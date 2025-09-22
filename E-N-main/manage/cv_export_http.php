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
    exportToPDFHTTP($cv, $workResult, $educationResult, $hobbiesResult);
} elseif ($format === 'xml') {
    exportToXML($cv, $workResult, $educationResult, $hobbiesResult);
}

function exportToPDFHTTP($cv, $workResult, $educationResult, $hobbiesResult) {
    // Disable error reporting to prevent warnings in output
    error_reporting(0);
    
    // Generate HTML content
    $html = generateCVHTML($cv, $workResult, $educationResult, $hobbiesResult);
    
    // Generate PDF filename
    $filename = 'CV_' . preg_replace('/[^a-zA-Z0-9_-]/', '_', $cv['name']) . '_' . date('Y-m-d') . '.pdf';
    
    // Try different methods to use wkhtmltopdf
    
    // Method 1: Try HTTP interface (if available)
    $pdfData = tryWkhtmltopdfHTTP($html);
    
    if ($pdfData) {
        // Success via HTTP
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . strlen($pdfData));
        echo $pdfData;
        return;
    }
    
    // Method 2: Try file-based approach
    $pdfData = tryWkhtmltopdfFile($html);
    
    if ($pdfData) {
        // Success via file
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . strlen($pdfData));
        echo $pdfData;
        return;
    }
    
    // Method 3: Fallback to HTML download
    header('Content-Type: text/html');
    header('Content-Disposition: attachment; filename="' . str_replace('.pdf', '.html', $filename) . '"');
    echo $html;
}

function tryWkhtmltopdfHTTP($html) {
    // Try to use wkhtmltopdf via HTTP interface
    // This method tries to send HTML to a local wkhtmltopdf service
    
    $possibleUrls = [
        'http://localhost:8080/convert',
        'http://127.0.0.1:8080/convert',
        'http://localhost:3000/convert',
        'http://127.0.0.1:3000/convert'
    ];
    
    $data = [
        'html' => $html,
        'options' => [
            'page-size' => 'A4',
            'margin-top' => '0.75in',
            'margin-right' => '0.75in',
            'margin-bottom' => '0.75in',
            'margin-left' => '0.75in',
            'encoding' => 'UTF-8',
            'no-outline' => true,
            'print-media-type' => true
        ]
    ];
    
    foreach ($possibleUrls as $url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode === 200 && $response) {
            return $response;
        }
    }
    
    return false;
}

function tryWkhtmltopdfFile($html) {
    // Try to use wkhtmltopdf via file system
    // This method creates temporary files and tries to process them
    
    $tempDir = sys_get_temp_dir();
    $tempHtml = tempnam($tempDir, 'cv_') . '.html';
    $tempPdf = tempnam($tempDir, 'cv_') . '.pdf';
    
    // Write HTML to temporary file
    if (file_put_contents($tempHtml, $html) === false) {
        return false;
    }
    
    // Try different wkhtmltopdf paths
    $possiblePaths = [
        '/usr/bin/wkhtmltopdf',
        '/usr/local/bin/wkhtmltopdf',
        '/opt/wkhtmltopdf/bin/wkhtmltopdf',
        'wkhtmltopdf'
    ];
    
    foreach ($possiblePaths as $path) {
        if ($path === 'wkhtmltopdf' || file_exists($path)) {
            // Build command
            $command = escapeshellcmd($path) . 
                       ' --page-size A4' .
                       ' --margin-top 0.75in' .
                       ' --margin-right 0.75in' .
                       ' --margin-bottom 0.75in' .
                       ' --margin-left 0.75in' .
                       ' --encoding UTF-8' .
                       ' --no-outline' .
                       ' --enable-local-file-access' .
                       ' --print-media-type' .
                       ' --disable-smart-shrinking' .
                       ' ' . escapeshellarg($tempHtml) . 
                       ' ' . escapeshellarg($tempPdf) . 
                       ' 2>/dev/null';
            
            // Try to execute command
            $output = [];
            $returnCode = 0;
            
            // Use different methods to execute
            $success = false;
            
            if (function_exists('exec')) {
                exec($command, $output, $returnCode);
                $success = ($returnCode === 0 && file_exists($tempPdf) && filesize($tempPdf) > 0);
            } elseif (function_exists('shell_exec')) {
                $result = shell_exec($command);
                $success = (file_exists($tempPdf) && filesize($tempPdf) > 0);
            } elseif (function_exists('system')) {
                ob_start();
                system($command, $returnCode);
                ob_end_clean();
                $success = ($returnCode === 0 && file_exists($tempPdf) && filesize($tempPdf) > 0);
            } elseif (function_exists('passthru')) {
                ob_start();
                passthru($command, $returnCode);
                ob_end_clean();
                $success = ($returnCode === 0 && file_exists($tempPdf) && filesize($tempPdf) > 0);
            }
            
            if ($success) {
                $pdfData = file_get_contents($tempPdf);
                unlink($tempPdf);
                unlink($tempHtml);
                return $pdfData;
            } else {
                // Clean up failed attempt
                if (file_exists($tempPdf)) {
                    unlink($tempPdf);
                }
                if (file_exists($tempHtml)) {
                    unlink($tempHtml);
                }
            }
        }
    }
    
    // Cleanup
    if (file_exists($tempPdf)) unlink($tempPdf);
    if (file_exists($tempHtml)) unlink($tempHtml);
    
    return false;
}

function generateCVHTML($cv, $workResult, $educationResult, $hobbiesResult) {
    ob_start();
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>CV - <?php echo htmlspecialchars($cv['name'] ?? ''); ?></title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <style>
            @media print {
                body { font-size: 12px; }
                .container { max-width: none; }
                .page-break { page-break-before: always; }
            }
            body { 
                font-family: Arial, sans-serif; 
                line-height: 1.4;
                margin: 0;
                padding: 20px;
                background: white;
            }
            .cv-header {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                padding: 30px;
                border-radius: 10px;
                margin-bottom: 30px;
                text-align: center;
            }
            .cv-header h1 {
                font-size: 2.5em;
                margin-bottom: 10px;
                font-weight: bold;
            }
            .cv-header p {
                font-size: 1.1em;
                margin: 5px 0;
            }
            .section {
                margin-bottom: 30px;
                page-break-inside: avoid;
            }
            .section h3 {
                color: #333;
                border-bottom: 3px solid #007bff;
                padding-bottom: 8px;
                margin-bottom: 20px;
                font-size: 1.4em;
                font-weight: bold;
            }
            .work-item, .education-item {
                margin-bottom: 20px;
                padding-bottom: 15px;
                border-bottom: 1px solid #eee;
                page-break-inside: avoid;
            }
            .work-item:last-child, .education-item:last-child {
                border-bottom: none;
            }
            .work-item h5, .education-item h5 {
                color: #007bff;
                font-weight: bold;
                margin-bottom: 5px;
            }
            .text-muted {
                color: #6c757d !important;
                font-style: italic;
            }
            .hobbies ul {
                list-style-type: none;
                padding-left: 0;
            }
            .hobbies li {
                padding: 5px 0;
                border-left: 3px solid #007bff;
                padding-left: 15px;
                margin-bottom: 8px;
            }
            .summary {
                background: #f8f9fa;
                padding: 20px;
                border-radius: 8px;
                border-left: 5px solid #007bff;
            }
        </style>
    </head>
    <body>
        <div class="cv-header">
            <h1><?php echo htmlspecialchars($cv['name'] ?? ''); ?></h1>
            <p>
                <i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($cv['address'] ?? ''); ?>
            </p>
            <p>
                <i class="fas fa-phone"></i> <?php echo htmlspecialchars($cv['phone_number'] ?? ''); ?> |
                <i class="fas fa-envelope"></i> <?php echo htmlspecialchars($cv['email'] ?? ''); ?>
            </p>
            <?php if (!empty($cv['date_of_birth'])): ?>
                <p><i class="fas fa-birthday-cake"></i> <?php echo htmlspecialchars($cv['date_of_birth']); ?></p>
            <?php endif; ?>
            <?php if (!empty($cv['portfolio'])): ?>
                <p><i class="fas fa-globe"></i> <a href="<?php echo htmlspecialchars($cv['portfolio']); ?>" style="color: #ffc107;"><?php echo htmlspecialchars($cv['portfolio']); ?></a></p>
            <?php endif; ?>
            <?php if (!empty($cv['linkedin_profile'])): ?>
                <p><i class="fab fa-linkedin"></i> <a href="<?php echo htmlspecialchars($cv['linkedin_profile']); ?>" style="color: #ffc107;">LinkedIn Profile</a></p>
            <?php endif; ?>
        </div>

        <?php if (!empty($cv['profile_summary'])): ?>
        <div class="section summary">
            <h3><i class="fas fa-user"></i> Professional Summary</h3>
            <p><?php echo nl2br(htmlspecialchars($cv['profile_summary'])); ?></p>
        </div>
        <?php endif; ?>

        <?php if ($workResult->num_rows > 0): ?>
        <div class="section">
            <h3><i class="fas fa-briefcase"></i> Work Experience</h3>
            <?php while ($work = $workResult->fetch_assoc()): ?>
            <div class="work-item">
                <h5><?php echo htmlspecialchars($work['job_title'] ?? ''); ?> - <?php echo htmlspecialchars($work['company'] ?? ''); ?></h5>
                <p class="text-muted">
                    <i class="fas fa-calendar"></i> 
                    <?php echo !empty($work['work_start']) ? date('M Y', strtotime($work['work_start'])) : ''; ?> - 
                    <?php echo !empty($work['work_end']) ? date('M Y', strtotime($work['work_end'])) : 'Present'; ?>
                </p>
                <?php if (!empty($work['description'])): ?>
                    <p><?php echo nl2br(htmlspecialchars($work['description'])); ?></p>
                <?php endif; ?>
            </div>
            <?php endwhile; ?>
        </div>
        <?php endif; ?>

        <?php if ($educationResult->num_rows > 0): ?>
        <div class="section">
            <h3><i class="fas fa-graduation-cap"></i> Education</h3>
            <?php while ($education = $educationResult->fetch_assoc()): ?>
            <div class="education-item">
                <h5><?php echo htmlspecialchars($education['degree'] ?? ''); ?> - <?php echo htmlspecialchars($education['institution'] ?? ''); ?></h5>
                <p class="text-muted">
                    <i class="fas fa-calendar"></i> 
                    <?php echo !empty($education['education_start']) ? date('M Y', strtotime($education['education_start'])) : ''; ?> - 
                    <?php echo !empty($education['education_end']) ? date('M Y', strtotime($education['education_end'])) : 'Present'; ?>
                </p>
                <?php if (!empty($education['description'])): ?>
                    <p><?php echo nl2br(htmlspecialchars($education['description'])); ?></p>
                <?php endif; ?>
            </div>
            <?php endwhile; ?>
        </div>
        <?php endif; ?>

        <?php if ($hobbiesResult->num_rows > 0): ?>
        <div class="section hobbies">
            <h3><i class="fas fa-heart"></i> Hobbies & Interests</h3>
            <ul>
                <?php while ($hobby = $hobbiesResult->fetch_assoc()): ?>
                <li>
                    <strong><?php echo htmlspecialchars($hobby['hobby_name'] ?? ''); ?></strong>
                    <?php if (!empty($hobby['description'])): ?>
                        - <?php echo htmlspecialchars($hobby['description']); ?>
                    <?php endif; ?>
                </li>
                <?php endwhile; ?>
            </ul>
        </div>
        <?php endif; ?>
    </body>
    </html>
    <?php
    return ob_get_clean();
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

// Close database connections
$workStmt->close();
$educationStmt->close();
$hobbiesStmt->close();
$conn->close();
?>
