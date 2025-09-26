@extends('layouts.app')

@section('title', 'Admin Dashboard - CV Management System')

@section('navbar')
    <nav class="navbar navbar-dark bg-primary">
        <div class="container">
            <span class="navbar-brand mb-0 h1">
                <i class="fas fa-crown me-2"></i>Admin Dashboard
            </span>
            <div class="navbar-nav ms-auto">
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user me-1"></i>{{ htmlspecialchars($user->username ?? 'Admin') }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ url('manage/cvs') }}"><i class="fas fa-list me-2"></i>Manage CVs</a></li>
                        <li><a class="dropdown-item" href="{{ route('manage.cvs.create') }}"><i class="fas fa-plus me-2"></i>Create CV</a></li>
                        <li><a class="dropdown-item" href="{{ route('audit.logs') }}"><i class="fas fa-clipboard-list me-2"></i>Audit Logs</a></li>
                        <li><a class="dropdown-item" href="{{ route('session.management') }}"><i class="fas fa-shield-alt me-2"></i>Session Management</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ url('/') }}"><i class="fas fa-home me-2"></i>Public Homepage</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
@endsection

@section('content')
    <div class="container py-4">
        <!-- Welcome Message -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="alert alert-primary">
                    <h4 class="alert-heading">
                        <i class="fas fa-crown me-2"></i>Welcome, Admin!
                    </h4>
                    <p class="mb-0">You have full access to manage all CVs and users in the system.</p>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="stat-card p-3">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon primary me-3">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div>
                            <h3 class="mb-0">{{ $stats['total_cvs'] }}</h3>
                            <small class="text-muted">Total CVs</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card p-3">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon success me-3">
                            <i class="fas fa-users"></i>
                        </div>
                        <div>
                            <h3 class="mb-0">{{ $stats['total_users'] }}</h3>
                            <small class="text-muted">Total Users</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card p-3">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon warning me-3">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <div>
                            <h3 class="mb-0">{{ $stats['by_role']['user'] ?? 0 }}</h3>
                            <small class="text-muted">Regular Users</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stat-card p-3">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon info me-3">
                            <i class="fas fa-user-shield"></i>
                        </div>
                        <div>
                            <h3 class="mb-0">{{ $stats['by_role']['admin'] ?? 0 }}</h3>
                            <small class="text-muted">Admins</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-2">
                                <a href="{{ url('manage/cvs') }}" class="btn btn-primary w-100">
                                    <i class="fas fa-list me-2"></i>Manage All CVs
                                </a>
                            </div>
                            <div class="col-md-3 mb-2">
                                <a href="{{ route('manage.cvs.create') }}" class="btn btn-success w-100">
                                    <i class="fas fa-plus me-2"></i>Create New CV
                                </a>
                            </div>
                            <div class="col-md-3 mb-2">
                                <a href="{{ route('audit.logs') }}" class="btn btn-info w-100">
                                    <i class="fas fa-clipboard-list me-2"></i>Audit Logs
                                </a>
                            </div>
                            <div class="col-md-3 mb-2">
                                <a href="{{ route('audit.debug') }}" class="btn btn-outline-info w-100">
                                    <i class="fas fa-bug me-2"></i>Debug Audit
                                </a>
                            </div>
                            <div class="col-md-3 mb-2">
                                <a href="{{ route('session.management') }}" class="btn btn-warning w-100">
                                    <i class="fas fa-shield-alt me-2"></i>Session Management
                                </a>
                            </div>
                            <div class="col-md-3 mb-2">
                                <a href="{{ route('session.debug') }}" class="btn btn-outline-warning w-100">
                                    <i class="fas fa-bug me-2"></i>Debug Sessions
                                </a>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-3 mb-2">
                                <a href="{{ url('/') }}" class="btn btn-outline-primary w-100">
                                    <i class="fas fa-home me-2"></i>Public Homepage
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent CVs -->
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-clock me-2"></i>Recent CVs</h5>
                    </div>
                    <div class="card-body">
                        @if (empty($stats['recent_cvs']))  <!-- Cambiado a empty() para array -->
                            <p class="text-muted">No CVs found.</p>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Template</th>
                                            <th>Created</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($stats['recent_cvs'] as $cv)
                                            <tr>
                                                <td>{{ htmlspecialchars($cv['name']) }}</td>  <!-- Cambiado a ['name'] para array -->
                                                <td>
                                                    <span class="badge bg-secondary">
                                                        {{ getTemplateDisplayName($cv['template_type']) }}
                                                    </span>
                                                </td>
                                                <td>{{ date('M j, Y', strtotime($cv['created_at'])) }}</td>  <!-- Cambiado a ['created_at'] -->
                                                <td>
                                                    <a href="{{ url('manage/cv_preview?id=' . $cv['id']) }}" class="btn btn-sm btn-outline-primary">  <!-- Cambiado a ['id'] -->
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>CVs by Template</h5>
                    </div>
                    <div class="card-body">
                        @if (empty($templates))
                            <p class="text-muted">No data available.</p>
                        @else
                            @foreach ($templates as $template)
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span>{{ $template['name'] }}</span>
                                    <span class="badge bg-primary">{{ $template['count'] }}</span>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
