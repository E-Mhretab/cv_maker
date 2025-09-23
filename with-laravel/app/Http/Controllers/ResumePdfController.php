<?php

namespace App\Http\Controllers;

use App\Models\Cv;
use App\Services\PdfExportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResumePdfController extends Controller
{
    protected $pdfExportService;

    public function __construct(PdfExportService $pdfExportService)
    {
        $this->pdfExportService = $pdfExportService;
    }

    /**
     * Generate and download CV as PDF
     */
    public function download(Cv $cv)
    {
        // Check if user can access this CV
        if (Auth::user()->role !== 'admin' && $cv->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Generate PDF using the service
        $result = $this->pdfExportService->generatePdf($cv);

        if ($result['success']) {
            return response($result['content'])
                ->header('Content-Type', $result['mime_type'])
                ->header('Content-Disposition', 'attachment; filename="' . $result['filename'] . '"');
        } else {
            // Return HTML fallback
            return response($result['content'])
                ->header('Content-Type', $result['mime_type'])
                ->header('Content-Disposition', 'attachment; filename="' . $result['filename'] . '"');
        }
    }

    /**
     * Generate and stream CV as PDF (for preview)
     */
    public function stream(Cv $cv)
    {
        // Check if user can access this CV
        if (Auth::user()->role !== 'admin' && $cv->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Generate PDF using the service
        $result = $this->pdfExportService->generatePdf($cv);

        if ($result['success']) {
            return response($result['content'])
                ->header('Content-Type', $result['mime_type'])
                ->header('Content-Disposition', 'inline; filename="' . $result['filename'] . '"');
        } else {
            // Return HTML fallback
            return response($result['content'])
                ->header('Content-Type', $result['mime_type'])
                ->header('Content-Disposition', 'inline; filename="' . $result['filename'] . '"');
        }
    }
}