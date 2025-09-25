<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Request;

class AuditLogger
{
    public static function log($userId, $action, $tableName, $recordId, $oldValues = null, $newValues = null)
    {
        return AuditLog::create([
            'user_id'    => $userId,
            'action'     => $action,
            'table_name' => $tableName,
            'record_id'  => $recordId,
            'old_values' => $oldValues ? json_encode($oldValues) : null,
            'new_values' => $newValues ? json_encode($newValues) : null,
            'ip_address' => Request::ip(),
            'user_agent' => Request::header('User-Agent'),
            'created_at' => now(),
        ]);
    }
    // Métodos de conveniencia
    public static function logCV($action, $cvId, $userId, $oldData = null, $newData = null) {
        return self::log($userId, $action, 'cv', $cvId, $oldData, $newData);
    }
    public static function logEducation($action, $educationId, $userId, $oldData = null, $newData = null) {
        return self::log($userId, $action, 'education', $educationId, $oldData, $newData);
    }
    public static function logWorkExperience($action, $workId, $userId, $oldData = null, $newData = null) {
        return self::log($userId, $action, 'work_experience', $workId, $oldData, $newData);
    }
    public static function logSkills($action, $skillId, $userId, $oldData = null, $newData = null) {
        return self::log($userId, $action, 'skills', $skillId, $oldData, $newData);
    }
    public static function logLanguages($action, $languageId, $userId, $oldData = null, $newData = null) {
        return self::log($userId, $action, 'languages', $languageId, $oldData, $newData);
    }
    public static function logAuth($action, $userId, $additionalData = null) {
        return self::log($userId, $action, 'users', $userId, null, $additionalData);
    }
}
