<?php

namespace App\Services;

use App\Models\Cv;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PdfExportService
{
    /**
     * Generate PDF using wkhtmltopdf with fallbacks
     */
    public function generatePdf(Cv $cv): array
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

        // Generate filename
        $filename = 'CV_' . preg_replace('/[^a-zA-Z0-9_-]/', '_', $cv->name) . '_' . date('Y-m-d') . '.pdf';

        // Check if exec functions are available
        if (!$this->hasExecFunctions()) {
            return $this->generateHtmlFallback($cv, $filename);
        }

        // Check for wkhtmltopdf installation
        $wkhtmltopdfPath = $this->findWkhtmltopdfPath();
        
        if (!$wkhtmltopdfPath) {
            // Try DomPDF as fallback for local development
            return $this->tryDomPdfFallback($cv, $filename);
        }

        // Generate HTML content
        $html = $this->generateHtmlContent($cv);
        
        // Create temporary files
        $tempHtmlFile = tempnam(sys_get_temp_dir(), 'cv_export_') . '.html';
        $tempPdfFile = tempnam(sys_get_temp_dir(), 'cv_export_') . '.pdf';

        try {
            // Write HTML to temporary file
            file_put_contents($tempHtmlFile, $html);

            // Build wkhtmltopdf command with optimized options
            $command = $this->buildWkhtmltopdfCommand($wkhtmltopdfPath, $tempHtmlFile, $tempPdfFile);

            // Execute command
            $success = $this->executeCommand($command, $tempPdfFile);

            if ($success) {
                // Read PDF content
                $pdfContent = file_get_contents($tempPdfFile);
                
                // Clean up temporary files
                unlink($tempHtmlFile);
                unlink($tempPdfFile);

                return [
                    'success' => true,
                    'content' => $pdfContent,
                    'filename' => $filename,
                    'mime_type' => 'application/pdf'
                ];
            } else {
                // Log error for debugging
                Log::error('PDF generation failed', [
                    'cv_id' => $cv->id,
                    'command' => $command
                ]);

                return $this->generateHtmlFallback($cv, $filename, 'PDF generation failed');
            }

        } catch (\Exception $e) {
            Log::error('PDF generation exception', [
                'cv_id' => $cv->id,
                'error' => $e->getMessage()
            ]);

            return $this->generateHtmlFallback($cv, $filename, 'PDF generation exception: ' . $e->getMessage());
        } finally {
            // Clean up temporary files
            if (file_exists($tempHtmlFile)) {
                unlink($tempHtmlFile);
            }
            if (file_exists($tempPdfFile)) {
                unlink($tempPdfFile);
            }
        }
    }

    /**
     * Check if exec functions are available
     */
    private function hasExecFunctions(): bool
    {
        return function_exists('exec') || function_exists('shell_exec') || function_exists('system');
    }

    /**
     * Find wkhtmltopdf installation path
     */
    private function findWkhtmltopdfPath(): ?string
    {
        $possiblePaths = [
            '/usr/local/bin/wkhtmltopdf',
            '/usr/bin/wkhtmltopdf',
            '/opt/wkhtmltopdf/bin/wkhtmltopdf',
            '/Applications/wkhtmltopdf.app/Contents/MacOS/wkhtmltopdf', // macOS app bundle
            'wkhtmltopdf' // Try PATH
        ];

        foreach ($possiblePaths as $path) {
            if ($this->isWkhtmltopdfExecutable($path)) {
                return $path;
            }
        }

        return null;
    }

    /**
     * Check if wkhtmltopdf is executable at given path
     */
    private function isWkhtmltopdfExecutable(string $path): bool
    {
        if ($path === 'wkhtmltopdf') {
            // Check if it's in PATH
            $output = [];
            $returnCode = 0;
            exec('which wkhtmltopdf 2>/dev/null', $output, $returnCode);
            return $returnCode === 0 && !empty($output[0]);
        }

        return file_exists($path) && is_executable($path);
    }

    /**
     * Build wkhtmltopdf command with optimized options
     */
    private function buildWkhtmltopdfCommand(string $wkhtmltopdfPath, string $htmlFile, string $pdfFile): string
    {
        return escapeshellcmd($wkhtmltopdfPath) . 
               ' --page-size A4' .
               ' --margin-top 0.5in' .
               ' --margin-right 0.5in' .
               ' --margin-bottom 0.5in' .
               ' --margin-left 0.5in' .
               ' --encoding UTF-8' .
               ' --no-outline' .
               ' --enable-local-file-access' .
               ' --print-media-type' .
               ' --disable-smart-shrinking' .
               ' --load-error-handling ignore' .
               ' --load-media-error-handling ignore' .
               ' --quiet' .
               ' ' . escapeshellarg($htmlFile) . 
               ' ' . escapeshellarg($pdfFile) . 
               ' 2>&1';
    }

    /**
     * Execute command using available functions
     */
    private function executeCommand(string $command, string $pdfFile): bool
    {
        $output = [];
        $returnCode = 0;
        $success = false;

        if (function_exists('exec')) {
            exec($command, $output, $returnCode);
            $success = ($returnCode === 0 && file_exists($pdfFile) && filesize($pdfFile) > 0);
        } elseif (function_exists('shell_exec')) {
            $result = shell_exec($command);
            $success = (file_exists($pdfFile) && filesize($pdfFile) > 0);
        } elseif (function_exists('system')) {
            ob_start();
            system($command, $returnCode);
            ob_end_clean();
            $success = ($returnCode === 0 && file_exists($pdfFile) && filesize($pdfFile) > 0);
        }

        return $success;
    }

    /**
     * Try DomPDF as fallback for local development
     */
    private function tryDomPdfFallback(Cv $cv, string $filename): array
    {
        try {
            // Try to use DomPDF as fallback
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('cvs.pdf', compact('cv'));
            $pdf->setPaper('A4', 'portrait');
            $pdf->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'defaultFont' => 'DejaVu Sans'
            ]);

            return [
                'success' => true,
                'content' => $pdf->output(),
                'filename' => $filename,
                'mime_type' => 'application/pdf'
            ];
        } catch (\Exception $e) {
            Log::error('DomPDF fallback failed', [
                'cv_id' => $cv->id,
                'error' => $e->getMessage()
            ]);

            return $this->generateHtmlFallback($cv, $filename, 'wkhtmltopdf not found and DomPDF failed: ' . $e->getMessage());
        }
    }

    /**
     * Generate HTML fallback when PDF generation fails
     */
    private function generateHtmlFallback(Cv $cv, string $filename, string $reason = 'PDF not available'): array
    {
        $htmlFilename = str_replace('.pdf', '.html', $filename);
        $htmlContent = $this->generateHtmlFallbackContent($cv, $reason);

        return [
            'success' => false,
            'content' => $htmlContent,
            'filename' => $htmlFilename,
            'mime_type' => 'text/html',
            'reason' => $reason
        ];
    }

    /**
     * Generate HTML content for PDF
     */
    private function generateHtmlContent(Cv $cv): string
    {
        // Get template type from metadata
        $templateType = $cv->metadata->template_type ?? 1;
        
        // Generate HTML based on template type
        switch ($templateType) {
            case 1: // Esey Template
                return $this->generateEseyHtml($cv);
            case 2: // Nathan Template
                return $this->generateNathanHtml($cv);
            case 3: // Mirian Template
                return $this->generateMirianHtml($cv);
            default:
                return $this->generateEseyHtml($cv);
        }
    }

    /**
     * Generate Esey template HTML for PDF
     */
    private function generateEseyHtml(Cv $cv): string
    {
        ob_start();
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>CV - <?= htmlspecialchars($cv->name) ?></title>
            <style>
                * {
                    margin: 0;
                    padding: 0;
                    box-sizing: border-box;
                }
                body {
                    font-family: 'DejaVu Sans', Arial, sans-serif;
                    line-height: 1.6;
                    color: #333;
                    background: white;
                }
                .cv-container {
                    max-width: 800px;
                    margin: 0 auto;
                    padding: 20px;
                }
                header {
                    background: #667eea;
                    color: white;
                    padding: 30px;
                    text-align: center;
                    margin-bottom: 30px;
                }
                header h1 {
                    font-size: 2.5em;
                    margin-bottom: 10px;
                }
                .contact-info {
                    font-size: 1.1em;
                }
                .section {
                    margin-bottom: 25px;
                    page-break-inside: avoid;
                }
                .section h2 {
                    background: #f8f9fa;
                    color: #667eea;
                    padding: 10px 15px;
                    margin-bottom: 15px;
                    border-left: 4px solid #667eea;
                }
                .section-content {
                    padding: 0 15px;
                }
                .work-item, .education-item {
                    margin-bottom: 15px;
                    padding-bottom: 10px;
                    border-bottom: 1px solid #eee;
                }
                .work-item:last-child, .education-item:last-child {
                    border-bottom: none;
                }
                .item-title {
                    font-weight: bold;
                    font-size: 1.1em;
                    color: #333;
                }
                .item-company, .item-institution {
                    color: #666;
                    font-style: italic;
                }
                .item-dates {
                    color: #888;
                    font-size: 0.9em;
                }
                .skills-list, .languages-list, .hobbies-list {
                    list-style: none;
                    padding: 0;
                }
                .skills-list li, .languages-list li, .hobbies-list li {
                    padding: 5px 0;
                    border-bottom: 1px solid #f0f0f0;
                }
                .skills-list li:last-child, .languages-list li:last-child, .hobbies-list li:last-child {
                    border-bottom: none;
                }
                @media print {
                    body { font-size: 12px; }
                    .cv-container { padding: 10px; }
                    header { padding: 20px; }
                    .section { margin-bottom: 20px; }
                }
            </style>
        </head>
        <body>
            <div class="cv-container">
                <header>
                    <h1><?= htmlspecialchars($cv->name) ?></h1>
                    <div class="contact-info">
                        <p><?= htmlspecialchars($cv->address) ?> | <?= htmlspecialchars($cv->phone_number) ?> | <?= htmlspecialchars($cv->email) ?></p>
                        <?php if ($cv->linkedin_profile): ?>
                        <p>LinkedIn: <?= htmlspecialchars($cv->linkedin_profile) ?></p>
                        <?php endif; ?>
                        <?php if ($cv->portfolio): ?>
                        <p>Portfolio: <?= htmlspecialchars($cv->portfolio) ?></p>
                        <?php endif; ?>
                    </div>
                </header>

                <?php if ($cv->profile_summary): ?>
                <div class="section">
                    <h2>Profile Summary</h2>
                    <div class="section-content">
                        <p><?= nl2br(htmlspecialchars($cv->profile_summary)) ?></p>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($cv->workExperiences->count() > 0): ?>
                <div class="section">
                    <h2>Work Experience</h2>
                    <div class="section-content">
                        <?php foreach ($cv->workExperiences as $work): ?>
                        <div class="work-item">
                            <div class="item-title"><?= htmlspecialchars($work->job_title) ?></div>
                            <div class="item-company"><?= htmlspecialchars($work->company_name) ?></div>
                            <div class="item-dates">
                                <?= $work->work_start ? \Carbon\Carbon::parse($work->work_start)->format('M Y') : '' ?> - 
                                <?= $work->is_current ? 'Current' : ($work->work_end ? \Carbon\Carbon::parse($work->work_end)->format('M Y') : '') ?>
                            </div>
                            <?php if ($work->description): ?>
                            <p><?= nl2br(htmlspecialchars($work->description)) ?></p>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($cv->education->count() > 0): ?>
                <div class="section">
                    <h2>Education</h2>
                    <div class="section-content">
                        <?php foreach ($cv->education as $edu): ?>
                        <div class="education-item">
                            <div class="item-title"><?= htmlspecialchars($edu->degree) ?></div>
                            <div class="item-institution"><?= htmlspecialchars($edu->institution) ?></div>
                            <div class="item-dates">
                                <?= $edu->education_start ? \Carbon\Carbon::parse($edu->education_start)->format('M Y') : '' ?> - 
                                <?= $edu->is_current ? 'Current' : ($edu->education_end ? \Carbon\Carbon::parse($edu->education_end)->format('M Y') : '') ?>
                            </div>
                            <?php if ($edu->description): ?>
                            <p><?= nl2br(htmlspecialchars($edu->description)) ?></p>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($cv->skills->count() > 0): ?>
                <div class="section">
                    <h2>Skills</h2>
                    <div class="section-content">
                        <ul class="skills-list">
                            <?php foreach ($cv->skills as $skill): ?>
                            <li><?= htmlspecialchars($skill->skill_name) ?>
                                <?php if ($skill->description): ?>
                                - <?= htmlspecialchars($skill->description) ?>
                                <?php endif; ?>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($cv->languages->count() > 0): ?>
                <div class="section">
                    <h2>Languages</h2>
                    <div class="section-content">
                        <ul class="languages-list">
                            <?php foreach ($cv->languages as $language): ?>
                            <li><?= htmlspecialchars($language->language_name) ?> - <?= ucfirst($language->proficiency) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($cv->hobbies->count() > 0): ?>
                <div class="section">
                    <h2>Hobbies & Interests</h2>
                    <div class="section-content">
                        <ul class="hobbies-list">
                            <?php foreach ($cv->hobbies as $hobby): ?>
                            <li><?= htmlspecialchars($hobby->hobby_name) ?>
                                <?php if ($hobby->description): ?>
                                - <?= htmlspecialchars($hobby->description) ?>
                                <?php endif; ?>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </body>
        </html>
        <?php
        return ob_get_clean();
    }

    /**
     * Generate Nathan template HTML for PDF
     */
    private function generateNathanHtml(Cv $cv): string
    {
        // Similar to Esey but with Nathan styling
        return $this->generateEseyHtml($cv); // For now, use same as Esey
    }

    /**
     * Generate Mirian template HTML for PDF
     */
    private function generateMirianHtml(Cv $cv): string
    {
        // Similar to Esey but with Mirian styling
        return $this->generateEseyHtml($cv); // For now, use same as Esey
    }

    /**
     * Generate HTML fallback content
     */
    private function generateHtmlFallbackContent(Cv $cv, string $reason): string
    {
        $htmlContent = $this->generateHtmlContent($cv);
        
        $fallbackHtml = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>CV Export - HTML Version</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .notice { background: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; margin: 20px 0; border-radius: 5px; }
        .instructions { background: #d1ecf1; border: 1px solid #bee5eb; padding: 15px; margin: 20px 0; border-radius: 5px; }
        .success { background: #d4edda; border: 1px solid #c3e6cb; padding: 15px; margin: 20px 0; border-radius: 5px; }
        .print-button { 
            background: #007bff; 
            color: white; 
            padding: 10px 20px; 
            border: none; 
            border-radius: 5px; 
            cursor: pointer; 
            font-size: 16px;
            margin: 10px 5px;
        }
        .print-button:hover { background: #0056b3; }
        @media print {
            .notice, .instructions, .success, .print-button { display: none; }
        }
    </style>
    <script>
        function printCV() {
            window.print();
        }
        function downloadAsPDF() {
            alert("To save as PDF:\\n1. Press Ctrl+P (Windows) or Cmd+P (Mac)\\n2. Select \\"Save as PDF\\" as destination\\n3. Click \\"Save\\"");
        }
    </script>
</head>
<body>
    <div class="success">
        <h3>✅ CV Ready for Export</h3>
        <p>Your CV has been generated successfully! Use the buttons below to print or save as PDF.</p>
    </div>
    
    <div class="instructions">
        <h3>🖨️ Export Options:</h3>
        <button class="print-button" onclick="printCV()">🖨️ Print CV</button>
        <button class="print-button" onclick="downloadAsPDF()">📄 Save as PDF</button>
        <p><strong>Note:</strong> For local development, PDF generation requires wkhtmltopdf. On the live server, PDFs will be generated automatically.</p>
    </div>
    
    <div class="notice">
        <h3>ℹ️ Development Mode</h3>
        <p>Reason: ' . htmlspecialchars($reason) . '</p>
        <p><strong>On the live server:</strong> PDFs will be generated automatically using wkhtmltopdf.</p>
    </div>
    
    <hr>';
        
        return $fallbackHtml . $htmlContent . '</body></html>';
    }
}
