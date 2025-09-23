<?php

namespace App\Http\Controllers;

use App\Models\Cv;
use App\Models\CvMetadata;
use App\Models\Skill;
use App\Models\Language;
use App\Models\WorkExperience;
use App\Models\Education;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CvController extends Controller
{
    /**
     * Display a listing of the user's CVs.
     */
    public function index(): View
    {
        $user = Auth::user();
        
        $cvs = Cv::with(['metadata', 'skills', 'languages'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('cv.index', compact('cvs'));
    }

    /**
     * Show template selection page.
     */
    public function templateSelection(): View
    {
        return view('cv.template-selection');
    }

    /**
     * Show the form for creating a new CV.
     */
    public function createForm(Request $request): View
    {
        $request->validate([
            'template_type' => 'required|in:nathan,esey,mirian'
        ]);

        $templateType = $request->template_type;
        
        return view('cv.create-form', compact('templateType'));
    }

    /**
     * Store a newly created CV.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'linkedin_profile' => 'nullable|url',
            'portfolio' => 'nullable|url',
            'profile_summary' => 'required|string',
            'template_type' => 'required|in:nathan,esey,mirian',
            'is_public' => 'nullable|boolean',
            // Work experience
            'work_title' => 'nullable|array',
            'work_company' => 'nullable|array',
            'work_start' => 'nullable|array',
            'work_end' => 'nullable|array',
            'work_current' => 'nullable|array',
            'work_description' => 'nullable|array',
            // Education
            'education_degree' => 'nullable|array',
            'education_institution' => 'nullable|array',
            'education_start' => 'nullable|array',
            'education_end' => 'nullable|array',
            'education_current' => 'nullable|array',
            'education_description' => 'nullable|array',
            // Skills
            'skill_name' => 'nullable|array',
            'skill_description' => 'nullable|array',
            // Languages
            'language_name' => 'nullable|array',
            'language_proficiency' => 'nullable|array',
            // Hobbies
            'hobby_name' => 'nullable|array',
            'hobby_description' => 'nullable|array',
        ]);

        // Convert template type to integer
        $templateTypeMap = ['nathan' => 1, 'esey' => 2, 'mirian' => 3];
        $templateType = $templateTypeMap[$request->template_type] ?? 1;

        DB::transaction(function () use ($request, $templateType) {
            // Create CV (user_id can be null for guest users)
            $cv = Cv::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'address' => $request->address,
                'date_of_birth' => $request->date_of_birth,
                'linkedin_profile' => $request->linkedin_profile,
                'portfolio' => $request->portfolio,
                'profile_summary' => $request->profile_summary,
                'user_id' => Auth::id(), // Will be null if not authenticated
            ]);

            // Create metadata
            CvMetadata::create([
                'cv_id' => $cv->id,
                'template_type' => $templateType,
                'is_public' => $request->boolean('is_public', true), // Default to public for guests
            ]);

            // Create work experience
            if ($request->has('work_title')) {
                foreach ($request->work_title as $index => $title) {
                    if (!empty($title)) {
                        WorkExperience::create([
                            'cv_id' => $cv->id,
                            'job_title' => $title,
                            'company_name' => $request->work_company[$index] ?? null,
                            'start_date' => $request->work_start[$index] ?? null,
                            'end_date' => $request->work_end[$index] ?? null,
                            'is_current' => isset($request->work_current[$index]),
                            'description' => $request->work_description[$index] ?? null,
                        ]);
                    }
                }
            }

            // Create education
            if ($request->has('education_degree')) {
                foreach ($request->education_degree as $index => $degree) {
                    if (!empty($degree)) {
                        Education::create([
                            'cv_id' => $cv->id,
                            'degree' => $degree,
                            'institution' => $request->education_institution[$index] ?? null,
                            'start_date' => $request->education_start[$index] ?? null,
                            'end_date' => $request->education_end[$index] ?? null,
                            'is_current' => isset($request->education_current[$index]),
                            'field_of_study' => $request->education_description[$index] ?? null,
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
        });

        // Redirect to the created CV
        return redirect()->route('cv.show', Cv::latest()->first())
            ->with('success', 'CV created successfully!');
    }

    /**
     * Display the specified CV.
     */
    public function show(Cv $cv): View
    {
        // Check if user can access this CV
        $user = Auth::user();
        if ($user && !$user->isAdmin() && $cv->user_id !== $user->id && !$cv->isPublic()) {
            abort(403, 'Unauthorized access to CV.');
        } elseif (!$user && !$cv->isPublic()) {
            abort(403, 'Unauthorized access to CV.');
        }

        $cv->load(['metadata', 'skills', 'languages', 'workExperience', 'education']);

        // Determine template type
        $templateType = $cv->metadata?->template_type ?? 1;
        
        // Return the appropriate template view
        switch ($templateType) {
            case 1:
                return view('cv.templates.nathan', compact('cv'));
            case 2:
                return view('cv.templates.esey', compact('cv'));
            case 3:
                return view('cv.templates.mirian', compact('cv'));
            default:
                return view('cv.templates.nathan', compact('cv'));
        }
    }

    /**
     * Show the form for editing the specified CV.
     */
    public function edit(Cv $cv): View
    {
        // Check if user can edit this CV
        if (!Auth::user()->isAdmin() && $cv->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to CV.');
        }

        $cv->load(['metadata', 'skills', 'languages', 'workExperience', 'education']);

        return view('cv.edit', compact('cv'));
    }

    /**
     * Update the specified CV.
     */
    public function update(Request $request, Cv $cv): RedirectResponse
    {
        // Check if user can edit this CV
        if (!Auth::user()->isAdmin() && $cv->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to CV.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'linkedin_profile' => 'nullable|url',
            'portfolio' => 'nullable|url',
            'profile_summary' => 'nullable|string',
            'template_type' => 'required|integer|in:1,2,3',
            'skills' => 'nullable|array',
            'skills.*.skill_name' => 'required_with:skills|string|max:255',
            'skills.*.description' => 'nullable|string',
            'languages' => 'nullable|array',
            'languages.*.language_name' => 'required_with:languages|string|max:255',
            'languages.*.proficiency' => 'required_with:languages|in:basic,conversational,fluent,native',
        ]);

        DB::transaction(function () use ($request, $cv) {
            // Update CV
            $cv->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'address' => $request->address,
                'date_of_birth' => $request->date_of_birth,
                'linkedin_profile' => $request->linkedin_profile,
                'portfolio' => $request->portfolio,
                'profile_summary' => $request->profile_summary,
            ]);

            // Update metadata
            $cv->metadata->update([
                'template_type' => $request->template_type,
            ]);

            // Update skills
            $cv->skills()->delete();
            if ($request->has('skills')) {
                foreach ($request->skills as $skillData) {
                    if (!empty($skillData['skill_name'])) {
                        Skill::create([
                            'cv_id' => $cv->id,
                            'skill_name' => $skillData['skill_name'],
                            'description' => $skillData['description'] ?? null,
                        ]);
                    }
                }
            }

            // Update languages
            $cv->languages()->delete();
            if ($request->has('languages')) {
                foreach ($request->languages as $languageData) {
                    if (!empty($languageData['language_name'])) {
                        Language::create([
                            'cv_id' => $cv->id,
                            'language_name' => $languageData['language_name'],
                            'proficiency' => $languageData['proficiency'],
                        ]);
                    }
                }
            }
        });

        return redirect()->route('cv.index')
            ->with('success', 'CV updated successfully!');
    }

    /**
     * Remove the specified CV.
     */
    public function destroy(Cv $cv): RedirectResponse
    {
        // Check if user can delete this CV
        if (!Auth::user()->isAdmin() && $cv->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to CV.');
        }

        $cv->delete();

        return redirect()->route('cv.index')
            ->with('success', 'CV deleted successfully!');
    }

    /**
     * Publish the specified CV.
     */
    public function publish(Cv $cv): RedirectResponse
    {
        // Check if user can publish this CV
        if (!Auth::user()->isAdmin() && $cv->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to CV.');
        }

        $cv->metadata->publish();

        return redirect()->route('cv.index')
            ->with('success', 'CV published successfully!');
    }

    /**
     * Unpublish the specified CV.
     */
    public function unpublish(Cv $cv): RedirectResponse
    {
        // Check if user can unpublish this CV
        if (!Auth::user()->isAdmin() && $cv->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to CV.');
        }

        $cv->metadata->unpublish();

        return redirect()->route('cv.index')
            ->with('success', 'CV unpublished successfully!');
    }
}