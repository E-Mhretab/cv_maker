<?php
/**
 * Enhanced Security System
 * Phase 7: Testing & Optimization
 * 
 * Protects against:
 * - XSS (Cross-Site Scripting)
 * - SQL Injection
 * - CSRF (Cross-Site Request Forgery)
 * - File Upload Attacks
 * - Session Hijacking
 */

// Use the centralized session helper
require_once __DIR__ . '/session_helper.php';

// CSRF Protection
class CSRFProtection {
    private static $tokenName = 'csrf_token';
    
    public static function generateToken() {
        if (!isset($_SESSION[self::$tokenName])) {
            $_SESSION[self::$tokenName] = bin2hex(random_bytes(32));
        }
        return $_SESSION[self::$tokenName];
    }
    
    public static function validateToken($token) {
        return isset($_SESSION[self::$tokenName]) && 
               hash_equals($_SESSION[self::$tokenName], $token);
    }
    
    public static function getTokenField() {
        $token = self::generateToken();
        return '<input type="hidden" name="' . self::$tokenName . '" value="' . htmlspecialchars($token) . '">';
    }
}

// XSS Protection
class XSSProtection {
    
    public static function sanitizeInput($input) {
        if (is_array($input)) {
            return array_map([self::class, 'sanitizeInput'], $input);
        }
        
        // Remove null bytes
        $input = str_replace(chr(0), '', $input);
        
        // Convert to string if not already
        $input = (string) $input;
        
        // Remove any HTML tags
        $input = strip_tags($input);
        
        // Convert special characters to HTML entities
        $input = htmlspecialchars($input, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        
        return $input;
    }
    
    public static function sanitizeOutput($output) {
        return htmlspecialchars($output, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }
    
    public static function allowSafeHTML($input, $allowedTags = '<p><br><strong><em><ul><ol><li>') {
        return strip_tags($input, $allowedTags);
    }
}

// SQL Injection Protection
class SQLProtection {
    
    public static function sanitizeForSQL($input) {
        if (is_array($input)) {
            return array_map([self::class, 'sanitizeForSQL'], $input);
        }
        
        // Remove SQL injection patterns
        $patterns = [
            '/(\b(SELECT|INSERT|UPDATE|DELETE|DROP|CREATE|ALTER|EXEC|UNION|SCRIPT)\b)/i',
            '/(\b(OR|AND)\s+\d+\s*=\s*\d+)/i',
            '/(\b(OR|AND)\s+\'[^\']*\'\s*=\s*\'[^\']*\')/i',
            '/(\b(OR|AND)\s+\"[^\"]*\"\s*=\s*\"[^\"]*\")/i',
            '/(\b(OR|AND)\s+[a-zA-Z_][a-zA-Z0-9_]*\s*=\s*[a-zA-Z_][a-zA-Z0-9_]*)/i'
        ];
        
        foreach ($patterns as $pattern) {
            $input = preg_replace($pattern, '', $input);
        }
        
        return trim($input);
    }
    
    public static function validateInput($input, $type = 'string', $maxLength = 255) {
        switch ($type) {
            case 'email':
                return filter_var($input, FILTER_VALIDATE_EMAIL) !== false;
            case 'int':
                return filter_var($input, FILTER_VALIDATE_INT) !== false;
            case 'float':
                return filter_var($input, FILTER_VALIDATE_FLOAT) !== false;
            case 'url':
                return filter_var($input, FILTER_VALIDATE_URL) !== false;
            case 'string':
            default:
                return is_string($input) && strlen($input) <= $maxLength;
        }
    }
}

// File Upload Security
class FileUploadSecurity {
    
    private static $allowedTypes = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/gif' => 'gif',
        'application/pdf' => 'pdf',
        'text/plain' => 'txt'
    ];
    
    private static $maxFileSize = 5 * 1024 * 1024; // 5MB
    
    public static function validateUpload($file) {
        $errors = [];
        
        // Check if file was uploaded
        if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            $errors[] = 'No file uploaded';
            return $errors;
        }
        
        // Check file size
        if ($file['size'] > self::$maxFileSize) {
            $errors[] = 'File too large (max 5MB)';
        }
        
        // Check file type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        if (!array_key_exists($mimeType, self::$allowedTypes)) {
            $errors[] = 'Invalid file type';
        }
        
        // Check file extension
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, self::$allowedTypes)) {
            $errors[] = 'Invalid file extension';
        }
        
        // Scan for malware patterns
        $content = file_get_contents($file['tmp_name']);
        if (self::containsMalware($content)) {
            $errors[] = 'File contains suspicious content';
        }
        
        return $errors;
    }
    
    private static function containsMalware($content) {
        $malwarePatterns = [
            '/<script[^>]*>.*?<\/script>/i',
            '/javascript:/i',
            '/vbscript:/i',
            '/onload=/i',
            '/onerror=/i',
            '/eval\(/i',
            '/exec\(/i'
        ];
        
        foreach ($malwarePatterns as $pattern) {
            if (preg_match($pattern, $content)) {
                return true;
            }
        }
        
        return false;
    }
    
    public static function generateSecureFilename($originalName) {
        $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        $filename = bin2hex(random_bytes(16)) . '.' . $extension;
        return $filename;
    }
}

// Rate Limiting
class RateLimiter {
    
    public static function checkRateLimit($action, $limit = 10, $window = 3600) {
        $ip = $_SERVER['REMOTE_ADDR'];
        $key = $action . '_' . $ip;
        
        if (!isset($_SESSION['rate_limit'])) {
            $_SESSION['rate_limit'] = [];
        }
        
        $now = time();
        
        // Clean old entries
        if (isset($_SESSION['rate_limit'][$key])) {
            $_SESSION['rate_limit'][$key] = array_filter(
                $_SESSION['rate_limit'][$key],
                function($timestamp) use ($now, $window) {
                    return ($now - $timestamp) < $window;
                }
            );
        } else {
            $_SESSION['rate_limit'][$key] = [];
        }
        
        // Check if limit exceeded
        if (count($_SESSION['rate_limit'][$key]) >= $limit) {
            return false;
        }
        
        // Add current request
        $_SESSION['rate_limit'][$key][] = $now;
        
        return true;
    }
}

// Input Validation
class InputValidator {
    
    public static function validateCVData($data) {
        $errors = [];
        
        // Required fields
        $required = ['name', 'email', 'address', 'phone_number'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                $errors[] = ucfirst($field) . ' is required';
            }
        }
        
        // Email validation
        if (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid email format';
        }
        
        // Phone validation
        if (!empty($data['phone_number']) && !preg_match('/^[\+]?[0-9\s\-\(\)]{10,}$/', $data['phone_number'])) {
            $errors[] = 'Invalid phone number format';
        }
        
        // Length validation
        $maxLengths = [
            'name' => 100,
            'email' => 255,
            'address' => 500,
            'phone_number' => 20,
            'profile_summary' => 2000
        ];
        
        foreach ($maxLengths as $field => $maxLength) {
            if (isset($data[$field]) && strlen($data[$field]) > $maxLength) {
                $errors[] = ucfirst($field) . ' is too long (max ' . $maxLength . ' characters)';
            }
        }
        
        return $errors;
    }
    
    public static function validateWorkExperience($data) {
        $errors = [];
        
        $required = ['job_title', 'company', 'work_start'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                $errors[] = ucfirst($field) . ' is required';
            }
        }
        
        // Date validation
        if (!empty($data['work_start']) && !self::validateDate($data['work_start'])) {
            $errors[] = 'Invalid start date format';
        }
        
        if (!empty($data['work_end']) && !self::validateDate($data['work_end'])) {
            $errors[] = 'Invalid end date format';
        }
        
        return $errors;
    }
    
    private static function validateDate($date) {
        $d = DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') === $date;
    }
}

// Security Headers
function setSecurityHeaders() {
    // Prevent XSS
    header('X-XSS-Protection: 1; mode=block');
    
    // Prevent MIME type sniffing
    header('X-Content-Type-Options: nosniff');
    
    // Prevent clickjacking
    header('X-Frame-Options: DENY');
    
    // Strict Transport Security (HTTPS only)
    if (isset($_SERVER['HTTPS'])) {
        header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
    }
    
    // Content Security Policy
    header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; img-src 'self' data:; font-src 'self' https://cdnjs.cloudflare.com; connect-src 'self' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com;");
    
    // Referrer Policy
    header('Referrer-Policy: strict-origin-when-cross-origin');
}

// Initialize security
setSecurityHeaders();
?>
