<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Session Management - Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <nav class="navbar navbar-dark bg-primary">
        <div class="container">
            <span class="navbar-brand mb-0 h1">
                <i class="fas fa-shield-alt me-2"></i>Session Management
            </span>
            <div class="navbar-nav ms-auto">
                <a href="{{ route('admin.dashboard') }}" class="nav-link text-white">
                    <i class="fas fa-arrow-left me-1"></i>Back to Dashboard
                </a>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Session Statistics -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-0">{{ $sessionStats['total'] }}</h4>
                                <p class="mb-0">Total Sessions</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-users fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-0">{{ $sessionStats['active'] }}</h4>
                                <p class="mb-0">Active Sessions</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-check-circle fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-0">{{ $sessionStats['expired'] }}</h4>
                                <p class="mb-0">Expired Sessions</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-clock fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-0">{{ $sessionStats['unique_users'] }}</h4>
                                <p class="mb-0">Unique Users</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-user fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Debug Information -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-bug me-2"></i>Debug Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>User Linking:</h6>
                                <ul class="list-unstyled">
                                    <li><span class="badge bg-success">{{ $debugInfo['sessions_with_users'] }}</span> Sessions with users</li>
                                    <li><span class="badge bg-warning">{{ $debugInfo['sessions_without_users'] }}</span> Sessions without users</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6>Device Information:</h6>
                                <ul class="list-unstyled">
                                    <li><span class="badge bg-success">{{ $debugInfo['sessions_with_device_id'] }}</span> Sessions with device ID</li>
                                    <li><span class="badge bg-warning">{{ $debugInfo['sessions_without_device_id'] }}</span> Sessions without device ID</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-tools me-2"></i>Session Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex gap-2">
                            <form method="POST" action="{{ route('admin.sessions.terminate-expired') }}" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-warning" onclick="return confirm('Are you sure you want to terminate all expired sessions?')">
                                    <i class="fas fa-trash me-1"></i>Terminate Expired Sessions
                                </button>
                            </form>
                            <button type="button" class="btn btn-info" onclick="location.reload()">
                                <i class="fas fa-sync me-1"></i>Refresh
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sessions Table -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-list me-2"></i>Active Sessions</h5>
            </div>
            <div class="card-body">
                @if($sessions->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" style="min-width: 1200px;">
                            <thead>
                                <tr>
                                    <th style="width: 20%;">User</th>
                                    <th style="width: 12%;">IP Address</th>
                                    <th style="width: 20%;">Device</th>
                                    <th style="width: 15%;">Last Activity</th>
                                    <th style="width: 15%;">Expires At</th>
                                    <th style="width: 8%;">Status</th>
                                    <th style="width: 10%;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sessions as $session)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-primary rounded-circle d-flex align-items-center justify-content-center me-2">
                                                    <i class="fas fa-user text-white"></i>
                                                </div>
                                                <div>
                                                    @if($session->user)
                                                        <div class="fw-bold">{{ $session->user->name }}</div>
                                                        <small class="text-muted">{{ $session->user->email }}</small>
                                                    @else
                                                        <div class="fw-bold text-muted" title="Click to view full session ID: {{ $session->id }}" style="cursor: pointer;" onclick="showSessionDetails('{{ $session->id }}', '{{ $session->user_id }}', '{{ $session->device_id }}', '{{ addslashes($session->user_agent) }}', '{{ $session->ip_address }}', '{{ $session->last_activity ? $session->last_activity->format('M d, Y H:i:s') : 'N/A' }}', '{{ $session->expires_at ? $session->expires_at->format('M d, Y H:i:s') : 'N/A' }}')">
                                                            <code>Session: {{ Str::limit($session->id, 20) }}</code>
                                                            @if(strlen($session->id) > 20)
                                                                <i class="fas fa-ellipsis-h text-muted"></i>
                                                            @endif
                                                        </div>
                                                        <small class="text-muted">No user linked</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <code>{{ $session->ip_address ?? 'N/A' }}</code>
                                        </td>
                                        <td>
                                            <div>
                                                @if($session->device_id && str_starts_with($session->device_id, 'DEV_'))
                                                    <div class="fw-bold" title="Click to view full device ID: {{ $session->device_id }}" style="cursor: pointer;" onclick="showSessionDetails('{{ $session->id }}', '{{ $session->user_id }}', '{{ $session->device_id }}', '{{ addslashes($session->user_agent) }}', '{{ $session->ip_address }}', '{{ $session->last_activity ? $session->last_activity->format('M d, Y H:i:s') : 'N/A' }}', '{{ $session->expires_at ? $session->expires_at->format('M d, Y H:i:s') : 'N/A' }}')">
                                                        <code class="text-success">{{ Str::limit($session->device_id, 20) }}</code>
                                                        @if(strlen($session->device_id) > 20)
                                                            <i class="fas fa-ellipsis-h text-muted"></i>
                                                        @endif
                                                        <br><small class="text-success"><i class="fas fa-fingerprint"></i> Real Device ID</small>
                                                    </div>
                                                @else
                                                    <div class="fw-bold text-muted" title="Click to view full session ID: {{ $session->id }}" style="cursor: pointer;" onclick="showSessionDetails('{{ $session->id }}', '{{ $session->user_id }}', '{{ $session->device_id }}', '{{ addslashes($session->user_agent) }}', '{{ $session->ip_address }}', '{{ $session->last_activity ? $session->last_activity->format('M d, Y H:i:s') : 'N/A' }}', '{{ $session->expires_at ? $session->expires_at->format('M d, Y H:i:s') : 'N/A' }}')">
                                                        <code>Session: {{ Str::limit($session->id, 20) }}</code>
                                                        @if(strlen($session->id) > 20)
                                                            <i class="fas fa-ellipsis-h text-muted"></i>
                                                        @endif
                                                        <br><small class="text-warning"><i class="fas fa-exclamation-triangle"></i> No Device ID</small>
                                                    </div>
                                                @endif
                                                @if($session->user_agent)
                                                    @php
                                                        $userAgent = $session->user_agent;
                                                        $deviceInfo = '';
                                                        if (strpos($userAgent, 'Windows') !== false) {
                                                            $deviceInfo = 'Windows';
                                                        } elseif (strpos($userAgent, 'Macintosh') !== false) {
                                                            $deviceInfo = 'Mac';
                                                        } elseif (strpos($userAgent, 'Linux') !== false) {
                                                            $deviceInfo = 'Linux';
                                                        } elseif (strpos($userAgent, 'Android') !== false) {
                                                            $deviceInfo = 'Android';
                                                        } elseif (strpos($userAgent, 'iPhone') !== false || strpos($userAgent, 'iPad') !== false) {
                                                            $deviceInfo = 'iOS';
                                                        } else {
                                                            $deviceInfo = 'Unknown OS';
                                                        }
                                                        
                                                        if (strpos($userAgent, 'Chrome') !== false) {
                                                            $deviceInfo .= ' / Chrome';
                                                        } elseif (strpos($userAgent, 'Firefox') !== false) {
                                                            $deviceInfo .= ' / Firefox';
                                                        } elseif (strpos($userAgent, 'Safari') !== false) {
                                                            $deviceInfo .= ' / Safari';
                                                        } elseif (strpos($userAgent, 'Edge') !== false) {
                                                            $deviceInfo .= ' / Edge';
                                                        }
                                                    @endphp
                                                    <small class="text-muted">{{ $deviceInfo }}</small>
                                                @else
                                                    <small class="text-muted">No user agent</small>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <div>{{ $session->last_activity ? $session->last_activity->format('M d, Y H:i') : 'N/A' }}</div>
                                                <small class="text-muted">{{ $session->last_activity ? $session->last_activity->diffForHumans() : '' }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <div>{{ $session->expires_at ? $session->expires_at->format('M d, Y H:i') : 'N/A' }}</div>
                                                <small class="text-muted">{{ $session->expires_at ? $session->expires_at->diffForHumans() : '' }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            @if($session->expires_at && $session->expires_at > now())
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-warning">Expired</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <form method="POST" action="{{ route('admin.sessions.terminate', $session->id) }}" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to terminate this session?')">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                                @if($session->user_id)
                                                    <form method="POST" action="{{ route('admin.sessions.terminate-all', $session->user_id) }}" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-warning" onclick="return confirm('Are you sure you want to terminate ALL sessions for this user?')">
                                                            <i class="fas fa-user-times"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $sessions->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No Sessions Found</h5>
                        <p class="text-muted">There are currently no user sessions in the system.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Session Details Modal -->
    <div class="modal fade" id="sessionDetailsModal" tabindex="-1" aria-labelledby="sessionDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="sessionDetailsModalLabel">Session Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="sessionDetailsContent">
                        <!-- Content will be populated by JavaScript -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function showSessionDetails(sessionId, userId, deviceId, userAgent, ipAddress, lastActivity, expiresAt) {
            const content = `
                <div class="row">
                    <div class="col-md-6">
                        <h6>Session Information</h6>
                        <table class="table table-sm">
                            <tr><td><strong>Session ID:</strong></td><td><code>${sessionId}</code></td></tr>
                            <tr><td><strong>User ID:</strong></td><td>${userId || 'Not linked'}</td></tr>
                            <tr><td><strong>Device ID:</strong></td><td><code>${deviceId || 'Not set'}</code></td></tr>
                            <tr><td><strong>IP Address:</strong></td><td><code>${ipAddress}</code></td></tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6>Activity Information</h6>
                        <table class="table table-sm">
                            <tr><td><strong>Last Activity:</strong></td><td>${lastActivity}</td></tr>
                            <tr><td><strong>Expires At:</strong></td><td>${expiresAt}</td></tr>
                        </table>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <h6>User Agent</h6>
                        <div class="bg-light p-2 rounded">
                            <code style="word-break: break-all;">${userAgent || 'Not available'}</code>
                        </div>
                    </div>
                </div>
            `;
            
            document.getElementById('sessionDetailsContent').innerHTML = content;
            new bootstrap.Modal(document.getElementById('sessionDetailsModal')).show();
        }
    </script>
</body>
</html>
