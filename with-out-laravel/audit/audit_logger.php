<?php
/**
 * Audit Logger Class
 * Centralized logging system for tracking important changes
 */

class AuditLogger {
    private $conn;
    
    public function __construct($connection) {
        $this->conn = $connection;
    }
    
    /**
     * Log an action to the audit_logs table
     */
    public function log($userId, $action, $tableName, $recordId, $oldValues = null, $newValues = null) {
        try {
            $stmt = $this->conn->prepare("
                INSERT INTO audit_logs (user_id, action, table_name, record_id, old_values, new_values, ip_address, user_agent) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ");
            
            $ipAddress = $this->getClientIpAddress();
            $userAgent = $this->getUserAgent();
            
            $oldValuesJson = $oldValues ? json_encode($oldValues) : null;
            $newValuesJson = $newValues ? json_encode($newValues) : null;
            
            $stmt->bind_param("ississss", $userId, $action, $tableName, $recordId, $oldValuesJson, $newValuesJson, $ipAddress, $userAgent);
            $stmt->execute();
            $stmt->close();
            
            return true;
        } catch (Exception $e) {
            error_log("Audit logging failed: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Log CV operations
     */
    public function logCV($action, $cvId, $userId, $oldData = null, $newData = null) {
        return $this->log($userId, $action, 'cv', $cvId, $oldData, $newData);
    }
    
    /**
     * Log education operations
     */
    public function logEducation($action, $educationId, $userId, $oldData = null, $newData = null) {
        return $this->log($userId, $action, 'education', $educationId, $oldData, $newData);
    }
    
    /**
     * Log work experience operations
     */
    public function logWorkExperience($action, $workId, $userId, $oldData = null, $newData = null) {
        return $this->log($userId, $action, 'work_experience', $workId, $oldData, $newData);
    }
    
    /**
     * Log skills operations
     */
    public function logSkills($action, $skillId, $userId, $oldData = null, $newData = null) {
        return $this->log($userId, $action, 'skills', $skillId, $oldData, $newData);
    }
    
    /**
     * Log languages operations
     */
    public function logLanguages($action, $languageId, $userId, $oldData = null, $newData = null) {
        return $this->log($userId, $action, 'languages', $languageId, $oldData, $newData);
    }
    
    /**
     * Log authentication operations
     */
    public function logAuth($action, $userId, $additionalData = null) {
        return $this->log($userId, $action, 'users', $userId, null, $additionalData);
    }
    
    /**
     * Get client IP address
     */
    private function getClientIpAddress() {
        $ipKeys = ['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR'];
        foreach ($ipKeys as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip);
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                        return $ip;
                    }
                }
            }
        }
        return $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    }
    
    /**
     * Get user agent
     */
    private function getUserAgent() {
        return $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
    }
    
    /**
     * Get audit logs for a specific user
     */
    public function getUserAuditLogs($userId, $limit = 50) {
        $stmt = $this->conn->prepare("
            SELECT al.*, u.username 
            FROM audit_logs al 
            LEFT JOIN users u ON al.user_id = u.id 
            WHERE al.user_id = ? 
            ORDER BY al.timestamp DESC 
            LIMIT ?
        ");
        $stmt->bind_param("ii", $userId, $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        $logs = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        
        return $logs;
    }
    
    /**
     * Get all audit logs (admin only)
     */
    public function getAllAuditLogs($limit = 100) {
        $stmt = $this->conn->prepare("
            SELECT al.*, u.username 
            FROM audit_logs al 
            LEFT JOIN users u ON al.user_id = u.id 
            ORDER BY al.timestamp DESC 
            LIMIT ?
        ");
        $stmt->bind_param("i", $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        $logs = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        
        return $logs;
    }
    
    /**
     * Get audit logs for a specific table
     */
    public function getTableAuditLogs($tableName, $recordId = null, $limit = 50) {
        $sql = "
            SELECT al.*, u.username 
            FROM audit_logs al 
            LEFT JOIN users u ON al.user_id = u.id 
            WHERE al.table_name = ?
        ";
        
        if ($recordId) {
            $sql .= " AND al.record_id = ?";
        }
        
        $sql .= " ORDER BY al.timestamp DESC LIMIT ?";
        
        $stmt = $this->conn->prepare($sql);
        
        if ($recordId) {
            $stmt->bind_param("sii", $tableName, $recordId, $limit);
        } else {
            $stmt->bind_param("si", $tableName, $limit);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        $logs = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        
        return $logs;
    }
}
?>
