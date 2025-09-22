<?php

namespace App\Http\Controllers;

use App\Models\Cv;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ResumePdfController extends Controller
{
    /**
     * Generate and download CV as PDF
     */
    public function download(Cv $cv)
    {
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

        // Generate PDF filename
        $filename = 'CV_' . str_replace(' ', '_', $cv->name) . '_' . date('Y-m-d') . '.pdf';

        // Generate PDF using the blade template
        $pdf = Pdf::loadView('cvs.pdf', compact('cv'));

        // Set PDF options
        $pdf->setPaper('A4', 'portrait');
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'defaultFont' => 'Arial'
        ]);

        // Download the PDF
        return $pdf->download($filename);
    }

    /**
     * Generate and stream CV as PDF (for preview)
     */
    public function stream(Cv $cv)
    {
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

        // Generate PDF using the blade template
        $pdf = Pdf::loadView('cvs.pdf', compact('cv'));

        // Set PDF options
        $pdf->setPaper('A4', 'portrait');
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'defaultFont' => 'Arial'
        ]);

        // Stream the PDF
        return $pdf->stream('CV_' . str_replace(' ', '_', $cv->name) . '.pdf');
    }
}