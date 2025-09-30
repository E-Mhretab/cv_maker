<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - CV Management System</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .stat-card {
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        }
        .stat-icon {
            font-size: 2.5rem;
            opacity: 0.8;
        }
        .stat-number {
            font-size: 2rem;
            font-weight: bold;
        }
        .recent-cv-item {
            border-left: 3px solid #0d6efd;
            padding-left: 15px;
            margin-bottom: 10px;
        }
        .template-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }
        .quick-action-btn {
            transition: all 0.3s ease;
        }
        .quick-action-btn:hover {
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-dark bg-primary">
        <div class="container">
            <span class="navbar-brand mb-0 h1">
                <i class="fas fa-crown me-2"></i>Admin Dashboard
            </span>
            <div class="navbar-nav ms-auto">
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user me-1"></i>{{ auth()->user()->username ?? 'Admin' }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('admin.cv-list') }}"><i class="fas fa-list me-2"></i>Manage CVs</a></li>
                        <li><a class="dropdown-item" href="{{ route('cv.create') }}"><i class="fas fa-plus me-2"></i>Create CV</a></li>
                        <li><a class="dropdown-item" href="{{ route('admin.user-list') }}"><i class="fas fa-users me-2"></i>Manage Users</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ route('home') }}"><i class="fas fa-home me-2"></i>Public Homepage</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
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

    <div class="container py-4">
        <!-- Welcome Message -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="alert alert-primary" role="alert">
                    <h4 class="alert-heading">
                        <i class="fas fa-tachometer-alt me-2"></i>Welcome to Admin Dashboard
                    </h4>
                    <p class="mb-0">Manage your CV management system with comprehensive statistics and quick actions.</p>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card stat-card text-center">
                    <div class="card-body">
                        <i class="fas fa-file-alt stat-icon text-primary"></i>
                        <div class="stat-number text-primary">{{ $stats['total_cvs'] }}</div>
                        <h6 class="card-title">Total CVs</h6>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stat-card text-center">
                    <div class="card-body">
                        <i class="fas fa-users stat-icon text-success"></i>
                        <div class="stat-number text-success">{{ $stats['total_users'] }}</div>
                        <h6 class="card-title">Total Users</h6>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stat-card text-center">
                    <div class="card-body">
                        <i class="fas fa-eye stat-icon text-info"></i>
                        <div class="stat-number text-info">{{ collect($stats['by_template'])->sum() }}</div>
                        <h6 class="card-title">Published CVs</h6>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stat-card text-center">
                    <div class="card-body">
                        <i class="fas fa-chart-line stat-icon text-warning"></i>
                        <div class="stat-number text-warning">{{ $stats['recent_cvs']->count() }}</div>
                        <h6 class="card-title">Recent CVs</h6>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row mb-4">
            <!-- CVs by Template -->
            <div class="col-md-6 mb-3">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>CVs by Template</h5>
                    </div>
                    <div class="card-body">
                        @if(!empty($stats['by_template']))
                            @foreach($stats['by_template'] as $template => $count)
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge bg-primary template-badge">{{ ucfirst($template) }}</span>
                                    <span class="fw-bold">{{ $count }}</span>
                                </div>
                                <div class="progress mb-3" style="height: 8px;">
                                    <div class="progress-bar" role="progressbar" 
                                         style="width: {{ ($count / collect($stats['by_template'])->max()) * 100 }}%"></div>
                                </div>
                            @endforeach
                        @else
                            <p class="text-muted">No CVs found</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Users by Role -->
            <div class="col-md-6 mb-3">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-users me-2"></i>Users by Role</h5>
                    </div>
                    <div class="card-body">
                        @if(!empty($stats['by_role']))
                            @foreach($stats['by_role'] as $role => $count)
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge bg-{{ $role === 'admin' ? 'danger' : 'secondary' }} template-badge">
                                        {{ ucfirst($role) }}
                                    </span>
                                    <span class="fw-bold">{{ $count }}</span>
                                </div>
                                <div class="progress mb-3" style="height: 8px;">
                                    <div class="progress-bar bg-{{ $role === 'admin' ? 'danger' : 'secondary' }}" 
                                         role="progressbar" 
                                         style="width: {{ ($count / collect($stats['by_role'])->max()) * 100 }}%"></div>
                                </div>
                            @endforeach
                        @else
                            <p class="text-muted">No users found</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent CVs -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-clock me-2"></i>Recent CVs</h5>
                    </div>
                    <div class="card-body">
                        @if($stats['recent_cvs']->count() > 0)
                            @foreach($stats['recent_cvs'] as $cv)
                                <div class="recent-cv-item">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1">{{ $cv->name }}</h6>
                                            <p class="text-muted mb-1">{{ Str::limit($cv->profile_summary, 100) }}</p>
                                            <small class="text-muted">
                                                <i class="fas fa-calendar me-1"></i>
                                                {{ $cv->created_at->format('M d, Y H:i') }}
                                            </small>
                                        </div>
                                        <div>
                                            <span class="badge bg-primary template-badge">{{ ucfirst($cv->template_type) }}</span>
                                            <a href="{{ route('cv.show', $cv->id) }}" class="btn btn-sm btn-outline-primary ms-2">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="text-muted">No recent CVs found</p>
                        @endif
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
                                <a href="{{ route('admin.cv-list') }}" class="btn btn-primary w-100 quick-action-btn">
                                    <i class="fas fa-list me-2"></i>Manage All CVs
                                </a>
                            </div>
                            <div class="col-md-3 mb-2">
                                <a href="{{ route('cv.create') }}" class="btn btn-success w-100 quick-action-btn">
                                    <i class="fas fa-plus me-2"></i>Create New CV
                                </a>
                            </div>
                            <div class="col-md-3 mb-2">
                                <a href="{{ route('admin.user-list') }}" class="btn btn-info w-100 quick-action-btn">
                                    <i class="fas fa-users me-2"></i>Manage Users
                                </a>
                            </div>
                            <div class="col-md-3 mb-2">
                                <a href="{{ route('home') }}" class="btn btn-outline-primary w-100 quick-action-btn">
                                    <i class="fas fa-home me-2"></i>View Public Site
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
