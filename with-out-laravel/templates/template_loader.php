<?php
// Template Loader - Dynamically loads the appropriate template based on template_type

// Include template utilities for template name conversion
require_once __DIR__ . '/../includes/template_utils.php';

function loadCVTemplate($templateType, $cv, $workResult, $educationResult, $hobbiesResult, $skillsResult = null, $languagesResult = null) {
    // Convert template type to name if it's numeric
    if (is_numeric($templateType)) {
        $templateType = getTemplateName($templateType);
    }
    
    // Validate template type
    $allowedTemplates = ['nathan', 'esey', 'mirian'];
    if (!in_array($templateType, $allowedTemplates)) {
        $templateType = 'nathan'; // Default fallback
    }
    
    // Set template path - use absolute path from project root
    $templatePath = __DIR__ . "/template_{$templateType}.php";
    
    // Check if template file exists
    if (!file_exists($templatePath)) {
        // Fallback to nathan template if specific template doesn't exist
        $templatePath = __DIR__ . "/template_nathan.php";
        if (!file_exists($templatePath)) {
            die("Template file not found: {$templatePath}");
        }
    }
    
    // Determine the correct path to index.php based on current location
    $currentDir = dirname($_SERVER['PHP_SELF']);
    $indexPath = '';
    
    if (strpos($currentDir, '/create') !== false) {
        // We're in the create directory
        $indexPath = '../index.php';
    } elseif (strpos($currentDir, '/manage') !== false) {
        // We're in the manage directory
        $indexPath = '../index.php';
    } else {
        // We're in the root directory
        $indexPath = 'index.php';
    }
    
    // Set the index path as a variable for the template
    $GLOBALS['index_path'] = $indexPath;
    
    // Make skills and languages results available to templates
    $GLOBALS['skillsResult'] = $skillsResult;
    $GLOBALS['languagesResult'] = $languagesResult;
    
    // Include the template
    include $templatePath;
}

// Helper function to get template type from CV data
function getTemplateType($cv) {
    if (isset($cv['template_type'])) {
        // If it's numeric, convert to name
        if (is_numeric($cv['template_type'])) {
            return getTemplateName($cv['template_type']);
        }
        return $cv['template_type'];
    }
    return 'nathan';
}

// Helper function to render CV with template
function renderCV($cv, $workResult, $educationResult, $hobbiesResult, $skillsResult = null, $languagesResult = null) {
    $templateType = getTemplateType($cv);
    loadCVTemplate($templateType, $cv, $workResult, $educationResult, $hobbiesResult, $skillsResult, $languagesResult);
}

// Helper function to render CV with user context
function renderCVWithContext($cv, $workResult, $educationResult, $hobbiesResult, $userContext, $auth, $skillsResult = null, $languagesResult = null) {
    // Make user context and auth available to templates
    $GLOBALS['userContext'] = $userContext;
    $GLOBALS['auth'] = $auth;
    
    $templateType = getTemplateType($cv);
    loadCVTemplate($templateType, $cv, $workResult, $educationResult, $hobbiesResult, $skillsResult, $languagesResult);
}
?>
