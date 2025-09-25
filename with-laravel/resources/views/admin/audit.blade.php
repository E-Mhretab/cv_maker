<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Audit Logs - Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .audit-card {
            border-left: 4px solid #007bff;
        }
        .audit-card.create { border-left-color: #28a745; }
        .audit-card.update { border-left-color: #ffc107; }
        .audit-card.delete { border-left-color: #dc3545; }
        .audit-card.login { border-left-color: #17a2b8; }
        .audit-card.logout { border-left-color: #6c757d; }
        .audit-card.view { border-left-color: #007bff; }
        .audit-card.export { border-left-color: #20c997; }
        .audit-card.print { border-left-color: #343a40; }
        
        .badge-action {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }
        
        .json-display {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            padding: 0.5rem;
            font-family: 'Courier New', monospace;
            font-size: 0.875rem;
            max-height: 200px;
            overflow-y: auto;
        }
        
        .filter-section {
            background-color: #f8f9fa;
            border-radius: 0.5rem;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .stats-card.success {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        }
        
        .stats-card.warning {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        
        .stats-card.info {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }
        
        /* Custom Pagination Styling */
        .pagination {
            margin: 0;
            gap: 0.25rem;
        }
        
        .pagination .page-link {
            border-radius: 0.375rem;
            border: 1px solid #dee2e6;
            color: #0d6efd;
            padding: 0.5rem 0.75rem;
            margin: 0 0.125rem;
        }
        
        .pagination .page-link:hover {
            background-color: #e9ecef;
            border-color: #dee2e6;
        }
        
        .pagination .page-item.active .page-link {
            background-color: #0d6efd;
            border-color: #0d6efd;
            color: white;
        }
        
        .pagination .page-item.disabled .page-link {
            color: #6c757d;
            background-color: #fff;
            border-color: #dee2e6;
        }
        
        .pagination .page-item:first-child .page-link,
        .pagination .page-item:last-child .page-link {
            border-radius: 0.375rem;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container-fluid py-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h3 mb-0"><i class="fas fa-clipboard-list me-2"></i>Audit Logs</h1>
                        <p class="text-muted mb-0">Track all system activities and changes</p>
                    </div>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Back to Dashboard
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card stats-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h6 class="card-title text-white-50 mb-1">Total Logs</h6>
                                <h3 class="mb-0">{{ number_format($stats['total']) }}</h3>
                            </div>
                            <div class="fs-1 opacity-50">
                                <i class="fas fa-list"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stats-card success">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h6 class="card-title text-white-50 mb-1">Today</h6>
                                <h3 class="mb-0">{{ number_format($stats['today']) }}</h3>
                            </div>
                            <div class="fs-1 opacity-50">
                                <i class="fas fa-calendar-day"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stats-card warning">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h6 class="card-title text-white-50 mb-1">This Week</h6>
                                <h3 class="mb-0">{{ number_format($stats['this_week']) }}</h3>
                            </div>
                            <div class="fs-1 opacity-50">
                                <i class="fas fa-calendar-week"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card stats-card info">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h6 class="card-title text-white-50 mb-1">This Month</h6>
                                <h3 class="mb-0">{{ number_format($stats['this_month']) }}</h3>
                            </div>
                            <div class="fs-1 opacity-50">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="filter-section">
            <form method="GET" action="{{ route('admin.audit') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="search" class="form-label">Search</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               value="{{ request('search') }}" placeholder="Search logs...">
                    </div>
                    <div class="col-md-2">
                        <label for="action" class="form-label">Action</label>
                        <select class="form-select" id="action" name="action">
                            <option value="">All Actions</option>
                            @foreach($actions as $action)
                                <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                                    {{ ucfirst($action) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="table" class="form-label">Table</label>
                        <select class="form-select" id="table" name="table">
                            <option value="">All Tables</option>
                            @foreach($tables as $table)
                                <option value="{{ $table }}" {{ request('table') == $table ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace('_', ' ', $table)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="user_id" class="form-label">User</label>
                        <select class="form-select" id="user_id" name="user_id">
                            <option value="">All Users</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->username }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-1">
                        <label for="date_from" class="form-label">From</label>
                        <input type="date" class="form-control" id="date_from" name="date_from" 
                               value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-1">
                        <label for="date_to" class="form-label">To</label>
                        <input type="date" class="form-control" id="date_to" name="date_to" 
                               value="{{ request('date_to') }}">
                    </div>
                    <div class="col-md-1">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i>
                            </button>
                            <a href="{{ route('admin.audit') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Audit Logs Table -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-history me-2"></i>Audit Logs</h5>
                <div class="d-flex gap-2">
                    <span class="badge bg-primary">Total: {{ $stats['total'] }}</span>
                    <span class="badge bg-success">Found: {{ $auditLogs->count() }}</span>
                </div>
            </div>
            <div class="card-body p-0">
                @if($auditLogs->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th style="width: 5%;">ID</th>
                                    <th style="width: 15%;">User</th>
                                    <th style="width: 12%;">Action</th>
                                    <th style="width: 12%;">Table</th>
                                    <th style="width: 8%;">Record ID</th>
                                    <th style="width: 15%;">Timestamp</th>
                                    <th style="width: 12%;">IP Address</th>
                                    <th style="width: 8%;">Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($auditLogs as $index => $log)
                                    <tr class="{{ $index % 2 == 0 ? 'table-light' : '' }}">
                                        <td class="fw-bold">{{ $log->id }}</td>
                                        <td>
                                            @if($log->user)
                                                <span class="badge bg-info">{{ $log->user->username }}</span>
                                            @else
                                                <span class="text-muted">Unknown</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $log->action_color }}">
                                                {{ strtoupper($log->action) }}
                                            </span>
                                        </td>
                                        <td>{{ $log->table_name }}</td>
                                        <td>{{ $log->record_id ?? 'N/A' }}</td>
                                        <td>
                                            <div>{{ $log->timestamp->format('Y-m-d H:i:s') }}</div>
                                            <small class="text-muted">{{ $log->timestamp->diffForHumans() }}</small>
                                        </td>
                                        <td>
                                            <code class="text-primary">{{ $log->ip_address ?? 'N/A' }}</code>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-primary" type="button" 
                                                    data-bs-toggle="modal" data-bs-target="#logDetails{{ $log->id }}">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center p-3 border-top">
                        <div class="text-muted">
                            Showing {{ $auditLogs->firstItem() ?? 0 }} to {{ $auditLogs->lastItem() ?? 0 }} of {{ $auditLogs->total() }} results
                        </div>
                        <div>
                            {{ $auditLogs->appends(request()->query())->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No audit logs found</h5>
                        <p class="text-muted">No logs match your current filters.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Log Details Modals -->
        @foreach($auditLogs as $log)
            <div class="modal fade" id="logDetails{{ $log->id }}" tabindex="-1" aria-labelledby="logDetails{{ $log->id }}Label" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="logDetails{{ $log->id }}Label">
                                Audit Log Details - ID: {{ $log->id }}
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6>Basic Information</h6>
                                    <table class="table table-sm">
                                        <tr>
                                            <td><strong>ID:</strong></td>
                                            <td>{{ $log->id }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>User:</strong></td>
                                            <td>
                                                @if($log->user)
                                                    {{ $log->user->username }} ({{ $log->user->email }})
                                                @else
                                                    <span class="text-muted">Unknown User</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Action:</strong></td>
                                            <td>
                                                <span class="badge bg-{{ $log->action_color }}">
                                                    {{ strtoupper($log->action) }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Table:</strong></td>
                                            <td>{{ $log->table_name }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Record ID:</strong></td>
                                            <td>{{ $log->record_id ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Timestamp:</strong></td>
                                            <td>{{ $log->timestamp->format('Y-m-d H:i:s') }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>IP Address:</strong></td>
                                            <td><code>{{ $log->ip_address ?? 'N/A' }}</code></td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <h6>Change Details</h6>
                                    @if($log->old_values || $log->new_values)
                                        @if($log->old_values)
                                            <div class="mb-3">
                                                <strong>Old Values:</strong>
                                                <div class="json-display">
                                                    <pre>{{ $log->formatted_old_values }}</pre>
                                                </div>
                                            </div>
                                        @endif
                                        @if($log->new_values)
                                            <div class="mb-3">
                                                <strong>New Values:</strong>
                                                <div class="json-display">
                                                    <pre>{{ $log->formatted_new_values }}</pre>
                                                </div>
                                            </div>
                                        @endif
                                    @else
                                        <p class="text-muted">No change details available.</p>
                                    @endif
                                    
                                    @if($log->user_agent)
                                        <div class="mt-3">
                                            <strong>User Agent:</strong>
                                            <div class="bg-light p-2 rounded">
                                                <small>{{ $log->user_agent }}</small>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>