<?php

namespace App\Http\Controllers;

use App\Models\Cv;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Get published CVs for the homepage
        $cvs = Cv::with('metadata')
            ->whereHas('metadata', function($query) {
                $query->where('is_public', true);
            })
            ->orderBy('id', 'desc')
            ->get();

        return view('welcome', compact('cvs'));
    }
}
