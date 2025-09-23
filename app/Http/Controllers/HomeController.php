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
        // Get public CVs ordered by publication date
        $cvs = Cv::with(['metadata', 'skills', 'languages'])
            ->whereHas('metadata', function ($query) {
                $query->where('is_public', true);
            })
            ->join('cv_metadata', 'cv.id', '=', 'cv_metadata.cv_id')
            ->orderBy('cv_metadata.published_at', 'desc')
            ->select('cv.*')
            ->get();

        return view('welcome', compact('cvs'));
    }
}