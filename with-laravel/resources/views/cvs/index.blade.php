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
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
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
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 10px;
        }
    </style>
</head>
<body class="bg-light">
    <!-- Fixed Navigation Header -->
    <nav class="navbar navbar-dark bg-primary fixed-top" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important; box-shadow: 0 2px 20px rgba(0,0,0,0.15);">
        <div class="container">
            <a href="{{ route('welcome') }}" class="navbar-brand fw-bold">
                <i class="fas fa-arrow-left me-2"></i>Back to Home
            </a>
            @if(Auth::user()->role === 'admin')
            <a href="{{ route('admin.dashboard') }}" class="navbar-brand fw-bold">
                <i class="fas fa-tachometer-alt me-2"></i>Admin Dashboard
            </a>
            @endif
            <span class="navbar-text fw-semibold">
                <i class="fas fa-cogs me-2"></i>CV Management
            </span>
            
            <!-- User Menu -->
            <div class="navbar-nav ms-auto">
                @auth
                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user me-1"></i>{{ auth()->user()->name }}
                            <span class="badge bg-light text-dark ms-1">{{ ucfirst(auth()->user()->role ?? 'User') }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('cvs.create') }}"><i class="fas fa-plus me-2"></i>Create CV</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item"><i class="fas fa-sign-out-alt me-2"></i>Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="nav-link">
                        <i class="fas fa-sign-in-alt me-1"></i>Login
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <div class="container my-5" style="padding-top: 120px;">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="h2 mb-3">
                    <i class="fas fa-list me-2"></i>CV Management
                </h1>
                <p class="text-muted">Manage all CVs in the system</p>
            </div>
        </div>

        <!-- Messages -->
        @if (session('message'))
            <div class="row mb-4">
                <div class="col-12">
                    @if (session('type') === 'success')
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('message') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @else
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>{{ session('message') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Statistics -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="stats-card p-3 text-center">
                    <h3 class="h4 mb-1">{{ isset($stats) ? $stats['total_cvs'] : $cvs->count() }}</h3>
                    <p class="mb-0">Total CVs</p>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stats-card p-3 text-center">
                    <h3 class="h4 mb-1">{{ isset($stats) ? $stats['nathan_templates'] : $cvs->where('metadata.template_type', 2)->count() }}</h3>
                    <p class="mb-0">Nathan Templates</p>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stats-card p-3 text-center">
                    <h3 class="h4 mb-1">{{ isset($stats) ? $stats['esey_templates'] : $cvs->where('metadata.template_type', 1)->count() }}</h3>
                    <p class="mb-0">Esey Templates</p>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stats-card p-3 text-center">
                    <h3 class="h4 mb-1">{{ isset($stats) ? $stats['created_today'] : $cvs->where('created_at', '>=', today())->count() }}</h3>
                    <p class="mb-0">Created Today</p>
                </div>
            </div>
        </div>

        <!-- Search and Filter -->
        <div class="search-section">
            <form method="GET" class="row g-3">
                <div class="col-md-6">
                    <label for="search" class="form-label">Search CVs</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" class="form-control" id="search" name="search" 
                               value="{{ request('search') }}" 
                               placeholder="Search by name or summary...">
                    </div>
                </div>
                <div class="col-md-4">
                    <label for="template_type" class="form-label">Filter by Template</label>
                    <select class="form-select" id="template_type" name="template_type">
                        <option value="">All Templates</option>
                        <option value="1" {{ request('template_type') == '1' ? 'selected' : '' }}>Esey Template</option>
                        <option value="2" {{ request('template_type') == '2' ? 'selected' : '' }}>Nathan Template</option>
                        <option value="3" {{ request('template_type') == '3' ? 'selected' : '' }}>Mirian Template</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter me-1"></i>Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- CV List -->
        <div class="row">
            @if($cvs->count() === 0)
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        <i class="fas fa-info-circle fa-2x mb-3"></i>
                        <h4>No CVs Found</h4>
                        <p>No CVs match your search criteria.</p>
                        <a href="{{ route('cvs.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Create New CV
                        </a>
                    </div>
                </div>
            @else
                @foreach($cvs as $index => $cv)
                <div class="col-lg-6 col-xl-4 mb-4 cv-item {{ $index >= 12 ? 'd-none' : '' }}" data-index="{{ $index }}">
                    <div class="cv-card card h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h5 class="card-title mb-0">{{ $cv->name }}</h5>
                                <div class="d-flex flex-column align-items-end">
                                    <span class="badge template-badge bg-{{ $cv->metadata && $cv->metadata->template_type == 2 ? 'primary' : 'success' }} mb-1">
                                        @php
                                            $templates = [
                                                1 => 'Esey Template',
                                                2 => 'Nathan Template', 
                                                3 => 'Mirian Template',
                                            ];
                                            echo $templates[$cv->metadata->template_type] ?? 'Unknown Template';
                                        @endphp
                                    </span>
                                    @if($cv->metadata && $cv->metadata->is_public)
                                        <span class="badge bg-success" style="font-size: 0.7rem;">
                                            <i class="fas fa-globe me-1"></i>Public
                                        </span>
                                    @else
                                        <span class="badge bg-secondary" style="font-size: 0.7rem;">
                                            <i class="fas fa-lock me-1"></i>Private
                                        </span>
                                    @endif
                                    @if($cv->user_id)
                                        <span class="badge bg-info" style="font-size: 0.7rem;">
                                            <i class="fas fa-user me-1"></i>Owned
                                        </span>
                                    @else
                                        <span class="badge bg-warning" style="font-size: 0.7rem;">
                                            <i class="fas fa-hand-holding-heart me-1"></i>Guest
                                        </span>
                                    @endif
                                </div>
                            </div>
                            
                            <p class="card-text text-muted small mb-3">
                                {{ Str::limit($cv->profile_summary, 100) }}
                            </p>
                            
                            <div class="text-muted small mb-3">
                                <i class="fas fa-calendar me-1"></i>
                                Created: {{ $cv->metadata && $cv->metadata->published_at ? $cv->metadata->published_at->format('M j, Y') : 'Date not available' }}
                                @if(Auth::user()->role === 'admin' && $cv->user)
                                    <br><i class="fas fa-user me-1"></i>
                                    Owner: {{ $cv->user->name }} ({{ $cv->user->email }})
                                @endif
                            </div>
                            
                            <div class="action-buttons">
                                <a href="{{ route('cvs.preview', $cv) }}" 
                                   class="btn btn-outline-primary btn-sm" title="Preview">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('cvs.edit', $cv) }}" 
                                   class="btn btn-outline-warning btn-sm" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('cvs.send-email', $cv) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-success btn-sm" title="Send to Me"
                                            onclick="return confirm('Weet u zeker dat u uw CV naar uw e-mailadres wilt sturen?')">
                                        <i class="fas fa-envelope"></i>
                                    </button>
                                </form>
                                <a href="{{ route('cvs.show', $cv) }}" class="btn btn-outline-primary btn-sm" title="Send to Others">
                                    <i class="fas fa-paper-plane"></i>
                                </a>
                                @if($cv->metadata && $cv->metadata->is_public)
                                    <form method="POST" action="{{ route('cvs.publish', $cv) }}?action=unpublish" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-secondary btn-sm" title="Make Private"
                                                onclick="return confirm('Are you sure you want to make this CV private?')">
                                            <i class="fas fa-lock"></i>
                                        </button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('cvs.publish', $cv) }}?action=publish" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-success btn-sm" title="Make Public"
                                                onclick="return confirm('Are you sure you want to make this CV public? It will be visible to everyone.')">
                                            <i class="fas fa-globe"></i>
                                        </button>
                                    </form>
                                @endif
                                <form method="POST" action="{{ route('cvs.destroy', $cv) }}" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm" title="Delete"
                                            onclick="return confirm('Are you sure you want to delete this CV?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @endif
        </div>

        <!-- Load More Button -->
        @if($cvs->count() > 12)
        <div class="row mt-4">
            <div class="col-12 text-center">
                <button type="button" class="btn btn-primary btn-lg" id="loadMoreBtn" onclick="loadMoreCVs()">
                    <i class="fas fa-plus me-2"></i>Load More CVs
                </button>
                <div class="mt-2">
                    <small class="text-muted">
                        Showing <span id="showingCount">12</span> of {{ $cvs->count() }} CVs
                    </small>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let currentlyShowing = 12;
        const totalCVs = {{ $cvs->count() }};
        
        function loadMoreCVs() {
            const hiddenCVs = document.querySelectorAll('.cv-item.d-none');
            const loadMoreBtn = document.getElementById('loadMoreBtn');
            const showingCount = document.getElementById('showingCount');
            
            // Show next 12 CVs (or remaining if less than 12)
            const nextBatch = Array.from(hiddenCVs).slice(0, 12);
            nextBatch.forEach(cv => {
                cv.classList.remove('d-none');
                cv.classList.add('fade-in');
            });
            
            currentlyShowing += nextBatch.length;
            showingCount.textContent = currentlyShowing;
            
            // Hide button if all CVs are shown
            if (currentlyShowing >= totalCVs) {
                loadMoreBtn.style.display = 'none';
            }
        }
        
        // Reset load more functionality when page loads (for filtered results)
        document.addEventListener('DOMContentLoaded', function() {
            const totalCVs = {{ $cvs->count() }};
            const showingCount = document.getElementById('showingCount');
            const loadMoreBtn = document.getElementById('loadMoreBtn');
            
            // Update the counter with actual filtered count
            if (showingCount) {
                showingCount.textContent = Math.min(12, totalCVs);
            }
            
            // Hide load more button if 12 or fewer CVs
            if (totalCVs <= 12 && loadMoreBtn) {
                loadMoreBtn.style.display = 'none';
            }
        });
        
        // Add CSS for fade-in animation
        const style = document.createElement('style');
        style.textContent = `
            .fade-in {
                animation: fadeIn 0.5s ease-in;
            }
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(20px); }
                to { opacity: 1; transform: translateY(0); }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>
