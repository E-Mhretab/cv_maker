<?php
// Start session first, before any output
session_start();

require_once '../connection.php';
require_once '../includes/auth.php';
require_once '../includes/middleware.php';
require_once '../templates/template_loader.php';

// Require export permission (not guest)
requireExportPermission();

// Get CV ID and format from URL
$cvId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$format = isset($_GET['format']) ? strtolower($_GET['format']) : '';

if (!$cvId || !in_array($format, ['pdf', 'xml'])) {
    die('Invalid parameters');
}

// Check if user can access this CV
requireCVAccess($cvId);

// Get CV data from database
$cvQuery = "SELECT * FROM cv WHERE id = ? LIMIT 1";
$cvStmt = $conn->prepare($cvQuery);
$cvStmt->bind_param("i", $cvId);
$cvStmt->execute();
$cvResult = $cvStmt->get_result();

if ($cvResult->num_rows === 0) {
    die('CV not found');
}

$cv = $cvResult->fetch_assoc();
$cvStmt->close();

// Get related data
$workQuery = "SELECT * FROM work_experience WHERE cv_id = ? ORDER BY work_start DESC";
$workStmt = $conn->prepare($workQuery);
$workStmt->bind_param("i", $cvId);
$workStmt->execute();
$workResult = $workStmt->get_result();

$educationQuery = "SELECT * FROM education WHERE cv_id = ? ORDER BY education_start DESC";
$educationStmt = $conn->prepare($educationQuery);
$educationStmt->bind_param("i", $cvId);
$educationStmt->execute();
$educationResult = $educationStmt->get_result();

$hobbiesQuery = "SELECT * FROM hobbies WHERE cv_id = ? ORDER BY id";
$hobbiesStmt = $conn->prepare($hobbiesQuery);
$hobbiesStmt->bind_param("i", $cvId);
$hobbiesStmt->execute();
$hobbiesResult = $hobbiesStmt->get_result();

if ($format === 'pdf') {
    exportToPDF($cv, $workResult, $educationResult, $hobbiesResult);
} elseif ($format === 'xml') {
    exportToXML($cv, $workResult, $educationResult, $hobbiesResult);
}

function exportToPDF($cv, $workResult, $educationResult, $hobbiesResult) {
    // Disable error reporting to prevent warnings in HTML output
    error_reporting(0);
    
    // Create a temporary HTML file for wkhtmltopdf
    $tempHtmlFile = tempnam(sys_get_temp_dir(), 'cv_export_') . '.html';
    
    // Generate HTML content using PDF-optimized template
    $html = generateCVHTMLForPDF($cv, $workResult, $educationResult, $hobbiesResult);
    
    // Write HTML to temporary file
    file_put_contents($tempHtmlFile, $html);
    
    // Generate PDF filename
    $filename = 'CV_' . preg_replace('/[^a-zA-Z0-9_-]/', '_', $cv['name']) . '_' . date('Y-m-d') . '.pdf';
    
    // Check if exec functions are available
    if (!function_exists('exec') && !function_exists('shell_exec') && !function_exists('system')) {
        // Exec functions not available - send HTML file with instructions
        header('Content-Type: text/html');
        header('Content-Disposition: attachment; filename="' . str_replace('.pdf', '.html', $filename) . '"');
        echo '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>CV Export - HTML Versie</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .notice { background: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; margin: 20px 0; border-radius: 5px; }
        .instructions { background: #d1ecf1; border: 1px solid #bee5eb; padding: 15px; margin: 20px 0; border-radius: 5px; }
        @media print {
            .notice, .instructions { display: none; }
        }
    </style>
</head>
<body>
    <div class="notice">
        <h3>📄 HTML Versie van CV</h3>
        <p>De PDF export is momenteel niet beschikbaar omdat de benodigde server functies nog niet zijn aangezet.</p>
    </div>
    
    <div class="instructions">
        <h3>🖨️ Om PDF te maken:</h3>
        <ol>
            <li>Druk op <strong>Ctrl+P</strong> (Windows) of <strong>Cmd+P</strong> (Mac)</li>
            <li>Selecteer "Opslaan als PDF" als bestemming</li>
            <li>Klik op "Opslaan"</li>
        </ol>
    </div>
    
    <hr>';
        readfile($tempHtmlFile);
        echo '</body></html>';
        unlink($tempHtmlFile);
        exit;
    }
    
    // Check for wkhtmltopdf installation
    $wkhtmltopdfPath = '';
    $possiblePaths = [
        '/usr/local/bin/wkhtmltopdf',
        '/usr/bin/wkhtmltopdf',
        '/opt/wkhtmltopdf/bin/wkhtmltopdf'
    ];
    
    foreach ($possiblePaths as $path) {
        if (file_exists($path) && is_executable($path)) {
            $wkhtmltopdfPath = $path;
            break;
        }
    }
    
    if (!$wkhtmltopdfPath) {
        // Fallback: send HTML file with instructions
        header('Content-Type: text/html');
        header('Content-Disposition: attachment; filename="' . str_replace('.pdf', '.html', $filename) . '"');
        echo '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>CV Export - HTML Versie</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .notice { background: #f8d7da; border: 1px solid #f5c6cb; padding: 15px; margin: 20px 0; border-radius: 5px; }
        .instructions { background: #d1ecf1; border: 1px solid #bee5eb; padding: 15px; margin: 20px 0; border-radius: 5px; }
        @media print {
            .notice, .instructions { display: none; }
        }
    </style>
</head>
<body>
    <div class="notice">
        <h3>⚠️ PDF Export Niet Beschikbaar</h3>
        <p>wkhtmltopdf is niet gevonden op de server. De HTML versie wordt gedownload.</p>
    </div>
    
    <div class="instructions">
        <h3>🖨️ Om PDF te maken:</h3>
        <ol>
            <li>Druk op <strong>Ctrl+P</strong> (Windows) of <strong>Cmd+P</strong> (Mac)</li>
            <li>Selecteer "Opslaan als PDF" als bestemming</li>
            <li>Klik op "Opslaan"</li>
        </ol>
    </div>
    
    <hr>';
        readfile($tempHtmlFile);
        echo '</body></html>';
        unlink($tempHtmlFile);
        exit;
    }
    
    // Generate PDF using wkhtmltopdf
    $tempPdfFile = tempnam(sys_get_temp_dir(), 'cv_export_') . '.pdf';
    
    // Build command with optimized options for better PDF output
    $command = escapeshellcmd($wkhtmltopdfPath) . 
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
               ' ' . escapeshellarg($tempHtmlFile) . 
               ' ' . escapeshellarg($tempPdfFile) . 
               ' 2>&1';
    
    // Try different execution methods
    $output = [];
    $returnCode = 0;
    $success = false;
    
    if (function_exists('exec')) {
        exec($command, $output, $returnCode);
        $success = ($returnCode === 0 && file_exists($tempPdfFile) && filesize($tempPdfFile) > 0);
    } elseif (function_exists('shell_exec')) {
        $result = shell_exec($command);
        $success = (file_exists($tempPdfFile) && filesize($tempPdfFile) > 0);
    } elseif (function_exists('system')) {
        ob_start();
        system($command, $returnCode);
        ob_end_clean();
        $success = ($returnCode === 0 && file_exists($tempPdfFile) && filesize($tempPdfFile) > 0);
    }
    
    if ($success) {
        // Send PDF to browser
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . filesize($tempPdfFile));
        readfile($tempPdfFile);
        
        // Clean up
        unlink($tempPdfFile);
    } else {
        // Log error for debugging (if possible)
        $errorMsg = "PDF generation failed. Command: " . $command . "\nOutput: " . implode("\n", $output) . "\nReturn code: " . $returnCode;
        error_log($errorMsg);
        
        // Fallback: send HTML file with error notice
        header('Content-Type: text/html');
        header('Content-Disposition: attachment; filename="' . str_replace('.pdf', '.html', $filename) . '"');
        echo '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>CV Export - HTML Versie</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .error { background: #f8d7da; border: 1px solid #f5c6cb; padding: 15px; margin: 20px 0; border-radius: 5px; }
        .instructions { background: #d1ecf1; border: 1px solid #bee5eb; padding: 15px; margin: 20px 0; border-radius: 5px; }
        @media print {
            .error, .instructions { display: none; }
        }
    </style>
</head>
<body>
    <div class="error">
        <h3>❌ PDF Generatie Mislukt</h3>
        <p>Er is een fout opgetreden tijdens het genereren van de PDF. De HTML versie wordt gedownload.</p>
    </div>
    
    <div class="instructions">
        <h3>🖨️ Om PDF te maken:</h3>
        <ol>
            <li>Druk op <strong>Ctrl+P</strong> (Windows) of <strong>Cmd+P</strong> (Mac)</li>
            <li>Selecteer "Opslaan als PDF" als bestemming</li>
            <li>Klik op "Opslaan"</li>
        </ol>
    </div>
    
    <hr>';
        readfile($tempHtmlFile);
        echo '</body></html>';
    }
    
    unlink($tempHtmlFile);
}

function exportToXML($cv, $workResult, $educationResult, $hobbiesResult) {
    // Disable error reporting to prevent warnings in XML output
    error_reporting(0);
    
    $filename = 'CV_' . preg_replace('/[^a-zA-Z0-9_-]/', '_', $cv['name']) . '_' . date('Y-m-d') . '.xml';
    
    header('Content-Type: application/xml');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    
    echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    echo '<cv>' . "\n";
    echo '  <personal_info>' . "\n";
    echo '    <name>' . htmlspecialchars($cv['name'] ?? '') . '</name>' . "\n";
    echo '    <address>' . htmlspecialchars($cv['address'] ?? '') . '</address>' . "\n";
    echo '    <phone_number>' . htmlspecialchars($cv['phone_number'] ?? '') . '</phone_number>' . "\n";
    echo '    <email>' . htmlspecialchars($cv['email'] ?? '') . '</email>' . "\n";
    if (!empty($cv['date_of_birth'])) {
        echo '    <date_of_birth>' . htmlspecialchars($cv['date_of_birth']) . '</date_of_birth>' . "\n";
    }
    if (!empty($cv['portfolio'])) {
        echo '    <portfolio>' . htmlspecialchars($cv['portfolio']) . '</portfolio>' . "\n";
    }
    if (!empty($cv['linkedin_profile'])) {
        echo '    <linkedin_profile>' . htmlspecialchars($cv['linkedin_profile']) . '</linkedin_profile>' . "\n";
    }
    echo '    <template_type>' . htmlspecialchars($cv['template_type'] ?? '') . '</template_type>' . "\n";
    echo '    <created_at>' . htmlspecialchars($cv['created_at'] ?? '') . '</created_at>' . "\n";
    echo '  </personal_info>' . "\n";
    
    if (!empty($cv['profile_summary'])) {
        echo '  <profile_summary>' . "\n";
        echo '    <![CDATA[' . $cv['profile_summary'] . ']]>' . "\n";
        echo '  </profile_summary>' . "\n";
    }
    
    if ($workResult->num_rows > 0) {
        echo '  <work_experience>' . "\n";
        while ($work = $workResult->fetch_assoc()) {
            echo '    <job>' . "\n";
            echo '      <job_title>' . htmlspecialchars($work['job_title'] ?? '') . '</job_title>' . "\n";
            echo '      <company>' . htmlspecialchars($work['company'] ?? '') . '</company>' . "\n";
            echo '      <work_start>' . htmlspecialchars($work['work_start'] ?? '') . '</work_start>' . "\n";
            echo '      <work_end>' . htmlspecialchars($work['work_end'] ?? '') . '</work_end>' . "\n";
            if (!empty($work['description'])) {
                echo '      <description><![CDATA[' . $work['description'] . ']]></description>' . "\n";
            }
            echo '    </job>' . "\n";
        }
        echo '  </work_experience>' . "\n";
    }
    
    if ($educationResult->num_rows > 0) {
        echo '  <education>' . "\n";
        while ($education = $educationResult->fetch_assoc()) {
            echo '    <degree>' . "\n";
            echo '      <degree_name>' . htmlspecialchars($education['degree'] ?? '') . '</degree_name>' . "\n";
            echo '      <institution>' . htmlspecialchars($education['institution'] ?? '') . '</institution>' . "\n";
            echo '      <education_start>' . htmlspecialchars($education['education_start'] ?? '') . '</education_start>' . "\n";
            echo '      <education_end>' . htmlspecialchars($education['education_end'] ?? '') . '</education_end>' . "\n";
            if (!empty($education['description'])) {
                echo '      <description><![CDATA[' . $education['description'] . ']]></description>' . "\n";
            }
            echo '    </degree>' . "\n";
        }
        echo '  </education>' . "\n";
    }
    
    if ($hobbiesResult->num_rows > 0) {
        echo '  <hobbies>' . "\n";
        while ($hobby = $hobbiesResult->fetch_assoc()) {
            echo '    <hobby>' . "\n";
            echo '      <hobby_name>' . htmlspecialchars($hobby['hobby_name'] ?? '') . '</hobby_name>' . "\n";
            if (!empty($hobby['description'])) {
                echo '      <description><![CDATA[' . $hobby['description'] . ']]></description>' . "\n";
            }
            echo '    </hobby>' . "\n";
        }
        echo '  </hobbies>' . "\n";
    }
    
    echo '</cv>' . "\n";
}

function generateCVHTMLForPDF($cv, $workResult, $educationResult, $hobbiesResult) {
    // Use PDF-optimized template based on CV template
    $pdfTemplate = '../templates/template_' . $cv['template'] . '_pdf.php';
    $defaultPdfTemplate = '../templates/template_esey_pdf.php';
    
    if (file_exists($pdfTemplate)) {
        ob_start();
        include $pdfTemplate;
        return ob_get_clean();
    } elseif (file_exists($defaultPdfTemplate)) {
        ob_start();
        include $defaultPdfTemplate;
        return ob_get_clean();
    } else {
        // Fallback to regular template
        if (file_exists('../templates/template_' . $cv['template'] . '.php')) {
            ob_start();
            include '../templates/template_' . $cv['template'] . '.php';
            return ob_get_clean();
        } else {
            ob_start();
            include '../templates/template_esey.php';
            return ob_get_clean();
        }
    }
}

// Close database connections
$workStmt->close();
$educationStmt->close();
$hobbiesStmt->close();
$conn->close();
?>
