<?php
/**
 * Validation Utilities
 * 
 * This file contains validation functions for form data.
 */

/**
 * Validate phone number format
 * 
 * @param string $phoneNumber The phone number to validate
 * @return array ['valid' => bool, 'message' => string]
 */
function validatePhoneNumber($phoneNumber) {
    // Allow empty phone numbers
    if (empty(trim($phoneNumber))) {
        return ['valid' => true, 'message' => ''];
    }
    
    // Remove any whitespace
    $phoneNumber = trim($phoneNumber);
    
    // Check format: optional + at start, followed by 7-15 digits
    $pattern = '/^\+?[0-9]{7,15}$/';
    
    if (!preg_match($pattern, $phoneNumber)) {
        return [
            'valid' => false, 
            'message' => 'Please enter a valid phone number. Format: +1234567890 or 1234567890 (7-15 digits)'
        ];
    }
    
    return ['valid' => true, 'message' => ''];
}

/**
 * Validate date range (end date must be >= start date)
 * 
 * @param string $startDate The start date (Y-m-d format)
 * @param string $endDate The end date (Y-m-d format) - can be empty for current
 * @param string $fieldName The name of the field for error messages
 * @return array ['valid' => bool, 'message' => string]
 */
function validateDateRange($startDate, $endDate, $fieldName = 'dates') {
    // Allow empty dates
    if (empty(trim($startDate))) {
        return ['valid' => true, 'message' => ''];
    }
    
    // If end date is empty, it's valid (current position/education)
    if (empty(trim($endDate))) {
        return ['valid' => true, 'message' => ''];
    }
    
    // Validate date format
    $startTimestamp = strtotime($startDate);
    $endTimestamp = strtotime($endDate);
    
    if ($startTimestamp === false) {
        return [
            'valid' => false,
            'message' => "Invalid start date format for $fieldName"
        ];
    }
    
    if ($endTimestamp === false) {
        return [
            'valid' => false,
            'message' => "Invalid end date format for $fieldName"
        ];
    }
    
    // Check if end date is >= start date
    if ($endTimestamp < $startTimestamp) {
        return [
            'valid' => false,
            'message' => "End date must be on or after the start date for $fieldName"
        ];
    }
    
    return ['valid' => true, 'message' => ''];
}

/**
 * Validate work experience dates
 * 
 * @param array $workData Array of work experience data
 * @return array ['valid' => bool, 'errors' => array]
 */
function validateWorkExperienceDates($workData) {
    $errors = [];
    
    if (isset($workData['work_start']) && is_array($workData['work_start'])) {
        for ($i = 0; $i < count($workData['work_start']); $i++) {
            $startDate = $workData['work_start'][$i] ?? '';
            $endDate = $workData['work_end'][$i] ?? '';
            
            // Skip validation if both dates are empty
            if (empty(trim($startDate)) && empty(trim($endDate))) {
                continue;
            }
            
            $validation = validateDateRange($startDate, $endDate, "work experience #" . ($i + 1));
            if (!$validation['valid']) {
                $errors[] = $validation['message'];
            }
        }
    }
    
    return [
        'valid' => empty($errors),
        'errors' => $errors
    ];
}

/**
 * Validate education dates
 * 
 * @param array $educationData Array of education data
 * @return array ['valid' => bool, 'errors' => array]
 */
function validateEducationDates($educationData) {
    $errors = [];
    
    if (isset($educationData['education_start']) && is_array($educationData['education_start'])) {
        for ($i = 0; $i < count($educationData['education_start']); $i++) {
            $startDate = $educationData['education_start'][$i] ?? '';
            $endDate = $educationData['education_end'][$i] ?? '';
            
            // Skip validation if both dates are empty
            if (empty(trim($startDate)) && empty(trim($endDate))) {
                continue;
            }
            
            $validation = validateDateRange($startDate, $endDate, "education #" . ($i + 1));
            if (!$validation['valid']) {
                $errors[] = $validation['message'];
            }
        }
    }
    
    return [
        'valid' => empty($errors),
        'errors' => $errors
    ];
}

/**
 * Validate email format
 * 
 * @param string $email The email to validate
 * @return array ['valid' => bool, 'message' => string]
 */
function validateEmail($email) {
    if (empty(trim($email))) {
        return [
            'valid' => false,
            'message' => 'Email address is required'
        ];
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return [
            'valid' => false,
            'message' => 'Please enter a valid email address'
        ];
    }
    
    return ['valid' => true, 'message' => ''];
}

/**
 * Validate required field
 * 
 * @param string $value The value to validate
 * @param string $fieldName The name of the field for error messages
 * @return array ['valid' => bool, 'message' => string]
 */
function validateRequired($value, $fieldName) {
    if (empty(trim($value))) {
        return [
            'valid' => false,
            'message' => ucfirst($fieldName) . ' is required'
        ];
    }
    
    return ['valid' => true, 'message' => ''];
}

/**
 * Sanitize phone number (remove non-digit characters except +)
 * 
 * @param string $phoneNumber The phone number to sanitize
 * @return string The sanitized phone number
 */
function sanitizePhoneNumber($phoneNumber) {
    // Remove all characters except digits and +
    $sanitized = preg_replace('/[^0-9+]/', '', $phoneNumber);
    
    // Ensure + is only at the beginning
    if (strpos($sanitized, '+') !== false && strpos($sanitized, '+') !== 0) {
        $sanitized = '+' . preg_replace('/[^0-9]/', '', $sanitized);
    }
    
    return $sanitized;
}

/**
 * Format phone number for display
 * 
 * @param string $phoneNumber The phone number to format
 * @return string The formatted phone number
 */
function formatPhoneNumber($phoneNumber) {
    if (empty($phoneNumber)) {
        return '';
    }
    
    // Remove all non-digit characters
    $digits = preg_replace('/[^0-9]/', '', $phoneNumber);
    
    // Format based on length
    if (strlen($digits) == 10) {
        // US format: (123) 456-7890
        return '(' . substr($digits, 0, 3) . ') ' . substr($digits, 3, 3) . '-' . substr($digits, 6);
    } elseif (strlen($digits) == 11 && substr($digits, 0, 1) == '1') {
        // US format with country code: +1 (123) 456-7890
        return '+1 (' . substr($digits, 1, 3) . ') ' . substr($digits, 4, 3) . '-' . substr($digits, 7);
    } else {
        // International format: +1234567890
        return '+' . $digits;
    }
}
?>
