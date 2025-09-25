/**
 * Device Fingerprinting JavaScript
 * Captures additional device information for better fingerprinting
 */

class DeviceFingerprint {
    constructor() {
        this.fingerprintData = {};
        this.init();
    }

    init() {
        this.captureScreenResolution();
        this.captureTimezone();
        this.captureLanguage();
        this.captureConnectionType();
        this.captureHardwareConcurrency();
        this.captureCanvasFingerprint();
        this.sendFingerprintData();
    }

    captureScreenResolution() {
        this.fingerprintData.screenResolution = {
            width: screen.width,
            height: screen.height,
            availWidth: screen.availWidth,
            availHeight: screen.availHeight,
            colorDepth: screen.colorDepth,
            pixelDepth: screen.pixelDepth
        };
    }

    captureTimezone() {
        try {
            this.fingerprintData.timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
            this.fingerprintData.timezoneOffset = new Date().getTimezoneOffset();
        } catch (e) {
            this.fingerprintData.timezone = 'Unknown';
            this.fingerprintData.timezoneOffset = 0;
        }
    }

    captureLanguage() {
        this.fingerprintData.language = navigator.language;
        this.fingerprintData.languages = navigator.languages ? navigator.languages.join(',') : navigator.language;
    }

    captureConnectionType() {
        if (navigator.connection) {
            this.fingerprintData.connection = {
                effectiveType: navigator.connection.effectiveType,
                downlink: navigator.connection.downlink,
                rtt: navigator.connection.rtt
            };
        }
    }

    captureHardwareConcurrency() {
        this.fingerprintData.hardwareConcurrency = navigator.hardwareConcurrency || 'Unknown';
    }

    captureCanvasFingerprint() {
        try {
            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');
            
            // Set canvas size
            canvas.width = 200;
            canvas.height = 50;
            
            // Draw text with various fonts and styles
            ctx.textBaseline = 'top';
            ctx.font = '14px Arial';
            ctx.fillStyle = '#f60';
            ctx.fillRect(125, 1, 62, 20);
            ctx.fillStyle = '#069';
            ctx.font = '11px Arial';
            ctx.fillText('Device Fingerprint', 2, 15);
            ctx.fillStyle = 'rgba(102, 204, 0, 0.7)';
            ctx.font = '18px Arial';
            ctx.fillText('Device Fingerprint', 4, 35);
            
            // Get canvas data URL
            this.fingerprintData.canvas = canvas.toDataURL();
        } catch (e) {
            this.fingerprintData.canvas = 'Error';
        }
    }

    sendFingerprintData() {
        // Send fingerprint data to server
        fetch('/api/device-fingerprint', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            },
            body: JSON.stringify(this.fingerprintData)
        }).catch(error => {
            console.log('Device fingerprint data could not be sent:', error);
        });
    }

    getFingerprint() {
        return this.fingerprintData;
    }
}

// Initialize device fingerprinting when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    new DeviceFingerprint();
});

// Also initialize on page load for immediate capture
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function() {
        new DeviceFingerprint();
    });
} else {
    new DeviceFingerprint();
}
