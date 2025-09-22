<?php
// Start session first, before any output
session_start();

require_once '../connection.php';
require_once '../includes/auth.php';
require_once '../includes/middleware.php';
require_once '../includes/template_utils.php';
require_once '../templates/template_loader.php';

// Use global auth instance and initialize middleware
$auth = $GLOBALS['auth'] ?? new Auth($conn);
$middleware = new Middleware($auth);

// Get CV ID from URL
$cvId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$cvId) {
    header('Location: cv_list.php');
    exit;
}

// Check if user can access this CV (public CVs are accessible to everyone)
requireCVAccess($cvId);

// Get user context
$userContext = $middleware->getUserContext();

// Get CV data from database with metadata
$cvQuery = "SELECT c.*, m.template_type, m.is_public, m.published_at, m.created_at 
            FROM cv c 
            LEFT JOIN cv_metadata m ON c.id = m.cv_id 
            WHERE c.id = ? LIMIT 1";
$cvStmt = $conn->prepare($cvQuery);
$cvStmt->bind_param("i", $cvId);
$cvStmt->execute();
$cvResult = $cvStmt->get_result();

if ($cvResult->num_rows === 0) {
    die('<div class="container py-5"><div class="alert alert-danger">CV not found</div></div>');
}

$cv = $cvResult->fetch_assoc();
$cvStmt->close();

// Get work experience
$workQuery = "SELECT * FROM work_experience WHERE cv_id = ? ORDER BY work_start DESC";
$workStmt = $conn->prepare($workQuery);
$workStmt->bind_param("i", $cvId);
$workStmt->execute();
$workResult = $workStmt->get_result();

// Get education
$educationQuery = "SELECT * FROM education WHERE cv_id = ? ORDER BY education_start DESC";
$educationStmt = $conn->prepare($educationQuery);
$educationStmt->bind_param("i", $cvId);
$educationStmt->execute();
$educationResult = $educationStmt->get_result();

// Get skills
$skillsQuery = "SELECT * FROM skills WHERE cv_id = ? ORDER BY id";
$skillsStmt = $conn->prepare($skillsQuery);
$skillsStmt->bind_param("i", $cvId);
$skillsStmt->execute();
$skillsResult = $skillsStmt->get_result();

// Get languages
$languagesQuery = "SELECT * FROM languages WHERE cv_id = ? ORDER BY id";
$languagesStmt = $conn->prepare($languagesQuery);
$languagesStmt->bind_param("i", $cvId);
$languagesStmt->execute();
$languagesResult = $languagesStmt->get_result();

// Get hobbies
$hobbiesQuery = "SELECT * FROM hobbies WHERE cv_id = ? ORDER BY id";
$hobbiesStmt = $conn->prepare($hobbiesQuery);
$hobbiesStmt->bind_param("i", $cvId);
$hobbiesStmt->execute();
$hobbiesResult = $hobbiesStmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CV Preview - <?php echo htmlspecialchars($cv['name']); ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Fixed Header Styling */
        .preview-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2.5rem 0 1.5rem 0;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 9999;
            box-shadow: 0 2px 20px rgba(0,0,0,0.15);
            backdrop-filter: blur(10px);
            width: 100%;
        }
        
        /* Action Buttons in Header */
        .preview-actions {
            position: fixed;
            top: 0.5rem;
            right: 2rem;
            z-index: 10000;
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }
        
        .preview-actions .btn {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            font-weight: 600;
            border-radius: 8px;
            border: none;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .preview-actions .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.3);
        }
        
        /* Button Color Variants */
        .preview-actions .btn-secondary {
            background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
            color: white;
        }
        
        .preview-actions .btn-warning {
            background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
            color: #212529;
        }
        
        .preview-actions .btn-info {
            background: linear-gradient(135deg, #0dcaf0 0%, #0aa2c0 100%);
            color: white;
        }
        
        .preview-actions .btn-danger {
            background: linear-gradient(135deg, #dc3545 0%, #b02a37 100%);
            color: white;
        }
        
        .preview-actions .btn-success {
            background: linear-gradient(135deg, #198754 0%, #146c43 100%);
            color: white;
        }
        
        /* Body padding to account for fixed header */
        body {
            padding-top: 140px;
            position: relative;
            z-index: 1;
        }
        
        /* CV Container */
        .cv-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
            overflow: hidden;
            margin-top: 0;
            margin-bottom: 2rem;
            position: relative;
            z-index: 1;
        }
        
        /* Header Content */
        .header-content { 
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }
        
        .header-title {
            flex: 1;
            min-width: 200px;
        }
        
        .header-title h1 {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .header-title p {
            font-size: 1rem;
            opacity: 0.9;
            margin: 0;
        }
        
        .header-badge {
            background: rgba(255,255,255,0.2);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.3);
            border-radius: 25px;
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            font-weight: 500;
        }
        
        /* Mobile Dropdown Menu */
        .mobile-menu-toggle {
            display: none;
            background: rgba(255,255,255,0.2);
            border: 1px solid rgba(255,255,255,0.3);
            color: white;
            border-radius: 8px;
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            user-select: none;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
        }
        
        .mobile-menu-toggle:hover {
            background: rgba(255,255,255,0.3);
            transform: translateY(-1px);
        }
        
        .mobile-menu-toggle:active {
            transform: translateY(0);
        }
        
        .mobile-dropdown {
            display: none;
            position: fixed;
            top: 80px;
            right: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.3);
            min-width: 200px;
            z-index: 10001;
            overflow: visible;
            border: 2px solid #007bff;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            transform: translateY(-10px);
            padding: 0;
        }
        
        .mobile-dropdown.show {
            display: block !important;
            opacity: 1 !important;
            visibility: visible !important;
            transform: translateY(0) !important;
        }
        
        .mobile-dropdown .btn {
            display: block !important;
            width: 100% !important;
            margin: 0 !important;
            border-radius: 0 !important;
            border: none !important;
            border-bottom: 1px solid #eee !important;
            text-align: left !important;
            padding: 12px 16px !important;
            font-size: 14px !important;
            box-shadow: none !important;
            transform: none !important;
            min-height: 44px !important;
            line-height: 1.2 !important;
            text-decoration: none !important;
        }
        
        /* Back to List - Secondary (Gray) */
        .mobile-dropdown .btn-secondary {
            background: #6c757d !important;
            color: white !important;
        }
        
        .mobile-dropdown .btn-secondary:hover {
            background: #5a6268 !important;
            color: white !important;
        }
        
        /* Edit CV - Warning (Orange) */
        .mobile-dropdown .btn-warning {
            background: #ffc107 !important;
            color: #212529 !important;
        }
        
        .mobile-dropdown .btn-warning:hover {
            background: #e0a800 !important;
            color: #212529 !important;
        }
        
        /* Print - Info (Blue) */
        .mobile-dropdown .btn-info {
            background: #17a2b8 !important;
            color: white !important;
        }
        
        .mobile-dropdown .btn-info:hover {
            background: #138496 !important;
            color: white !important;
        }
        
        /* Export PDF - Danger (Red) */
        .mobile-dropdown .btn-danger {
            background: #dc3545 !important;
            color: white !important;
        }
        
        .mobile-dropdown .btn-danger:hover {
            background: #c82333 !important;
            color: white !important;
        }
        
        /* Export XML - Success (Green) */
        .mobile-dropdown .btn-success {
            background: #28a745 !important;
            color: white !important;
        }
        
        .mobile-dropdown .btn-success:hover {
            background: #218838 !important;
            color: white !important;
        }
        
        .mobile-dropdown .btn:last-child {
            border-bottom: none !important;
        }
        
        .mobile-dropdown .btn i {
            margin-right: 8px !important;
            width: 16px !important;
            display: inline-block !important;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .preview-actions {
                position: fixed;
                top: 0.5rem;
                right: 2rem;
                flex-direction: column;
                gap: 0;
            }
            
            .preview-actions .btn {
                display: none;
            }
            
            .mobile-menu-toggle {
                display: block;
            }
            
            .header-content {
                flex-direction: column;
                text-align: center;
                gap: 1rem;
            }
            
            .header-title h1 {
                font-size: 1.5rem;
            }
            
            body {
                padding-top: 160px;
                padding-bottom: 0;
            }
        }
        
        @media (max-width: 576px) {
            .preview-actions .btn {
                font-size: 0.75rem;
                padding: 0.5rem 0.6rem;
            }
            
            .preview-actions .btn i {
                display: none;
            }
        }
        
        /* Print Styles */
        @media print {
            .preview-actions, .preview-header {
                display: none !important;
            }
            .cv-container {
                box-shadow: none;
                border-radius: 0;
                margin-top: 0;
                margin-bottom: 1rem;
            }
            body {
                padding-top: 0;
                padding-bottom: 0;
            }
        }
        
        /* Smooth scrolling */
        html {
            scroll-behavior: smooth;
        }
        
        /* Loading animation for buttons */
        .preview-actions .btn:active {
            transform: translateY(0);
            transition: transform 0.1s ease;
        }
    </style>
</head>
<body class="bg-light">
    <!-- Preview Actions -->
    <div class="preview-actions">
        <!-- Desktop Buttons -->
        <a href="cv_list.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i>Back to List
        </a>
        <a href="cv_edit.php?id=<?php echo $cvId; ?>" class="btn btn-warning">
            <i class="fas fa-edit me-1"></i>Edit CV
        </a>
        <button onclick="window.print()" class="btn btn-info">
            <i class="fas fa-print me-1"></i>Print
        </button>
            <?php if ($userContext['is_logged_in']): ?>
                <a href="cv_export.php?id=<?php echo $cvId; ?>&format=pdf" class="btn btn-danger" target="_blank">
                    <i class="fas fa-file-pdf me-1"></i>Export PDF
                </a>
                <a href="cv_export_http.php?id=<?php echo $cvId; ?>&format=xml" class="btn btn-success">
                    <i class="fas fa-file-code me-1"></i>Export XML
                </a>
            <?php else: ?>
                <a href="../login.php?redirect=<?php echo urlencode('manage/cv_list.php'); ?>&message=export_required" class="btn btn-danger">
                    <i class="fas fa-file-pdf me-1"></i>Export PDF
                </a>
                <a href="../login.php?redirect=<?php echo urlencode('manage/cv_list.php'); ?>&message=export_required" class="btn btn-success">
                    <i class="fas fa-file-code me-1"></i>Export XML
                </a>
            <?php endif; ?>
        
        <!-- Mobile Dropdown Toggle -->
        <div class="mobile-menu-toggle" id="mobileMenuToggle" onclick="toggleMobileMenu()">
            <i class="fas fa-bars me-1"></i>Actions
        </div>
        
        <!-- Mobile Dropdown Menu -->
        <div class="mobile-dropdown" id="mobileDropdown">
            <a href="cv_list.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to List
            </a>
            <a href="cv_edit.php?id=<?php echo $cvId; ?>" class="btn btn-warning">
                <i class="fas fa-edit me-2"></i>Edit CV
            </a>
            <button onclick="window.print()" class="btn btn-info">
                <i class="fas fa-print me-2"></i>Print
            </button>
            <?php if (!$userContext['is_logged_in']): ?>
                <a href="../login.php?redirect=<?php echo urlencode('manage/cv_list.php'); ?>&message=export_required" class="btn btn-danger">
                    <i class="fas fa-file-pdf me-2"></i>Export PDF
                </a>
                <a href="../login.php?redirect=<?php echo urlencode('manage/cv_list.php'); ?>&message=export_required" class="btn btn-success">
                    <i class="fas fa-file-code me-2"></i>Export XML
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Preview Header -->
    <div class="preview-header">
        <div class="container">
            <div class="header-content">
                <div class="header-title">
                    <h1>
                        <i class="fas fa-eye me-2"></i>CV Preview
                    </h1>
                    <p><?php echo htmlspecialchars($cv['name']); ?> - <?php echo getTemplateDisplayName($cv['template_type']); ?></p>
                </div>
                <div class="header-badge">
                    <div class="d-flex align-items-center gap-3">
                        <?php if (isset($cv['is_public']) && $cv['is_public']): ?>
                            <span class="badge bg-success">
                                <i class="fas fa-globe me-1"></i>Public
                            </span>
                        <?php else: ?>
                            <span class="badge bg-secondary">
                                <i class="fas fa-lock me-1"></i>Private
                            </span>
                        <?php endif; ?>
                        <span>
                            <i class="fas fa-calendar me-1"></i>
                            Created: <?php echo $cv['created_at'] ? date('M j, Y', strtotime($cv['created_at'])) : 'Date not available'; ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CV Preview Container -->
    <div class="container">
        <div class="cv-container">
            <?php
            // Render CV using template system with user context
            renderCVWithContext($cv, $workResult, $educationResult, $hobbiesResult, $userContext, $auth, $skillsResult, $languagesResult);
            ?>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Mobile Menu JavaScript -->
    <script>
        // Global function for mobile menu toggle
        function toggleMobileMenu() {
            console.log('toggleMobileMenu called');
            const dropdown = document.getElementById('mobileDropdown');
            if (dropdown) {
                if (dropdown.classList.contains('show')) {
                    dropdown.classList.remove('show');
                    dropdown.style.display = 'none';
                    console.log('Dropdown hidden');
                } else {
                    dropdown.classList.add('show');
                    dropdown.style.display = 'block';
                    dropdown.style.opacity = '1';
                    dropdown.style.visibility = 'visible';
                    dropdown.style.transform = 'translateY(0)';
                    console.log('Dropdown shown');
                    
                    // Debug: Check if buttons are inside dropdown
                    const buttons = dropdown.querySelectorAll('.btn');
                    console.log('Number of buttons in dropdown:', buttons.length);
                    buttons.forEach((btn, index) => {
                        console.log(`Button ${index + 1}:`, btn.textContent.trim());
                    });
                }
            } else {
                console.error('Dropdown element not found');
            }
        }
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('mobileDropdown');
            const toggle = document.getElementById('mobileMenuToggle');
            
            if (dropdown && toggle && !dropdown.contains(event.target) && !toggle.contains(event.target)) {
                dropdown.classList.remove('show');
                dropdown.style.display = 'none';
                dropdown.style.opacity = '0';
                dropdown.style.visibility = 'hidden';
            }
        });
        
        // Close dropdown on window resize to desktop
        window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
                const dropdown = document.getElementById('mobileDropdown');
                if (dropdown) {
                    dropdown.classList.remove('show');
                    dropdown.style.display = 'none';
                    dropdown.style.opacity = '0';
                    dropdown.style.visibility = 'hidden';
                }
            }
        });
    </script>
</body>
</html>

<?php
// Close database connections
$workStmt->close();
$educationStmt->close();
$skillsStmt->close();
$languagesStmt->close();
$hobbiesStmt->close();
$conn->close();
?>
