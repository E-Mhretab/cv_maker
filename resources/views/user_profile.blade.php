
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - CV Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #f8f9fa; }
        .profile-card { background: white; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
        .cv-card { transition: all 0.3s ease; border: 1px solid #dee2e6; }
        .cv-card:hover { transform: translateY(-2px); box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .stats-card { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 15px; padding: 2rem; text-align: center; }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark bg-primary">
        <div class="container">
            <span class="navbar-brand mb-0 h1">
                <i class="fas fa-user me-2"></i>My Profile
            </span>
            <div class="navbar-nav ms-auto">
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user me-1"></i>{{ $user->username }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ url('manage/cvs') }}"><i class="fas fa-list me-2"></i>Manage CVs</a></li>
                        <li><a class="dropdown-item" href="{{ route('manage.cvs.create') }}"><i class="fas fa-plus me-2"></i>Create CV</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ url('/') }}"><i class="fas fa-home me-2"></i>Homepage</a></li>
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

    <div class="container py-4">
        <!-- Profile Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="profile-card p-4">
                    <div class="row align-items-center">
                        <div class="col-md-2 text-center">
                            <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                <i class="fas fa-user fa-2x text-white"></i>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <h1 class="h3 mb-1">{{ $user->username }}</h1>
                            <p class="text-muted mb-1">{{ $user->email }}</p>
                            <span class="badge bg-primary">{{ ucfirst($user->role) }}</span>
                        </div>
                        <div class="col-md-2 text-end">
                            <a href="{{ route('manage.cvs.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Create CV
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="stats-card">
                    <h3 class="h2 mb-1">{{ $cvs->count() }}</h3>
                    <p class="mb-0">Total CVs</p>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stats-card">
                    <h3 class="h2 mb-1">{{ $cvs->where('is_public', 1)->count() }}</h3>
                    <p class="mb-0">Public CVs</p>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stats-card">
                    <h3 class="h2 mb-1">{{ $cvs->where('is_public', 0)->count() }}</h3>
                    <p class="mb-0">Private CVs</p>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stats-card">
                    <h3 class="h2 mb-1">{{ $cvs->pluck('template_type')->unique()->count() }}</h3>
                    <p class="mb-0">Templates Used</p>
                </div>
            </div>
        </div>

        <!-- CVs Section -->
        <div class="row">
            <div class="col-12">
                <div class="profile-card p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2 class="h4 mb-0">
                            <i class="fas fa-file-alt me-2"></i>My CVs
                        </h2>
                        <a href="{{ route('manage.cvs.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Create New CV
                        </a>
                    </div>

                    @if ($cvs->isEmpty())
                        <div class="text-center py-5">
                            <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                            <h4 class="text-muted">No CVs Found</h4>
                            <p class="text-muted">You haven't created any CVs yet. Create your first CV to get started!</p>
                            <a href="{{ route('manage.cvs.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Create Your First CV
                            </a>
                        </div>
                    @else
                        <div class="row">
                            @foreach ($cvs as $cv)
                                <div class="col-lg-6 col-xl-4 mb-4">
                                    <div class="cv-card card h-100">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-3">
                                                <h5 class="card-title mb-0">{{ $cv->name }}</h5>
                                                <div class="d-flex flex-column align-items-end">
                                                    <span class="badge bg-{{ $cv->template_type == 1 ? 'primary' : 'success' }} mb-1">
                                                        {{ $cv->template_type == 1 ? 'Nathan' : ($cv->template_type == 2 ? 'Esey' : 'Mirian') }}
                                                    </span>
                                                    @if ($cv->is_public)
                                                        <span class="badge bg-success" style="font-size: 0.7rem;">
                                                            <i class="fas fa-globe me-1"></i>Public
                                                        </span>
                                                    @else
                                                        <span class="badge bg-secondary" style="font-size: 0.7rem;">
                                                            <i class="fas fa-lock me-1"></i>Private
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <p class="card-text text-muted small mb-3">
                                                {{ Str::limit($cv->profile_summary, 100) }}
                                            </p>
                                            <div class="text-muted small mb-3">
                                                <i class="fas fa-calendar me-1"></i>
                                                Created: {{ $cv->created_at ? 
                                                    		date('M j, Y', strtotime($cv->created_at)) : 'Date not available' }}
                                                @if ($cv->published_at)
                                                    <br><i class="fas fa-globe me-1"></i>
                                                    Published: {{ date('M j, Y', strtotime($cv->published_at)) }}
                                                @endif
                                            </div>
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('manage.cvs.show', $cv->id) }}" class="btn btn-outline-primary btn-sm">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('manage.cvs.edit', $cv->id) }}" class="btn btn-outline-warning btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @if ($cv->is_public)
                                                    <a href="#" class="btn btn-outline-secondary btn-sm" title="Make Private">
                                                        <i class="fas fa-lock"></i>
                                                    </a>
                                                @else
                                                    <a href="#" class="btn btn-outline-success btn-sm" title="Make Public">
                                                        <i class="fas fa-globe"></i>
                                                    </a>
                                                @endif
                                                <form action="{{ route('manage.cvs.destroy', $cv->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm" title="Delete" onclick="return confirm('Are you sure you want to delete this CV?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
