# Audit Logging System

This directory contains the audit logging system for tracking important changes and user actions in the CV application.

## Files

### Core Files
- `create_audit_logs_table.sql` - SQL script to create the audit_logs table
- `audit_logger.php` - Main AuditLogger class for logging operations
- `audit_viewer.php` - Admin interface for viewing audit logs

## Database Schema

The `audit_logs` table tracks:
- **id** - Primary key
- **user_id** - Foreign key to users table (with CASCADE DELETE)
- **action** - Type of action (INSERT, UPDATE, DELETE, LOGIN, LOGOUT)
- **table_name** - Database table affected
- **record_id** - ID of the affected record
- **old_values** - JSON of old values (for UPDATE/DELETE)
- **new_values** - JSON of new values (for INSERT/UPDATE)
- **ip_address** - Client IP address
- **user_agent** - Browser user agent
- **timestamp** - When the action occurred

## Usage

### Basic Logging
```php
require_once 'audit/audit_logger.php';
$auditLogger = new AuditLogger($conn);

// Log a CV operation
$auditLogger->logCV('INSERT', $cvId, $userId, null, $cvData);

// Log authentication
$auditLogger->logAuth('LOGIN', $userId, $additionalData);
```

### Available Methods
- `logCV($action, $cvId, $userId, $oldData, $newData)` - Log CV operations
- `logEducation($action, $educationId, $userId, $oldData, $newData)` - Log education operations
- `logWorkExperience($action, $workId, $userId, $oldData, $newData)` - Log work experience operations
- `logSkills($action, $skillId, $userId, $oldData, $newData)` - Log skills operations
- `logLanguages($action, $languageId, $userId, $oldData, $newData)` - Log languages operations
- `logAuth($action, $userId, $additionalData)` - Log authentication operations

### Admin Interface
Access the audit viewer at `/audit/audit_viewer.php` (admin only).

Features:
- Filter by user, action, or table
- Pagination
- Detailed view of changes
- Export capabilities

## Security Features

- **IP Tracking** - Records client IP addresses
- **User Agent** - Tracks browser information
- **Data Integrity** - Stores both old and new values for updates
- **Access Control** - Only admins can view audit logs
- **Cascade Delete** - Audit logs are deleted when users are deleted

## Installation

1. Run the SQL script to create the table:
   ```sql
   source audit/create_audit_logs_table.sql
   ```

2. The audit logging is automatically integrated into:
   - CV creation, updates, and deletion
   - User login and logout
   - All database operations

## Monitoring

The audit system provides comprehensive tracking of:
- Who performed what action
- When the action occurred
- What data was changed
- Where the action came from (IP/User Agent)

This ensures full accountability and traceability for all user actions in the system.
