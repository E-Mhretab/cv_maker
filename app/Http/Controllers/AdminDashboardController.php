<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cv;
use App\Models\CvMetadata;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            return redirect()->route('login');
        }

        $user = Auth::user(); // Define $user aquí

        $stats = [];

        // Total CVs
        $stats['total_cvs'] = Cv::count();

        // CVs by template
        $stats['by_template'] = Cv::leftJoin('cv_metadata as m', 'cv.id', '=', 'm.cv_id')
            ->select('m.template_type', DB::raw('COUNT(*) as count'))
            ->groupBy('m.template_type')
            ->pluck('count', 'm.template_type')
            ->toArray();

        // Recent CVs (convertido a colección)
        $stats['recent_cvs'] = collect(Cv::leftJoin('cv_metadata as m', 'cv.id', '=', 'm.cv_id')
            ->select('cv.id', 'cv.name', 'm.created_at', 'm.template_type')
            ->orderBy('m.created_at', 'desc')
            ->limit(5)
            ->get()
            ->toArray());

        // Total users
        $stats['total_users'] = User::count();

        // Users by role
        $stats['by_role'] = User::select('role', DB::raw('COUNT(*) as count'))
            ->groupBy('role')
            ->pluck('count', 'role')
            ->toArray();

        // Mapeo para nombres de templates
        $templateMap = [
            1 => 'Nathan Template',
            2 => 'Esey Template',
            3 => 'Mirian Template',
        ];

        // Crea array con nombres y counts
        $templates = [];
        foreach ($templateMap as $id => $name) {
            $templates[] = [
                'name' => $name,
                'count' => $stats['by_template'][$id] ?? 0,
            ];
        }

        return view('admin.dashboard', compact('stats', 'user', 'templates'));
    }
}
