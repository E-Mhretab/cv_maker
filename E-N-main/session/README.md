# Enhanced Session Management System

This directory contains the enhanced session management system with device tracking, refresh tokens, and secure session lifecycle management.

## Files

### Core Files
- `enhance_sessions_table.sql` - SQL script to enhance the user_sessions table
- `session_manager.php` - Main SessionManager class for session operations
- `session_middleware.php` - Middleware for automatic session tracking
- `session_management.php` - User interface for managing sessions

## Database Schema

The enhanced `user_sessions` table includes:
- **device_id** - Unique identifier for each device/browser
- **refresh_token** - Secure token for session refresh
- **last_activity** - Timestamp of last user activity
- **ip_address** - Client IP address (existing)
- **user_agent** - Browser user agent (existing)

## Features

### 🔐 Security Features
- **Device Tracking** - Each device gets a unique identifier
- **Refresh Tokens** - Secure session refresh without re-login
- **IP Tracking** - Monitor sessions by IP address
- **Session Limits** - Maximum 5 concurrent sessions per user
- **Automatic Cleanup** - Expired sessions are automatically removed

### 📊 Session Management
- **Activity Tracking** - Last activity timestamp for each session
- **Session Validation** - Real-time session validation against database
- **Bulk Operations** - Destroy all sessions or all other sessions
- **Session Statistics** - View active session counts and details

### 🔄 Session Lifecycle
- **Auto-Refresh** - Sessions refresh automatically when half-expired
- **Graceful Expiry** - Sessions expire based on both session and refresh token
- **Secure Logout** - Complete session cleanup on logout
- **Concurrent Control** - Automatic cleanup of old sessions when limit reached

## Usage

### Basic Session Operations
```php
require_once 'session/session_manager.php';

$sessionManager = new SessionManager($conn);

// Create session
$result = $sessionManager->createSession($userId);

// Validate session
$validation = $sessionManager->validateSession($sessionId);

// Refresh session
$refresh = $sessionManager->refreshSessionWithToken($refreshToken);

// Destroy session
$sessionManager->destroySession($sessionId);
```

### Session Middleware
```php
require_once 'session/session_middleware.php';

$sessionMiddleware = new SessionMiddleware($conn);
$sessionMiddleware->handle(); // Automatically tracks activity
```

### User Session Management
Access the session management interface at `/session/session_management.php` (requires login).

Features:
- View all active sessions
- Destroy individual sessions
- Bulk session management
- Session statistics
- Device information

## Configuration

### Session Settings
```php
private $sessionLifetime = 3600; // 1 hour
private $refreshTokenLifetime = 2592000; // 30 days
private $maxSessionsPerUser = 5; // Maximum concurrent sessions
```

### Excluded Paths
The session middleware automatically excludes certain paths from activity tracking:
- `/css/`, `/js/`, `/img/`, `/fonts/`
- `/favicon.ico`, `/robots.txt`

## Installation

1. **Database Setup**: Run the SQL script to enhance the sessions table:
   ```sql
   source session/enhance_sessions_table.sql
   ```

2. **Automatic Integration**: Session management is automatically integrated into:
   - User login and logout
   - Session validation
   - Activity tracking via middleware

3. **User Interface**: Users can access session management at `/session/session_management.php`

## Security Benefits

- **Device Fingerprinting** - Track sessions by device characteristics
- **Refresh Token Security** - Secure session refresh without password
- **Session Limits** - Prevent session hijacking with concurrent limits
- **Activity Monitoring** - Track user activity patterns
- **Automatic Cleanup** - Remove expired and inactive sessions
- **IP Validation** - Monitor sessions by IP address

## Monitoring

The system provides comprehensive session monitoring:
- **Active Session Count** - Total active sessions
- **User Session Distribution** - Sessions per user
- **Recent Activity** - Sessions active in the last hour
- **Device Information** - Browser and device details
- **IP Tracking** - Geographic and network monitoring

This enhanced session management system provides enterprise-level security and monitoring capabilities for your CV application.
