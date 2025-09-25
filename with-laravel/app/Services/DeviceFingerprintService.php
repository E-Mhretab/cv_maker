<?php

namespace App\Services;

use Illuminate\Http\Request;

class DeviceFingerprintService
{
    /**
     * Generate a unique device fingerprint based on browser and system characteristics
     */
    public static function generateDeviceId(Request $request): string
    {
        $components = [];
        
        // User Agent (browser, OS, device type)
        $userAgent = $request->header('User-Agent', '');
        $components[] = self::hashComponent($userAgent);
        
        // Screen resolution (if available via JavaScript)
        $screenResolution = $request->header('X-Screen-Resolution');
        if ($screenResolution) {
            $components[] = self::hashComponent($screenResolution);
        }
        
        // Timezone (if available via JavaScript)
        $timezone = $request->header('X-Timezone');
        if ($timezone) {
            $components[] = self::hashComponent($timezone);
        }
        
        // Language preferences
        $acceptLanguage = $request->header('Accept-Language', '');
        $components[] = self::hashComponent($acceptLanguage);
        
        // Accept headers (browser capabilities)
        $accept = $request->header('Accept', '');
        $components[] = self::hashComponent($accept);
        
        // Connection type (if available)
        $connection = $request->header('X-Connection-Type');
        if ($connection) {
            $components[] = self::hashComponent($connection);
        }
        
        // IP address (first 3 octets for privacy)
        $ip = $request->ip();
        $ipComponents = explode('.', $ip);
        if (count($ipComponents) >= 3) {
            $ipPrefix = $ipComponents[0] . '.' . $ipComponents[1] . '.' . $ipComponents[2];
            $components[] = self::hashComponent($ipPrefix);
        }
        
        // Combine all components
        $fingerprint = implode('|', $components);
        
        // Generate a consistent hash
        return 'DEV_' . strtoupper(substr(hash('sha256', $fingerprint), 0, 16));
    }
    
    /**
     * Generate an enhanced device ID using JavaScript fingerprint data
     */
    public static function generateEnhancedDeviceId(Request $request): string
    {
        $components = [];
        
        // User Agent (browser, OS, device type)
        $userAgent = $request->header('User-Agent', '');
        $components[] = self::hashComponent($userAgent);
        
        // JavaScript fingerprint data
        $fingerprintData = $request->input();
        
        if (isset($fingerprintData['screenResolution'])) {
            $screen = $fingerprintData['screenResolution'];
            $screenInfo = $screen['width'] . 'x' . $screen['height'] . '_' . $screen['colorDepth'];
            $components[] = self::hashComponent($screenInfo);
        }
        
        if (isset($fingerprintData['timezone'])) {
            $components[] = self::hashComponent($fingerprintData['timezone']);
        }
        
        if (isset($fingerprintData['language'])) {
            $components[] = self::hashComponent($fingerprintData['language']);
        }
        
        if (isset($fingerprintData['hardwareConcurrency'])) {
            $components[] = self::hashComponent($fingerprintData['hardwareConcurrency']);
        }
        
        if (isset($fingerprintData['canvas'])) {
            $components[] = self::hashComponent($fingerprintData['canvas']);
        }
        
        // Accept headers
        $acceptLanguage = $request->header('Accept-Language', '');
        $components[] = self::hashComponent($acceptLanguage);
        
        // IP address (first 3 octets for privacy)
        $ip = $request->ip();
        $ipComponents = explode('.', $ip);
        if (count($ipComponents) >= 3) {
            $ipPrefix = $ipComponents[0] . '.' . $ipComponents[1] . '.' . $ipComponents[2];
            $components[] = self::hashComponent($ipPrefix);
        }
        
        // Combine all components
        $fingerprint = implode('|', $components);
        
        // Generate a consistent hash
        return 'DEV_' . strtoupper(substr(hash('sha256', $fingerprint), 0, 16));
    }
    
    /**
     * Generate a device ID from user agent only (fallback method)
     */
    public static function generateDeviceIdFromUserAgent(string $userAgent): string
    {
        // Parse user agent for key characteristics
        $browser = self::parseBrowser($userAgent);
        $os = self::parseOperatingSystem($userAgent);
        $device = self::parseDeviceType($userAgent);
        
        $components = [
            $browser,
            $os,
            $device
        ];
        
        $fingerprint = implode('|', $components);
        return 'DEV_' . strtoupper(substr(hash('sha256', $fingerprint), 0, 16));
    }
    
    /**
     * Hash a component for consistent fingerprinting
     */
    private static function hashComponent(string $component): string
    {
        return substr(hash('sha256', trim($component)), 0, 8);
    }
    
    /**
     * Parse browser from user agent
     */
    private static function parseBrowser(string $userAgent): string
    {
        if (strpos($userAgent, 'Chrome') !== false && strpos($userAgent, 'Edg') === false) {
            return 'Chrome';
        } elseif (strpos($userAgent, 'Firefox') !== false) {
            return 'Firefox';
        } elseif (strpos($userAgent, 'Safari') !== false && strpos($userAgent, 'Chrome') === false) {
            return 'Safari';
        } elseif (strpos($userAgent, 'Edg') !== false) {
            return 'Edge';
        } elseif (strpos($userAgent, 'Opera') !== false) {
            return 'Opera';
        } else {
            return 'Unknown';
        }
    }
    
    /**
     * Parse operating system from user agent
     */
    private static function parseOperatingSystem(string $userAgent): string
    {
        if (strpos($userAgent, 'Windows NT 10.0') !== false) {
            return 'Windows10';
        } elseif (strpos($userAgent, 'Windows NT 6.3') !== false) {
            return 'Windows8.1';
        } elseif (strpos($userAgent, 'Windows NT 6.1') !== false) {
            return 'Windows7';
        } elseif (strpos($userAgent, 'Macintosh') !== false) {
            return 'macOS';
        } elseif (strpos($userAgent, 'Linux') !== false) {
            return 'Linux';
        } elseif (strpos($userAgent, 'Android') !== false) {
            return 'Android';
        } elseif (strpos($userAgent, 'iPhone') !== false) {
            return 'iOS';
        } elseif (strpos($userAgent, 'iPad') !== false) {
            return 'iPadOS';
        } else {
            return 'Unknown';
        }
    }
    
    /**
     * Parse device type from user agent
     */
    private static function parseDeviceType(string $userAgent): string
    {
        if (strpos($userAgent, 'Mobile') !== false) {
            return 'Mobile';
        } elseif (strpos($userAgent, 'Tablet') !== false) {
            return 'Tablet';
        } elseif (strpos($userAgent, 'iPad') !== false) {
            return 'Tablet';
        } else {
            return 'Desktop';
        }
    }
    
    /**
     * Get device information for display
     */
    public static function getDeviceInfo(string $userAgent): array
    {
        return [
            'browser' => self::parseBrowser($userAgent),
            'os' => self::parseOperatingSystem($userAgent),
            'device_type' => self::parseDeviceType($userAgent),
            'user_agent' => $userAgent
        ];
    }
}
