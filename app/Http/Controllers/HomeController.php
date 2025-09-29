<?php

namespace App\Http\Controllers;

use App\Models\Cv;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Display the homepage with public CVs.
     */
    public function index(): View
    {
        try {
            // Get public CVs ordered by publication date (same query as original PHP)
            $cvs = Cv::select('cv.id', 'cv.name', 'cv.profile_summary')
                ->join('cv_metadata', 'cv.id', '=', 'cv_metadata.cv_id')
                ->where('cv_metadata.is_public', 1)
                ->orderBy('cv_metadata.published_at', 'desc')
                ->get();
        } catch (\Exception $e) {
            // Fallback to empty collection if database error
            $cvs = collect([]);
        }

        return view('welcome', compact('cvs'));
    }
}