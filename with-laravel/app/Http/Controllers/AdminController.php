<?php

namespace App\Http\Controllers;

use App\Models\Cv;
use App\Models\User;
use App\Models\CvMetadata;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Get statistics
        $stats = [
            'total_cvs' => Cv::count(),
            'total_users' => User::count(),
            'regular_users' => User::where('role', 'user')->count(),
            'admins' => User::where('role', 'admin')->count(),
        ];

        // Get CVs by template
        $cvsByTemplate = CvMetadata::select('template_type', DB::raw('count(*) as count'))
            ->groupBy('template_type')
            ->get()
            ->pluck('count', 'template_type')
            ->toArray();

        // Get recent CVs
        $recentCvs = Cv::with('metadata')
            ->join('cv_metadata', 'cv.id', '=', 'cv_metadata.cv_id')
            ->orderBy('cv_metadata.created_at', 'desc')
            ->limit(5)
            ->get(['cv.*', 'cv_metadata.template_type', 'cv_metadata.created_at as metadata_created_at']);

        // Convert the metadata_created_at to Carbon instances for proper formatting
        $recentCvs->transform(function ($cv) {
            if ($cv->metadata_created_at) {
                $cv->metadata_created_at = Carbon::parse($cv->metadata_created_at);
            }
            return $cv;
        });

        return view('admin.dashboard', compact('stats', 'cvsByTemplate', 'recentCvs'));
    }

    public function audit()
    {
        // TODO: Implement audit logs viewer
        return view('admin.audit');
    }

    public function sessions()
    {
        // TODO: Implement session management
        return view('admin.sessions');
    }

    public function getTemplateDisplayName($templateType)
    {
        $templates = [
            0 => 'Default Template',
            1 => 'Esey Template',
            2 => 'Nathan Template',
            3 => 'Mirian Template',
        ];

        return $templates[$templateType] ?? 'Unknown Template';
    }
}
