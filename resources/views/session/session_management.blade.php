@extends('layouts.app')
@section('content')
<div class="container mt-4">
    <nav class="navbar navbar-dark bg-primary mb-4">
        <div class="container">
            <a href="{{ route('manage.cvs.index') }}" class="navbar-brand">
                <i class="fas fa-arrow-left me-2"></i>Back to CV Management
            </a>
            <div class="navbar-nav ms-auto">
                <span class="navbar-text">
                    <i class="fas fa-user me-1"></i>{{ $user->username }}
                </span>
            </div>
        </div>
    </nav>
    <div class="row mb-4">
        <div class="col">
            <h2><i class="fas fa-shield-alt me-2"></i>Session Management</h2>
            <p class="text-muted">Manage your active sessions and security settings</p>
        </div>
        <div class="col-auto">
            <a href="{{ route('session.management', ['debug' => 1]) }}" class="btn btn-outline-info">
                <i class="fas fa-bug me-1"></i>Debug
            </a>
            <a href="{{ route('session.debug') }}" class="btn btn-outline-warning">
                <i class="fas fa-search me-1"></i>Full Debug
            </a>
        </div>
    </div>
    @if($success)
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ $success }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if($debug)
        <div class="alert alert-info">
            <h5>Debug Information:</h5>
            <p><strong>Current Session ID:</strong> {{ $currentSessionId }}</p>
            <p><strong>User ID:</strong> {{ $user->id }}</p>
            <p><strong>Sessions Found:</strong> {{ $sessions->count() }}</p>
            <p><strong>Session Data:</strong></p>
            <pre>{{ print_r($sessionData, true) }}</pre>
        </div>
    @endif
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title text-primary">{{ $sessions->count() }}</h5>
                    <p class="card-text">Active Sessions</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title text-success">1</h5>
                    <p class="card-text">Current Session</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title text-warning">{{ $sessions->count() - 1 }}</h5>
                    <p class="card-text">Other Sessions</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title text-info">5</h5>
                    <p class="card-text">Max Sessions</p>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Bulk Actions</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('session.destroyOther') }}" class="d-inline">
                        @csrf
                        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#destroyOtherModal">
                            <i class="fas fa-trash me-1"></i>Destroy All Other Sessions
                        </button>
                    </form>
                    <form method="POST" action="{{ route('session.destroyAll') }}" class="d-inline ms-2">
                        @csrf
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#destroyAllModal">
                            <i class="fas fa-exclamation-triangle me-1"></i>Destroy All Sessions
                        </button>
                    </form>
                    <small class="text-muted d-block mt-2">
                        <i class="fas fa-info-circle me-1"></i>
                        Destroying all sessions will log you out from all devices.
                    </small>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Active Sessions</h5>
                </div>
                <div class="card-body">
                    @if($sessions->isEmpty())
                        <div class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No active sessions found</p>
                        </div>
                    @else
                        <div class="row">
                            @foreach($sessions as $session)
                                <div class="col-md-6 mb-3">
                                    <div class="card session-card {{ $session->id === $currentSessionId ? 'current-session' : '' }}">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div class="flex-grow-1">
                                                    <h6 class="card-title">
                                                        @if($session->id === $currentSessionId)
                                                            <i class="fas fa-circle text-success me-1"></i>Current Session
                                                        @else
                                                            <i class="fas fa-circle text-primary me-1"></i>Other Session
                                                        @endif
                                                    </h6>
                                                    <div class="device-info">
                                                        <div><strong>Device ID:</strong> {{ Str::limit($session->device_id, 16) }}</div>
                                                        <div><strong>IP Address:</strong> {{ $session->ip_address }}</div>
                                                        <div><strong>User Agent:</strong> {{ Str::limit($session->user_agent, 50) }}</div>
                                                        <div><strong>Last Activity:</strong> {{ $session->last_activity }}</div>
                                                        <div><strong>Created:</strong> {{ $session->created_at }}</div>
                                                        <div><strong>Expires:</strong> {{ $session->expires_at }}</div>
                                                    </div>
                                                </div>
                                                @if($session->id !== $currentSessionId)
                                                    <form method="POST" action="{{ route('session.destroy') }}" class="session-actions ms-2">
                                                        @csrf
                                                        <input type="hidden" name="session_id" value="{{ $session->id }}">
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to destroy this session?')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- Destroy Other Sessions Modal -->
    <div class="modal fade" id="destroyOtherModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Destroy All Other Sessions</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>This will destroy all your other active sessions, keeping only the current one.</p>
                    <p class="text-warning"><i class="fas fa-exclamation-triangle me-1"></i>This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form method="POST" action="{{ route('session.destroyOther') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-warning">Destroy Other Sessions</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Destroy All Sessions Modal -->
    <div class="modal fade" id="destroyAllModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Destroy All Sessions</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>This will destroy ALL your active sessions, including the current one.</p>
                    <p class="text-danger"><i class="fas fa-exclamation-triangle me-1"></i>You will be logged out immediately.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form method="POST" action="{{ route('session.destroyAll') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-danger">Destroy All Sessions</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
