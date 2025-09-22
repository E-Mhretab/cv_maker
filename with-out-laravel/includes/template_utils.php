<?php
/**
 * Template Utilities
 * Helper functions for template type conversion
 */

/**
 * Convert template ID to template name
 */
function getTemplateName($templateId) {
    $templateMap = [
        1 => 'nathan',
        2 => 'esey',
        3 => 'mirian'
    ];
    
    return $templateMap[$templateId] ?? 'nathan';
}

/**
 * Convert template name to template ID
 */
function getTemplateId($templateName) {
    $templateMap = [
        'nathan' => 1,
        'esey' => 2,
        'mirian' => 3
    ];
    
    return $templateMap[$templateName] ?? 1;
}

/**
 * Get all available templates
 */
function getAvailableTemplates() {
    return [
        1 => 'nathan',
        2 => 'esey',
        3 => 'mirian'
    ];
}

/**
 * Get template display name
 */
function getTemplateDisplayName($templateId) {
    $templateMap = [
        1 => 'Nathan Template',
        2 => 'Esey Template',
        3 => 'Mirian Template'
    ];
    
    return $templateMap[$templateId] ?? 'Nathan Template';
}
?>
