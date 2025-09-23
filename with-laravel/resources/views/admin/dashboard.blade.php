<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - CV Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: #f8f9fa;
        }
        .stat-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-2px);
        }
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
        }
        .stat-icon.primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .stat-icon.success { background: linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%); }
        .stat-icon.warning { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
        .stat-icon.info { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
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
                        <i class="fas fa-user me-1"></i>{{ Auth::user()->username ?? 'Admin' }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('cvs.index') }}"><i class="fas fa-list me-2"></i>Manage CVs</a></li>
                        <li><a class="dropdown-item" href="{{ route('cvs.create') }}"><i class="fas fa-plus me-2"></i>Create CV</a></li>
                        <li><a class="dropdown-item" href="{{ route('admin.audit') }}"><i class="fas fa-clipboard-list me-2"></i>Audit Logs</a></li>
                        <li><a class="dropdown-item" href="{{ route('admin.sessions') }}"><i class="fas fa-shield-alt me-2"></i>Session Management</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ route('welcome') }}"><i class="fas fa-home me-2"></i>Public Homepage</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}" class="d-inline">
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
                            <h3 class="mb-0">{{ $stats['regular_users'] }}</h3>
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
                            <h3 class="mb-0">{{ $stats['admins'] }}</h3>
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
                                <a href="{{ route('cvs.index') }}" class="btn btn-primary w-100">
                                    <i class="fas fa-list me-2"></i>Manage All CVs
                                </a>
                            </div>
                            <div class="col-md-3 mb-2">
                                <a href="{{ route('cvs.create') }}" class="btn btn-success w-100">
                                    <i class="fas fa-plus me-2"></i>Create New CV
                                </a>
                            </div>
                            <div class="col-md-3 mb-2">
                                <a href="{{ route('admin.audit') }}" class="btn btn-info w-100">
                                    <i class="fas fa-clipboard-list me-2"></i>Audit Logs
                                </a>
                            </div>
                            <div class="col-md-3 mb-2">
                                <a href="{{ route('admin.audit') }}" class="btn btn-outline-info w-100">
                                    <i class="fas fa-bug me-2"></i>Debug Audit
                                </a>
                            </div>
                            <div class="col-md-3 mb-2">
                                <a href="{{ route('admin.sessions') }}" class="btn btn-warning w-100">
                                    <i class="fas fa-shield-alt me-2"></i>Session Management
                                </a>
                            </div>
                            <div class="col-md-3 mb-2">
                                <a href="{{ route('admin.sessions') }}" class="btn btn-outline-warning w-100">
                                    <i class="fas fa-bug me-2"></i>Debug Sessions
                                </a>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-3 mb-2">
                                <a href="{{ route('welcome') }}" class="btn btn-outline-primary w-100">
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
                        @if($recentCvs->isEmpty())
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
                                        @foreach($recentCvs as $cv)
                                            <tr>
                                                <td>{{ $cv->name }}</td>
                                                <td>
                                                    <span class="badge bg-secondary">
                                                        @php
                                                            $templates = [
                                                                0 => 'Default Template',
                                                                1 => 'Esey Template',
                                                                2 => 'Nathan Template',
                                                                3 => 'Mirian Template',
                                                            ];
                                                            echo $templates[$cv->template_type] ?? 'Unknown Template';
                                                        @endphp
                                                    </span>
                                                </td>
                                                <td>
                                                    @if($cv->metadata_created_at)
                                                        {{ $cv->metadata_created_at instanceof \Carbon\Carbon ? $cv->metadata_created_at->format('M j, Y') : \Carbon\Carbon::parse($cv->metadata_created_at)->format('M j, Y') }}
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('cvs.show', $cv->id) }}" class="btn btn-sm btn-outline-primary">
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
                        @if(empty($cvsByTemplate))
                            <p class="text-muted">No data available.</p>
                        @else
                            @foreach($cvsByTemplate as $template => $count)
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span>
                                        @php
                                            $templates = [
                                                0 => 'Default Template',
                                                1 => 'Esey Template',
                                                2 => 'Nathan Template',
                                                3 => 'Mirian Template',
                                            ];
                                            echo $templates[$template] ?? 'Unknown Template';
                                        @endphp
                                    </span>
                                    <span class="badge bg-primary">{{ $count }}</span>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
