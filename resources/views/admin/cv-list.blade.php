<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CV Management - List All CVs</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .cv-card {
            transition: all 0.3s ease;
            border: 1px solid #dee2e6;
        }
        .cv-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .template-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }
        .action-buttons .btn {
            margin: 0 2px;
        }
        .search-section {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .status-badge {
            font-size: 0.7rem;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-dark bg-primary">
        <div class="container">
            <span class="navbar-brand mb-0 h1">
                <i class="fas fa-list me-2"></i>CV Management
            </span>
            <div class="navbar-nav ms-auto">
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user me-1"></i>{{ auth()->user()->username ?? 'Admin' }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</a></li>
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
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <h2><i class="fas fa-file-alt me-2"></i>All CVs</h2>
                    <a href="{{ route('cv.create') }}" class="btn btn-success">
                        <i class="fas fa-plus me-2"></i>Create New CV
                    </a>
                </div>
            </div>
        </div>

        <!-- Search and Filter Section -->
        <div class="search-section">
            <form method="GET" action="{{ route('admin.cv-list') }}">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="search" class="form-label">Search CVs</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" class="form-control" id="search" name="search" 
                                   value="{{ $search }}" placeholder="Search by name or summary...">
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="template" class="form-label">Filter by Template</label>
                        <select class="form-select" id="template" name="template">
                            <option value="">All Templates</option>
                            @foreach($templateOptions as $template)
                                <option value="{{ $template }}" {{ $templateFilter === $template ? 'selected' : '' }}>
                                    {{ ucfirst($template) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 mb-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-search me-1"></i>Search
                        </button>
                        <a href="{{ route('admin.cv-list') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i>Clear
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- CVs Grid -->
        @if($cvs->count() > 0)
            <div class="row">
                @foreach($cvs as $cv)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card cv-card h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="card-title">{{ $cv->name }}</h5>
                                    <div>
                                        <span class="badge bg-primary template-badge">{{ ucfirst($cv->template_type) }}</span>
                                        @if($cv->is_public)
                                            <span class="badge bg-success status-badge">Public</span>
                                        @else
                                            <span class="badge bg-warning status-badge">Private</span>
                                        @endif
                                    </div>
                                </div>
                                
                                <p class="card-text text-muted">
                                    {{ Str::limit($cv->profile_summary, 120) }}
                                </p>
                                
                                <div class="mb-3">
                                    <small class="text-muted">
                                        <i class="fas fa-user me-1"></i>
                                        @if($cv->user_id)
                                            User ID: {{ $cv->user_id }}
                                        @else
                                            Guest ({{ $cv->email }})
                                        @endif
                                    </small><br>
                                    <small class="text-muted">
                                        <i class="fas fa-calendar me-1"></i>
                                        Created: {{ $cv->created_at->format('M d, Y H:i') }}
                                    </small>
                                    @if($cv->published_at)
                                        <br><small class="text-muted">
                                            <i class="fas fa-eye me-1"></i>
                                            Published: {{ \Carbon\Carbon::parse($cv->published_at)->format('M d, Y H:i') }}
                                        </small>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="card-footer bg-transparent">
                                <div class="action-buttons d-flex justify-content-between">
                                    <div>
                                        <a href="{{ route('cv.show', $cv->id) }}" class="btn btn-sm btn-outline-primary" 
                                           title="View CV">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('cv.edit', $cv->id) }}" class="btn btn-sm btn-outline-warning" 
                                           title="Edit CV">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                    <div>
                                        @if($cv->is_public)
                                            <form method="POST" action="{{ route('cv.unpublish', $cv->id) }}" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-warning" 
                                                        title="Unpublish CV">
                                                    <i class="fas fa-eye-slash"></i>
                                                </button>
                                            </form>
                                        @else
                                            <form method="POST" action="{{ route('cv.publish', $cv->id) }}" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-outline-success" 
                                                        title="Publish CV">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </form>
                                        @endif
                                        <form method="POST" action="{{ route('cv.destroy', $cv->id) }}" class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to delete this CV?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                    title="Delete CV">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="row">
                <div class="col-12">
                    {{ $cvs->appends(request()->query())->links() }}
                </div>
            </div>
        @else
            <div class="row">
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                        <h4 class="text-muted">No CVs Found</h4>
                        <p class="text-muted">
                            @if($search || $templateFilter)
                                No CVs match your search criteria. Try adjusting your filters.
                            @else
                                No CVs have been created yet. Create your first CV!
                            @endif
                        </p>
                        <a href="{{ route('cv.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Create First CV
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
