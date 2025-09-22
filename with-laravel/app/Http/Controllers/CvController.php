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
    public function index()
    {
        $cvs = Cv::with(['user', 'metadata', 'workExperiences', 'education', 'skills', 'languages', 'hobbies'])
            ->where('user_id', Auth::id())
            ->paginate(10);
        
        return view('cvs.index', compact('cvs'));
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
            'nathan' => 1,
            'esey' => 2,
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
        return view('cvs.show', compact('cv'));
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
        return view('cvs.preview', compact('cv'));
    }

    /**
     * Export CV
     */
    public function export(Cv $cv)
    {
        // Implementation for CV export
        return response()->json($cv->toArray());
    }

    /**
     * Publish CV
     */
    public function publish(Cv $cv)
    {
        try {
            $cv->metadata()->updateOrCreate(
                ['cv_id' => $cv->id],
                ['is_public' => true, 'published_at' => now()]
            );
            
            return redirect()->route('cvs.show', $cv->id)
                ->with('success', 'CV published successfully!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error publishing CV: ' . $e->getMessage()]);
        }
    }
}
