<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cv;
use App\Models\CvMetadata;
use App\Models\WorkExperience;
use App\Models\Education;
use App\Models\Skill;
use App\Models\Language;
use App\Models\Hobby;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class CvManageController extends Controller
{
    public function index(Request $request)
    {
        // Obtener contexto de usuario
        $user = Auth::user();
        $userContext = [
            'is_logged_in' => (bool) $user,
            'user'         => $user,
            'role'         => $user->role ?? 'user',
            'is_admin'     => $user->is_admin ?? false,
        ];

        // Obtener parámetros de búsqueda y filtro
        $search = $request->input('search', '');
        $templateFilter = $request->input('template', '');

        // Construir la consulta base
        $query = Cv::leftJoin('cv_metadata as m', 'cv.id', '=', 'm.cv_id')
            ->select(
                'cv.id',
                'cv.name',
                'cv.profile_summary',
                'cv.user_id',
                'cv.email',
                'm.created_at',
                'm.template_type',
                'm.is_public',
                'm.published_at'
            );

        // Filtro de usuario (solo si NO es admin)
        if (!$userContext['is_admin']) {
            if ($userContext['is_logged_in']) {
                $query->where(function ($q) use ($userContext) {
                    $q->where('cv.user_id', $userContext['user']->id)
                      ->orWhere(function ($q) use ($userContext) {
                          $q->whereNull('cv.user_id')
                            ->where('cv.email', $userContext['user']->email);
                      });
                });
            } else {
                $query->whereNull('cv.user_id');
            }
        }

        // Filtro de búsqueda
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('cv.name', 'LIKE', "%$search%")
                  ->orWhere('cv.profile_summary', 'LIKE', "%$search%") ;
            });
        }

        // Filtro por plantilla
        if (!empty($templateFilter)) {
            $query->where('m.template_type', $templateFilter);
        }

        // Orden y paginación
        $query->orderByDesc('m.created_at')
              ->orderByDesc('cv.id');

        $cvs = $query->paginate(12)->withQueryString();

        // Pasar datos a la vista
        return view('manage.cv_list', [
            'cvs' => $cvs,
            'userContext' => $userContext,
            'search' => $search,
            'templateFilter' => $templateFilter,
        ]);
    }

    public function create()
    {
        return view('manage.cv_create');
    }

    public function createForm(Request $request)
    {
        $request->validate([
            'template_type' => 'required|in:nathan,esey',
        ]);
        $templateType = $request->template_type;
        return view('manage.cv_create_form', compact('templateType'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'template_type' => 'required|in:nathan,esey',
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone_number' => 'nullable|regex:/^\+?[0-9]{7,15}$/',
            'date_of_birth' => 'nullable|date',
            'address' => 'nullable|string',
            'linkedin_profile' => 'nullable|url',
            'portfolio' => 'nullable|url',
            'profile_summary' => 'required|string',
            'work_title.*' => 'nullable|string',
            'work_company.*' => 'nullable|string',
            'work_start.*' => 'nullable|date',
            'work_end.*' => 'nullable|date|after_or_equal:work_start.*',
            'work_current.*' => 'nullable|boolean',
            'work_description.*' => 'nullable|string',
            'education_degree.*' => 'nullable|string',
            'education_institution.*' => 'nullable|string',
            'education_start.*' => 'nullable|date',
            'education_end.*' => 'nullable|date|after_or_equal:education_start.*',
            'education_current.*' => 'nullable|boolean',
            'education_description.*' => 'nullable|string',
            'skill_name.*' => 'nullable|string',
            'skill_description.*' => 'nullable|string',
            'language_name.*' => 'nullable|string',
            'language_proficiency.*' => 'nullable|in:basic,conversational,fluent,native',
            'hobby_name.*' => 'nullable|string',
            'hobby_description.*' => 'nullable|string',
            'is_public' => 'boolean',
        ]);

        DB::beginTransaction();
        try {
            $cv = Cv::create([
                'name' => $request->name,
                'address' => $request->address,
                'phone_number' => $request->phone_number,
                'email' => $request->email,
                'date_of_birth' => $request->date_of_birth,
                'linkedin_profile' => $request->linkedin_profile,
                'portfolio' => $request->portfolio,
                'profile_summary' => $request->profile_summary,
                'user_id' => Auth::id() ?? null,
            ]);

            $templateTypeMap = ['nathan' => 1, 'esey' => 2, 'mirian' => 3];
            $templateTypeId = $templateTypeMap[$request->template_type] ?? 0;

            $isPublic = $request->is_public ? 1 : 0;
            $publishedAt = $isPublic ? now() : null;

            CvMetadata::create([
                'cv_id' => $cv->id,
                'template_type' => $templateTypeId,
                'is_public' => $isPublic,
                'published_at' => $publishedAt,
            ]);

            // Insert work experience
            if ($request->work_title) {
                foreach ($request->work_title as $key => $title) {
                    if ($title) {
                        WorkExperience::create([
                            'cv_id' => $cv->id,
                            'job_title' => $title,
                            'company_name' => $request->work_company[$key] ?? null,
                            'work_start' => $request->work_start[$key] ?? null,
                            'work_end' => $request->work_end[$key] ?? null,
                            'is_current' => $request->work_current[$key] ?? 0,
                            'description' => $request->work_description[$key] ?? null,
                        ]);
                    }
                }
            }

            // Insert education
            if ($request->education_degree) {
                foreach ($request->education_degree as $key => $degree) {
                    if ($degree) {
                        Education::create([
                            'cv_id' => $cv->id,
                            'degree' => $degree,
                            'institution' => $request->education_institution[$key] ?? null,
                            'education_start' => $request->education_start[$key] ?? null,
                            'education_end' => $request->education_end[$key] ?? null,
                            'is_current' => $request->education_current[$key] ?? 0,
                            'description' => $request->education_description[$key] ?? null,
                        ]);
                    }
                }
            }

            // Insert skills
            if ($request->skill_name) {
                foreach ($request->skill_name as $key => $name) {
                    if ($name) {
                        Skill::create([
                            'cv_id' => $cv->id,
                            'skill_name' => $name,
                            'description' => $request->skill_description[$key] ?? null,
                        ]);
                    }
                }
            }

            // Insert languages
            if ($request->language_name) {
                foreach ($request->language_name as $key => $name) {
                    if ($name) {
                        Language::create([
                            'cv_id' => $cv->id,
                            'language_name' => $name,
                            'proficiency' => $request->language_proficiency[$key] ?? 'basic',
                        ]);
                    }
                }
            }

            // Insert hobbies
            if ($request->hobby_name) {
                foreach ($request->hobby_name as $key => $name) {
                    if ($name) {
                        Hobby::create([
                            'cv_id' => $cv->id,
                            'hobby_name' => $name,
                            'description' => $request->hobby_description[$key] ?? null,
                        ]);
                    }
                }
            }

            DB::commit();

            return redirect()->route('manage.cvs.show', ['id' => $cv->id])->with('message', 'CV creado exitosamente!')->with('type', 'success');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('message', 'Error creando CV: ' . $e->getMessage())->with('type', 'error')->withInput();
        }
    }

    public function show($id)
    {
        $cv = Cv::with(['metadata', 'workExperiences', 'education', 'skills', 'languages', 'hobbies'])->findOrFail($id);
        return view('manage.cv_view', compact('cv'));
    }

    // Método para renderizar templates (público para previews)
    public function renderCVTemplate($id)
    {
        $cv = Cv::with('metadata')->findOrFail($id)->toArray();
        $workExperiences = WorkExperience::where('cv_id', $id)->get()->toArray();
        $education = Education::where('cv_id', $id)->get()->toArray();
        $hobbies = Hobby::where('cv_id', $id)->get()->toArray();
        $skills = Skill::where('cv_id', $id)->get()->toArray();
        $languages = Language::where('cv_id', $id)->get()->toArray();

        $templateType = $this->getTemplateType($cv);
        $allowedTemplates = ['nathan', 'esey', 'mirian'];
        if (!in_array($templateType, $allowedTemplates)) {
            $templateType = 'nathan';
        }
        $templateView = "templates.template_{$templateType}";
        if (!view()->exists($templateView)) {
            $templateView = 'templates.template_nathan';
            if (!view()->exists($templateView)) {
                abort(404, "Template file not found: {$templateView}");
            }
        }
        return view($templateView, [
            'cv' => $cv,
            'workExperiences' => $workExperiences,
            'education' => $education,
            'hobbies' => $hobbies,
            'skills' => $skills,
            'languages' => $languages,
        ]);
    }

    protected function getTemplateType($cv)
    {
        if (isset($cv['template_type'])) {
            if (is_numeric($cv['template_type'])) {
                return getTemplateName($cv['template_type']);
            }
            return $cv['template_type'];
        }
        return 'nathan';
    }

    // Método para exportar PDF (protegido)
    public function exportPdf($id)
    {
        $cv = Cv::with(['metadata', 'workExperiences', 'education', 'skills', 'languages', 'hobbies'])->findOrFail($id);
        $templateType = $this->getTemplateType($cv);
        $pdfView = "templates.template_{$templateType}_pdf";
        if (!view()->exists($pdfView)) {
            abort(404, "PDF template not found");
        }
        $pdf = Pdf::loadView($pdfView, compact('cv'));
        return $pdf->download("cv_{$id}.pdf");
    }

    // Método para exportar XML (protegido)
    public function exportXml($id)
    {
        $cv = Cv::with(['metadata', 'workExperiences', 'education', 'skills', 'languages', 'hobbies'])->findOrFail($id);
        $xml = new \SimpleXMLElement('<cv/>');
        $xml->addChild('name', $cv->name);
        $xml->addChild('email', $cv->email);
        // Agrega más campos y secciones (work, education, etc.) como XML
        return response($xml->asXML(), 200)
            ->header('Content-Type', 'application/xml')
            ->header('Content-Disposition', "attachment; filename=cv_{$id}.xml");
    }
    // Aquí irán los otros métodos para edit, update, etc.
}

