<?php

namespace App\Http\Controllers;

use App\Models\Cv;
use App\Models\CvMetadata;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Show admin dashboard with statistics.
     */
    public function dashboard(): View
    {
        // Get statistics
        $stats = [];
        
        // Total CVs
        $stats['total_cvs'] = Cv::count();
        
        // CVs by template
        $stats['by_template'] = CvMetadata::select('template_type', DB::raw('COUNT(*) as count'))
            ->groupBy('template_type')
            ->pluck('count', 'template_type')
            ->toArray();
        
        // Recent CVs
        $stats['recent_cvs'] = Cv::with('metadata')
            ->join('cv_metadata', 'cv.id', '=', 'cv_metadata.cv_id')
            ->select('cv.id', 'cv.name', 'cv_metadata.created_at', 'cv_metadata.template_type')
            ->orderBy('cv_metadata.created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Total users
        $stats['total_users'] = User::count();
        
        // Users by role
        $stats['by_role'] = User::select('role', DB::raw('COUNT(*) as count'))
            ->groupBy('role')
            ->pluck('count', 'role')
            ->toArray();
        
        return view('admin.dashboard', compact('stats'));
    }
    
    /**
     * Show all CVs for admin management.
     */
    public function cvList(Request $request): View
    {
        $search = $request->get('search', '');
        $templateFilter = $request->get('template', '');
        
        $query = Cv::with('metadata')
            ->join('cv_metadata', 'cv.id', '=', 'cv_metadata.cv_id')
            ->select('cv.id', 'cv.name', 'cv.profile_summary', 'cv.user_id', 'cv.email', 
                    'cv_metadata.created_at', 'cv_metadata.template_type', 'cv_metadata.is_public', 'cv_metadata.published_at');
        
        // Apply search filter
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('cv.name', 'LIKE', "%{$search}%")
                  ->orWhere('cv.profile_summary', 'LIKE', "%{$search}%");
            });
        }
        
        // Apply template filter
        if (!empty($templateFilter)) {
            $query->where('cv_metadata.template_type', $templateFilter);
        }
        
        $cvs = $query->orderBy('cv_metadata.created_at', 'desc')
                    ->orderBy('cv.id', 'desc')
                    ->paginate(20);
        
        // Get template options for filter
        $templateOptions = CvMetadata::select('template_type')
            ->distinct()
            ->pluck('template_type')
            ->toArray();
        
        return view('admin.cv-list', compact('cvs', 'search', 'templateFilter', 'templateOptions'));
    }
    
    /**
     * Show user management.
     */
    public function userList(Request $request): View
    {
        $search = $request->get('search', '');
        $roleFilter = $request->get('role', '');
        
        $query = User::select('id', 'username', 'email', 'role', 'is_active', 'created_at', 'last_login');
        
        // Apply search filter
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('username', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%");
            });
        }
        
        // Apply role filter
        if (!empty($roleFilter)) {
            $query->where('role', $roleFilter);
        }
        
        $users = $query->orderBy('created_at', 'desc')->paginate(20);
        
        return view('admin.user-list', compact('users', 'search', 'roleFilter'));
    }
}
