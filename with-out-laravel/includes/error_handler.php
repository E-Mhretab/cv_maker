<?php
/**
 * Enhanced Error Handling System
 * Phase 7: Testing & Optimization
 */

// Custom error handler
function customErrorHandler($errno, $errstr, $errfile, $errline) {
    $errorTypes = [
        E_ERROR => 'Fatal Error',
        E_WARNING => 'Warning',
        E_PARSE => 'Parse Error',
        E_NOTICE => 'Notice',
        E_CORE_ERROR => 'Core Error',
        E_CORE_WARNING => 'Core Warning',
        E_COMPILE_ERROR => 'Compile Error',
        E_COMPILE_WARNING => 'Compile Warning',
        E_USER_ERROR => 'User Error',
        E_USER_WARNING => 'User Warning',
        E_USER_NOTICE => 'User Notice',
        E_STRICT => 'Strict Notice',
        E_RECOVERABLE_ERROR => 'Recoverable Error',
        E_DEPRECATED => 'Deprecated',
        E_USER_DEPRECATED => 'User Deprecated'
    ];
    
    $errorType = isset($errorTypes[$errno]) ? $errorTypes[$errno] : 'Unknown Error';
    
    $errorMessage = [
        'type' => $errorType,
        'message' => $errstr,
        'file' => $errfile,
        'line' => $errline,
        'timestamp' => date('Y-m-d H:i:s'),
        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown',
        'request_uri' => $_SERVER['REQUEST_URI'] ?? 'Unknown',
        'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'Unknown'
    ];
    
    // Log error to file
    logError($errorMessage);
    
    // Display user-friendly error message
    if (defined('DEVELOPMENT') && DEVELOPMENT) {
        displayDetailedError($errorMessage);
    } else {
        displayUserFriendlyError($errorType);
    }
    
    return true;
}

// Custom exception handler
function customExceptionHandler($exception) {
    $errorMessage = [
        'type' => 'Uncaught Exception',
        'message' => $exception->getMessage(),
        'file' => $exception->getFile(),
        'line' => $exception->getLine(),
        'trace' => $exception->getTraceAsString(),
        'timestamp' => date('Y-m-d H:i:s'),
        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown',
        'request_uri' => $_SERVER['REQUEST_URI'] ?? 'Unknown',
        'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'Unknown'
    ];
    
    logError($errorMessage);
    
    if (defined('DEVELOPMENT') && DEVELOPMENT) {
        displayDetailedError($errorMessage);
    } else {
        displayUserFriendlyError('System Error');
    }
}

// Log error to file
function logError($errorMessage) {
    $logFile = 'logs/error_' . date('Y-m-d') . '.log';
    $logDir = dirname($logFile);
    
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }
    
    $logEntry = json_encode($errorMessage) . "\n";
    file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
}

// Display detailed error for development
function displayDetailedError($errorMessage) {
    echo '<div style="background: #f8d7da; border: 1px solid #f5c6cb; padding: 15px; margin: 20px; border-radius: 5px;">';
    echo '<h3 style="color: #721c24;">🚨 ' . htmlspecialchars($errorMessage['type']) . '</h3>';
    echo '<p><strong>Message:</strong> ' . htmlspecialchars($errorMessage['message']) . '</p>';
    echo '<p><strong>File:</strong> ' . htmlspecialchars($errorMessage['file']) . '</p>';
    echo '<p><strong>Line:</strong> ' . htmlspecialchars($errorMessage['line']) . '</p>';
    echo '<p><strong>Time:</strong> ' . htmlspecialchars($errorMessage['timestamp']) . '</p>';
    
    if (isset($errorMessage['trace'])) {
        echo '<p><strong>Stack Trace:</strong></p>';
        echo '<pre style="background: #f8f9fa; padding: 10px; border-radius: 3px; overflow-x: auto;">';
        echo htmlspecialchars($errorMessage['trace']);
        echo '</pre>';
    }
    
    echo '</div>';
}

// Display user-friendly error
function displayUserFriendlyError($errorType) {
    http_response_code(500);
    
    echo '<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>System Error - CV Management</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <style>
            body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; }
            .error-container { min-height: 100vh; display: flex; align-items: center; justify-content: center; }
            .error-card { background: white; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.2); padding: 3rem; text-align: center; max-width: 500px; }
            .error-icon { font-size: 4rem; color: #dc3545; margin-bottom: 1rem; }
        </style>
    </head>
    <body>
        <div class="error-container">
            <div class="error-card">
                <i class="fas fa-exclamation-triangle error-icon"></i>
                <h1 class="text-danger mb-3">Oops! Something went wrong</h1>
                <p class="text-muted mb-4">We apologize for the inconvenience. Our team has been notified and is working to fix this issue.</p>
                <div class="d-grid gap-2">
                    <a href="javascript:history.back()" class="btn btn-primary">
                        <i class="fas fa-arrow-left me-2"></i>Go Back
                    </a>
                    <a href="index.php" class="btn btn-outline-primary">
                        <i class="fas fa-home me-2"></i>Go to Homepage
                    </a>
                </div>
                <hr class="my-4">
                <small class="text-muted">
                    Error ID: ' . uniqid() . '<br>
                    Time: ' . date('Y-m-d H:i:s') . '
                </small>
            </div>
        </div>
    </body>
    </html>';
}

// Database error handler
function handleDatabaseError($error, $query = '') {
    $errorMessage = [
        'type' => 'Database Error',
        'message' => $error,
        'query' => $query,
        'timestamp' => date('Y-m-d H:i:s'),
        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown',
        'request_uri' => $_SERVER['REQUEST_URI'] ?? 'Unknown',
        'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'Unknown'
    ];
    
    logError($errorMessage);
    
    if (defined('DEVELOPMENT') && DEVELOPMENT) {
        echo '<div style="background: #f8d7da; border: 1px solid #f5c6cb; padding: 15px; margin: 20px; border-radius: 5px;">';
        echo '<h3 style="color: #721c24;">🚨 Database Error</h3>';
        echo '<p><strong>Error:</strong> ' . htmlspecialchars($error) . '</p>';
        if ($query) {
            echo '<p><strong>Query:</strong> ' . htmlspecialchars($query) . '</p>';
        }
        echo '</div>';
    } else {
        displayUserFriendlyError('Database Error');
    }
}

// Validation error handler
function handleValidationError($field, $message) {
    $errorMessage = [
        'type' => 'Validation Error',
        'field' => $field,
        'message' => $message,
        'timestamp' => date('Y-m-d H:i:s'),
        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown',
        'request_uri' => $_SERVER['REQUEST_URI'] ?? 'Unknown',
        'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'Unknown'
    ];
    
    logError($errorMessage);
    
    return [
        'success' => false,
        'error' => $message,
        'field' => $field
    ];
}

// File upload error handler
function handleFileUploadError($errorCode) {
    $errorMessages = [
        UPLOAD_ERR_INI_SIZE => 'File is too large (server limit)',
        UPLOAD_ERR_FORM_SIZE => 'File is too large (form limit)',
        UPLOAD_ERR_PARTIAL => 'File was only partially uploaded',
        UPLOAD_ERR_NO_FILE => 'No file was uploaded',
        UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder',
        UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
        UPLOAD_ERR_EXTENSION => 'File upload stopped by extension'
    ];
    
    $message = $errorMessages[$errorCode] ?? 'Unknown upload error';
    
    $errorMessage = [
        'type' => 'File Upload Error',
        'code' => $errorCode,
        'message' => $message,
        'timestamp' => date('Y-m-d H:i:s'),
        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown',
        'request_uri' => $_SERVER['REQUEST_URI'] ?? 'Unknown',
        'ip_address' => $_SERVER['REMOTE_ADDR'] ?? 'Unknown'
    ];
    
    logError($errorMessage);
    
    return [
        'success' => false,
        'error' => $message
    ];
}

// Set error handlers
set_error_handler('customErrorHandler');
set_exception_handler('customExceptionHandler');

// Define development mode (set to false in production)
define('DEVELOPMENT', true);
?>
