<?php

namespace App\Http\Controllers;

use App\Models\Cv;
use App\Models\CvMetadata;
use App\Models\WorkExperience;
use App\Models\Education;
use App\Models\Skill;
use App\Models\Language;
use App\Models\Hobby;
use App\Http\Requests\StoreCvRequest;
use App\Http\Requests\UpdateCvRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CvController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // For admins, show all CVs. For regular users, show only their own CVs
        $query = Cv::with(['user', 'metadata', 'workExperiences', 'education', 'skills', 'languages', 'hobbies']);
        
        if (Auth::user()->role !== 'admin') {
            $query->where('user_id', Auth::id());
        }
        
        // Apply search filter
        if ($request->filled('search')) {
            $searchTerm = $request->get('search');
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('profile_summary', 'like', "%{$searchTerm}%")
                  ->orWhere('email', 'like', "%{$searchTerm}%");
            });
        }
        
        // Apply template type filter
        if ($request->filled('template_type')) {
            $templateType = $request->get('template_type');
            $query->whereHas('metadata', function($q) use ($templateType) {
                $q->where('template_type', $templateType);
            });
        }
        
        // Load all CVs instead of pagination
        $cvs = $query->orderBy('id', 'desc')->get();
        
        // Get statistics for all users
        $stats = [];
        
        if (Auth::user()->role === 'admin') {
            // Create base query for statistics (same filters as main query)
            $statsQuery = Cv::with(['metadata']);
            
            // Apply same search filter to statistics
            if ($request->filled('search')) {
                $searchTerm = $request->get('search');
                $statsQuery->where(function($q) use ($searchTerm) {
                    $q->where('name', 'like', "%{$searchTerm}%")
                      ->orWhere('profile_summary', 'like', "%{$searchTerm}%")
                      ->orWhere('email', 'like', "%{$searchTerm}%");
                });
            }
            
            // Apply same template filter to statistics
            if ($request->filled('template_type')) {
                $templateType = $request->get('template_type');
                $statsQuery->whereHas('metadata', function($q) use ($templateType) {
                    $q->where('template_type', $templateType);
                });
            }
            
            $stats = [
                'total_cvs' => $statsQuery->count(),
                'nathan_templates' => $statsQuery->whereHas('metadata', function($q) { $q->where('template_type', 2); })->count(),
                'esey_templates' => $statsQuery->whereHas('metadata', function($q) { $q->where('template_type', 1); })->count(),
                'created_today' => $statsQuery->whereHas('metadata', function($q) { $q->whereDate('created_at', today()); })->count(),
            ];
        } else {
            // For regular users, show statistics for their own CVs only
            $userCvsQuery = Cv::where('user_id', Auth::id());
            
            // Apply same search filter to user's CVs
            if ($request->filled('search')) {
                $searchTerm = $request->get('search');
                $userCvsQuery->where(function($q) use ($searchTerm) {
                    $q->where('name', 'like', "%{$searchTerm}%")
                      ->orWhere('profile_summary', 'like', "%{$searchTerm}%")
                      ->orWhere('email', 'like', "%{$searchTerm}%");
                });
            }
            
            // Apply same template filter to user's CVs
            if ($request->filled('template_type')) {
                $templateType = $request->get('template_type');
                $userCvsQuery->whereHas('metadata', function($q) use ($templateType) {
                    $q->where('template_type', $templateType);
                });
            }
            
            $stats = [
                'total_cvs' => $userCvsQuery->count(),
                'nathan_templates' => $userCvsQuery->whereHas('metadata', function($q) { $q->where('template_type', 2); })->count(),
                'esey_templates' => $userCvsQuery->whereHas('metadata', function($q) { $q->where('template_type', 1); })->count(),
                'created_today' => $userCvsQuery->whereHas('metadata', function($q) { $q->whereDate('created_at', today()); })->count(),
            ];
        }
        
        return view('cvs.index', compact('cvs', 'stats'));
    }

    /**
     * Show the template selection page.
     */
    public function create()
    {
        return view('cvs.template-selection');
    }

    /**
     * Show the CV creation form with selected template.
     */
    public function createForm(Request $request)
    {
        $request->validate([
            'template_type' => 'required|string|in:nathan,esey,mirian'
        ]);

        $templateType = $request->template_type;
        
        // Convert template name to integer for database
        $templateMap = [
            'nathan' => 2,
            'esey' => 1,
            'mirian' => 3
        ];
        
        $templateId = $templateMap[$templateType];
        
        return view('cvs.create', compact('templateType', 'templateId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCvRequest $request)
    {
        \Log::info('CV Store method called', ['request_data' => $request->all()]);
        
        try {
            \Log::info('Starting database transaction');
            DB::beginTransaction();

            // Template type is now passed as integer directly
            $templateTypeId = $request->template_type;

            // Create CV record
            \Log::info('Creating CV record', ['user_id' => Auth::id()]);
            $cv = Cv::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'date_of_birth' => $request->date_of_birth,
                'address' => $request->address,
                'linkedin_profile' => $request->linkedin_profile,
                'portfolio' => $request->portfolio,
                'profile_summary' => $request->profile_summary,
                'user_id' => Auth::id(),
            ]);
            \Log::info('CV record created', ['cv_id' => $cv->id]);

            // Create CV metadata
            $isPublic = $request->boolean('is_public');
            $publishedAt = $isPublic ? now() : null;

            CvMetadata::create([
                'cv_id' => $cv->id,
                'template_type' => $templateTypeId,
                'is_public' => $isPublic,
                'published_at' => $publishedAt,
            ]);

            // Create work experiences
            if ($request->has('work_title')) {
                foreach ($request->work_title as $index => $title) {
                    if (!empty($title) && !empty($request->work_company[$index])) {
                        WorkExperience::create([
                            'cv_id' => $cv->id,
                            'job_title' => $title,
                            'company_name' => $request->work_company[$index],
                            'work_start' => $request->work_start[$index] ?? null,
                            'work_end' => $request->work_end[$index] ?? null,
                            'is_current' => isset($request->work_current[$index]),
                            'description' => $request->work_description[$index] ?? null,
                        ]);
                    }
                }
            }

            // Create education records
            if ($request->has('education_degree')) {
                foreach ($request->education_degree as $index => $degree) {
                    if (!empty($degree) && !empty($request->education_institution[$index])) {
                        Education::create([
                            'cv_id' => $cv->id,
                            'degree' => $degree,
                            'institution' => $request->education_institution[$index],
                            'education_start' => $request->education_start[$index] ?? null,
                            'education_end' => $request->education_end[$index] ?? null,
                            'is_current' => isset($request->education_current[$index]),
                            'description' => $request->education_description[$index] ?? null,
                        ]);
                    }
                }
            }

            // Create skills
            if ($request->has('skill_name')) {
                foreach ($request->skill_name as $index => $skillName) {
                    if (!empty($skillName)) {
                        Skill::create([
                            'cv_id' => $cv->id,
                            'skill_name' => $skillName,
                            'description' => $request->skill_description[$index] ?? null,
                        ]);
                    }
                }
            }

            // Create languages
            if ($request->has('language_name')) {
                foreach ($request->language_name as $index => $languageName) {
                    if (!empty($languageName)) {
                        Language::create([
                            'cv_id' => $cv->id,
                            'language_name' => $languageName,
                            'proficiency' => $request->language_proficiency[$index] ?? 'basic',
                        ]);
                    }
                }
            }

            // Create hobbies
            if ($request->has('hobby_name')) {
                foreach ($request->hobby_name as $index => $hobbyName) {
                    if (!empty($hobbyName)) {
                        Hobby::create([
                            'cv_id' => $cv->id,
                            'hobby_name' => $hobbyName,
                            'description' => $request->hobby_description[$index] ?? null,
                        ]);
                    }
                }
            }

            \Log::info('Committing transaction');
            DB::commit();

            \Log::info('CV created successfully, redirecting to show page', ['cv_id' => $cv->id]);
            return redirect()->route('cvs.show', $cv->id)
                ->with('success', 'CV created successfully!');

        } catch (\Exception $e) {
            \Log::error('Error creating CV', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            DB::rollBack();
            
            return back()
                ->withInput()
                ->withErrors(['error' => 'Error creating CV: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Cv $cv)
    {
        $cv->load(['user', 'metadata', 'workExperiences', 'education', 'skills', 'languages', 'hobbies']);
        
        // Get template type from metadata
        $templateType = $cv->metadata->template_type ?? 1;
        
        // Choose template based on template type
        switch ($templateType) {
            case 1: // Esey Template
                return view('cvs.templates.esey', compact('cv'));
            case 2: // Nathan Template  
                return view('cvs.templates.nathan', compact('cv'));
            case 3: // Mirian Template
                return view('cvs.templates.mirian', compact('cv'));
            default:
                return view('cvs.templates.esey', compact('cv'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cv $cv)
    {
        $cv->load(['metadata', 'workExperiences', 'education', 'skills', 'languages', 'hobbies']);
        return view('cvs.edit', compact('cv'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCvRequest $request, Cv $cv)
    {
        try {
            DB::beginTransaction();

            // Template type is now passed as integer directly
            $templateTypeId = $request->template_type;

            // Update CV record
            $cv->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'date_of_birth' => $request->date_of_birth,
                'address' => $request->address,
                'linkedin_profile' => $request->linkedin_profile,
                'portfolio' => $request->portfolio,
                'profile_summary' => $request->profile_summary,
            ]);

            // Update CV metadata
            $isPublic = $request->boolean('is_public');
            $publishedAt = $isPublic ? now() : null;

            $cv->metadata()->updateOrCreate(
                ['cv_id' => $cv->id],
                [
                    'template_type' => $templateTypeId,
                    'is_public' => $isPublic,
                    'published_at' => $publishedAt,
                ]
            );

            // Delete existing related records and recreate them
            $cv->workExperiences()->delete();
            $cv->education()->delete();
            $cv->skills()->delete();
            $cv->languages()->delete();
            $cv->hobbies()->delete();

            // Recreate work experiences
            if ($request->has('work_title')) {
                foreach ($request->work_title as $index => $title) {
                    if (!empty($title) && !empty($request->work_company[$index])) {
                        WorkExperience::create([
                            'cv_id' => $cv->id,
                            'job_title' => $title,
                            'company_name' => $request->work_company[$index],
                            'work_start' => $request->work_start[$index] ?? null,
                            'work_end' => $request->work_end[$index] ?? null,
                            'is_current' => isset($request->work_current[$index]),
                            'description' => $request->work_description[$index] ?? null,
                        ]);
                    }
                }
            }

            // Recreate education records
            if ($request->has('education_degree')) {
                foreach ($request->education_degree as $index => $degree) {
                    if (!empty($degree) && !empty($request->education_institution[$index])) {
                        Education::create([
                            'cv_id' => $cv->id,
                            'degree' => $degree,
                            'institution' => $request->education_institution[$index],
                            'education_start' => $request->education_start[$index] ?? null,
                            'education_end' => $request->education_end[$index] ?? null,
                            'is_current' => isset($request->education_current[$index]),
                            'description' => $request->education_description[$index] ?? null,
                        ]);
                    }
                }
            }

            // Recreate skills
            if ($request->has('skill_name')) {
                foreach ($request->skill_name as $index => $skillName) {
                    if (!empty($skillName)) {
                        Skill::create([
                            'cv_id' => $cv->id,
                            'skill_name' => $skillName,
                            'description' => $request->skill_description[$index] ?? null,
                        ]);
                    }
                }
            }

            // Recreate languages
            if ($request->has('language_name')) {
                foreach ($request->language_name as $index => $languageName) {
                    if (!empty($languageName)) {
                        Language::create([
                            'cv_id' => $cv->id,
                            'language_name' => $languageName,
                            'proficiency' => $request->language_proficiency[$index] ?? 'basic',
                        ]);
                    }
                }
            }

            // Recreate hobbies
            if ($request->has('hobby_name')) {
                foreach ($request->hobby_name as $index => $hobbyName) {
                    if (!empty($hobbyName)) {
                        Hobby::create([
                            'cv_id' => $cv->id,
                            'hobby_name' => $hobbyName,
                            'description' => $request->hobby_description[$index] ?? null,
                        ]);
                    }
                }
            }

            DB::commit();

            return redirect()->route('cvs.show', $cv->id)
                ->with('success', 'CV updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()
                ->withInput()
                ->withErrors(['error' => 'Error updating CV: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cv $cv)
    {
        try {
            $cv->delete();
            return redirect()->route('cvs.index')
                ->with('success', 'CV deleted successfully!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error deleting CV: ' . $e->getMessage()]);
        }
    }

    /**
     * Download CV as PDF
     */
    public function download(Cv $cv)
    {
        // Implementation for PDF download
        return response()->download($cv->getPdfPath());
    }

    /**
     * Preview CV
     */
    public function preview(Cv $cv)
    {
        $cv->load(['user', 'metadata', 'workExperiences', 'education', 'skills', 'languages', 'hobbies']);
        
        // Get template type from metadata
        $templateType = $cv->metadata->template_type ?? 1;
        
        // Choose template based on template type
        switch ($templateType) {
            case 1: // Esey Template
                return view('cvs.templates.esey', compact('cv'));
            case 2: // Nathan Template  
                return view('cvs.templates.nathan', compact('cv'));
            case 3: // Mirian Template
                return view('cvs.templates.mirian', compact('cv'));
            default:
                return view('cvs.templates.esey', compact('cv'));
        }
    }

    /**
     * Export CV as XML
     */
    public function export(Cv $cv)
    {
        // Check if user can access this CV
        if (Auth::user()->role !== 'admin' && $cv->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Load CV with all related data
        $cv->load([
            'user',
            'metadata',
            'workExperiences',
            'education',
            'skills',
            'languages',
            'hobbies'
        ]);

        // Generate XML filename
        $filename = 'CV_' . preg_replace('/[^a-zA-Z0-9_-]/', '_', $cv->name) . '_' . date('Y-m-d') . '.xml';

        // Generate XML content
        $xml = $this->generateXmlContent($cv);

        return response($xml)
            ->header('Content-Type', 'application/xml')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Generate XML content for CV export
     */
    private function generateXmlContent(Cv $cv): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<cv>' . "\n";
        
        // Personal information
        $xml .= '  <personal_info>' . "\n";
        $xml .= '    <name>' . htmlspecialchars($cv->name ?? '') . '</name>' . "\n";
        $xml .= '    <address>' . htmlspecialchars($cv->address ?? '') . '</address>' . "\n";
        $xml .= '    <phone_number>' . htmlspecialchars($cv->phone_number ?? '') . '</phone_number>' . "\n";
        $xml .= '    <email>' . htmlspecialchars($cv->email ?? '') . '</email>' . "\n";
        
        if (!empty($cv->date_of_birth)) {
            $xml .= '    <date_of_birth>' . htmlspecialchars($cv->date_of_birth) . '</date_of_birth>' . "\n";
        }
        
        if (!empty($cv->portfolio)) {
            $xml .= '    <portfolio>' . htmlspecialchars($cv->portfolio) . '</portfolio>' . "\n";
        }
        
        if (!empty($cv->linkedin_profile)) {
            $xml .= '    <linkedin_profile>' . htmlspecialchars($cv->linkedin_profile) . '</linkedin_profile>' . "\n";
        }
        
        $xml .= '    <template_type>' . htmlspecialchars($cv->metadata->template_type ?? '') . '</template_type>' . "\n";
        $xml .= '    <created_at>' . htmlspecialchars($cv->created_at ?? '') . '</created_at>' . "\n";
        $xml .= '  </personal_info>' . "\n";
        
        // Profile summary
        if (!empty($cv->profile_summary)) {
            $xml .= '  <profile_summary>' . "\n";
            $xml .= '    <![CDATA[' . $cv->profile_summary . ']]>' . "\n";
            $xml .= '  </profile_summary>' . "\n";
        }
        
        // Work experience
        if ($cv->workExperiences->count() > 0) {
            $xml .= '  <work_experience>' . "\n";
            foreach ($cv->workExperiences as $work) {
                $xml .= '    <job>' . "\n";
                $xml .= '      <job_title>' . htmlspecialchars($work->job_title ?? '') . '</job_title>' . "\n";
                $xml .= '      <company>' . htmlspecialchars($work->company_name ?? '') . '</company>' . "\n";
                $xml .= '      <work_start>' . htmlspecialchars($work->work_start ?? '') . '</work_start>' . "\n";
                $xml .= '      <work_end>' . htmlspecialchars($work->work_end ?? '') . '</work_end>' . "\n";
                $xml .= '      <is_current>' . ($work->is_current ? 'true' : 'false') . '</is_current>' . "\n";
                if (!empty($work->description)) {
                    $xml .= '      <description><![CDATA[' . $work->description . ']]></description>' . "\n";
                }
                $xml .= '    </job>' . "\n";
            }
            $xml .= '  </work_experience>' . "\n";
        }
        
        // Education
        if ($cv->education->count() > 0) {
            $xml .= '  <education>' . "\n";
            foreach ($cv->education as $edu) {
                $xml .= '    <degree>' . "\n";
                $xml .= '      <degree_name>' . htmlspecialchars($edu->degree ?? '') . '</degree_name>' . "\n";
                $xml .= '      <institution>' . htmlspecialchars($edu->institution ?? '') . '</institution>' . "\n";
                $xml .= '      <education_start>' . htmlspecialchars($edu->education_start ?? '') . '</education_start>' . "\n";
                $xml .= '      <education_end>' . htmlspecialchars($edu->education_end ?? '') . '</education_end>' . "\n";
                $xml .= '      <is_current>' . ($edu->is_current ? 'true' : 'false') . '</is_current>' . "\n";
                if (!empty($edu->description)) {
                    $xml .= '      <description><![CDATA[' . $edu->description . ']]></description>' . "\n";
                }
                $xml .= '    </degree>' . "\n";
            }
            $xml .= '  </education>' . "\n";
        }
        
        // Skills
        if ($cv->skills->count() > 0) {
            $xml .= '  <skills>' . "\n";
            foreach ($cv->skills as $skill) {
                $xml .= '    <skill>' . "\n";
                $xml .= '      <skill_name>' . htmlspecialchars($skill->skill_name ?? '') . '</skill_name>' . "\n";
                if (!empty($skill->description)) {
                    $xml .= '      <description><![CDATA[' . $skill->description . ']]></description>' . "\n";
                }
                $xml .= '    </skill>' . "\n";
            }
            $xml .= '  </skills>' . "\n";
        }
        
        // Languages
        if ($cv->languages->count() > 0) {
            $xml .= '  <languages>' . "\n";
            foreach ($cv->languages as $language) {
                $xml .= '    <language>' . "\n";
                $xml .= '      <language_name>' . htmlspecialchars($language->language_name ?? '') . '</language_name>' . "\n";
                $xml .= '      <proficiency>' . htmlspecialchars($language->proficiency ?? '') . '</proficiency>' . "\n";
                $xml .= '    </language>' . "\n";
            }
            $xml .= '  </languages>' . "\n";
        }
        
        // Hobbies
        if ($cv->hobbies->count() > 0) {
            $xml .= '  <hobbies>' . "\n";
            foreach ($cv->hobbies as $hobby) {
                $xml .= '    <hobby>' . "\n";
                $xml .= '      <hobby_name>' . htmlspecialchars($hobby->hobby_name ?? '') . '</hobby_name>' . "\n";
                if (!empty($hobby->description)) {
                    $xml .= '      <description><![CDATA[' . $hobby->description . ']]></description>' . "\n";
                }
                $xml .= '    </hobby>' . "\n";
            }
            $xml .= '  </hobbies>' . "\n";
        }
        
        $xml .= '</cv>' . "\n";
        
        return $xml;
    }

    /**
     * Publish CV
     */

    /**
     * Show the template selection page for guests.
     */
    public function guestCreate()
    {
        return view('cvs.template-selection');
    }

    /**
     * Show the CV creation form for guests with selected template.
     */
    public function guestCreateForm(Request $request)
    {
        $request->validate([
            'template_type' => 'required|string|in:nathan,esey,mirian'
        ]);

        $templateType = $request->template_type;
        
        // Convert template name to integer for database
        $templateMap = [
            'nathan' => 2,
            'esey' => 1,
            'mirian' => 3
        ];
        
        $templateId = $templateMap[$templateType];
        
        return view('cvs.create', compact('templateType', 'templateId'));
    }

    /**
     * Store a newly created CV for guests.
     */
    public function guestStore(StoreCvRequest $request)
    {
        \Log::info('Guest CV Store method called', ['request_data' => $request->all()]);
        
        try {
            DB::beginTransaction();

            // Create CV without user_id (guest CV)
            $cv = Cv::create([
                'name' => $request->name,
                'address' => $request->address,
                'phone_number' => $request->phone_number,
                'email' => $request->email,
                'date_of_birth' => $request->date_of_birth,
                'linkedin_profile' => $request->linkedin_profile,
                'portfolio' => $request->portfolio,
                'profile_summary' => $request->profile_summary,
                'user_id' => null, // Guest CV
            ]);

            // Create CV metadata
            $cvMetadata = CvMetadata::create([
                'cv_id' => $cv->id,
                'template_type' => $request->template_type,
                'is_public' => false, // Guest CVs are private by default
                'published_at' => null,
            ]);

            // Create work experiences
            if ($request->has('work_title')) {
                foreach ($request->work_title as $index => $title) {
                    if (!empty($title) && !empty($request->work_company[$index])) {
                        WorkExperience::create([
                            'cv_id' => $cv->id,
                            'job_title' => $title,
                            'company_name' => $request->work_company[$index],
                            'work_start' => $request->work_start[$index] ?? null,
                            'work_end' => $request->work_end[$index] ?? null,
                            'is_current' => isset($request->work_current[$index]),
                            'description' => $request->work_description[$index] ?? null,
                        ]);
                    }
                }
            }

            // Create education records
            if ($request->has('education_degree')) {
                foreach ($request->education_degree as $index => $degree) {
                    if (!empty($degree) && !empty($request->education_institution[$index])) {
                        Education::create([
                            'cv_id' => $cv->id,
                            'degree' => $degree,
                            'institution' => $request->education_institution[$index],
                            'education_start' => $request->education_start[$index] ?? null,
                            'education_end' => $request->education_end[$index] ?? null,
                            'is_current' => isset($request->education_current[$index]),
                            'description' => $request->education_description[$index] ?? null,
                        ]);
                    }
                }
            }

            // Create skills
            if ($request->has('skill_name')) {
                foreach ($request->skill_name as $index => $skillName) {
                    if (!empty($skillName)) {
                        Skill::create([
                            'cv_id' => $cv->id,
                            'skill_name' => $skillName,
                            'description' => $request->skill_description[$index] ?? null,
                        ]);
                    }
                }
            }

            // Create languages
            if ($request->has('language_name')) {
                foreach ($request->language_name as $index => $languageName) {
                    if (!empty($languageName)) {
                        Language::create([
                            'cv_id' => $cv->id,
                            'language_name' => $languageName,
                            'proficiency' => $request->language_proficiency[$index] ?? 'basic',
                        ]);
                    }
                }
            }

            // Create hobbies
            if ($request->has('hobby_name')) {
                foreach ($request->hobby_name as $index => $hobbyName) {
                    if (!empty($hobbyName)) {
                        Hobby::create([
                            'cv_id' => $cv->id,
                            'hobby_name' => $hobbyName,
                            'description' => $request->hobby_description[$index] ?? null,
                        ]);
                    }
                }
            }

            DB::commit();

            \Log::info('Guest CV created successfully', ['cv_id' => $cv->id]);

            return redirect()->route('guest.cvs.show', $cv->id)
                ->with('success', 'CV created successfully! You can view and download your CV below.');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Guest CV creation failed', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return back()->withErrors(['error' => 'Error creating CV: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Display the specified CV for guests.
     */
    public function guestShow(Cv $cv)
    {
        $cv->load(['metadata', 'workExperiences', 'education', 'skills', 'languages', 'hobbies']);
        
        // Get template type from metadata
        $templateType = $cv->metadata->template_type ?? 1;
        
        // Choose template based on template type
        switch ($templateType) {
            case 1: // Esey Template
                return view('cvs.templates.esey', compact('cv'));
            case 2: // Nathan Template  
                return view('cvs.templates.nathan', compact('cv'));
            case 3: // Mirian Template
                return view('cvs.templates.mirian', compact('cv'));
            default:
                return view('cvs.templates.esey', compact('cv'));
        }
    }

    /**
     * Display a public CV (no authentication required).
     */
    public function publicShow(Cv $cv)
    {
        $cv->load(['metadata', 'workExperiences', 'education', 'skills', 'languages', 'hobbies']);
        
        // Check if CV is public
        if (!$cv->metadata || !$cv->metadata->is_public) {
            abort(404, 'CV not found or not public');
        }
        
        // Get template type from metadata
        $templateType = $cv->metadata->template_type ?? 1;
        
        // Choose template based on template type
        switch ($templateType) {
            case 1: // Esey Template
                return view('cvs.templates.esey', compact('cv'));
            case 2: // Nathan Template  
                return view('cvs.templates.nathan', compact('cv'));
            case 3: // Mirian Template
                return view('cvs.templates.mirian', compact('cv'));
            default:
                return view('cvs.templates.esey', compact('cv'));
        }
    }

    /**
     * Publish or unpublish a CV.
     */
    public function publish(Request $request, Cv $cv)
    {
        // Check if user is admin or CV owner
        if (Auth::user()->role !== 'admin' && $cv->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $action = $request->query('action');
        
        if ($action === 'publish') {
            $cv->metadata->update([
                'is_public' => true,
                'published_at' => now()
            ]);
            $message = 'CV has been made public and is now visible to everyone.';
        } elseif ($action === 'unpublish') {
            $cv->metadata->update([
                'is_public' => false,
                'published_at' => null
            ]);
            $message = 'CV has been made private and is no longer visible to others.';
        } else {
            return redirect()->back()->withErrors(['error' => 'Invalid action.']);
        }

        return redirect()->back()->with('success', $message);
    }
}
