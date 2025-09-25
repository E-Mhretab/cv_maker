@extends('layouts.app')
@section('title', 'Audit Logs - Admin Panel')
@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-2 bg-dark text-white min-vh-100 p-3">
            <h5 class="text-center mb-4">Admin Panel</h5>
            <ul class="nav flex-column">
                <li class="nav-item mb-2">
                    <a class="nav-link text-white" href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a class="nav-link text-white active" href="{{ route('audit.logs') }}">
                        <i class="fas fa-clipboard-list me-2"></i>Audit Logs
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a class="nav-link text-white" href="{{ route('manage.cvs.index') }}">
                        <i class="fas fa-file-alt me-2"></i>CV Management
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a class="nav-link text-white" href="{{ route('logout') }}"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="col-md-10 p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-clipboard-list me-2"></i>Audit Logs</h2>
                <div>
                    <span class="badge bg-primary">Total: {{ $logs->total() }}</span>
                    <span class="badge bg-info">Found: {{ $logs->count() }}</span>
                    @if(request('debug'))
                        <span class="badge bg-warning">Debug Mode</span>
                    @endif
                </div>
            </div>

            @if(request('debug'))
            <div class="alert alert-info">
                <h5>Debug Information:</h5>
                <p><strong>Query:</strong> {{ $logs->toSql() ?? '' }}</p>
                <p><strong>Parameters:</strong> {{ json_encode(request()->all()) }}</p>
                <p><strong>Total Records:</strong> {{ $logs->total() }}</p>
                <p><strong>Current Page:</strong> {{ $logs->currentPage() }}</p>
                <p><strong>Per Page:</strong> {{ $logs->perPage() }}</p>
            </div>
            @endif

            <!-- Filters -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Filters</h5>
                </div>
                <div class="card-body">
                    <form method="GET" class="row g-3">
                        <div class="col-md-3">
                            <label for="user_id" class="form-label">User</label>
                            <select class="form-select" id="user_id" name="user_id">
                                <option value="">All Users</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" @if(request('user_id') == $user->id) selected @endif>
                                        {{ $user->username }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="action" class="form-label">Action</label>
                            <select class="form-select" id="action" name="action">
                                <option value="">All Actions</option>
                                @foreach($actions as $action)
                                    <option value="{{ $action }}" @if(request('action') == $action) selected @endif>{{ $action }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="table_name" class="form-label">Table</label>
                            <select class="form-select" id="table_name" name="table_name">
                                <option value="">All Tables</option>
                                @foreach($tables as $table)
                                    <option value="{{ $table }}" @if(request('table_name') == $table) selected @endif>{{ $table }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="fas fa-search me-1"></i>Filter
                            </button>
                            <a href="{{ route('audit.logs') }}" class="btn btn-outline-secondary me-2">
                                <i class="fas fa-times me-1"></i>Clear
                            </a>
                            <a href="{{ route('audit.logs', array_merge(request()->all(), ['debug' => 1])) }}" class="btn btn-outline-warning">
                                <i class="fas fa-bug me-1"></i>Debug
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Audit Logs Table -->
            <div class="card">
                <div class="card-body">
                    @if($logs->isEmpty())
                        <div class="text-center py-4">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No audit logs found</p>
                            @if($logs->total() > 0)
                                <p class="text-warning">Total records in database: {{ $logs->total() }}, but none match current filters.</p>
                            @else
                                <p class="text-info">No audit logs have been created yet. Try logging in/out o creando/editando un CV para generar logs.</p>
                            @endif
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>User</th>
                                        <th>Action</th>
                                        <th>Table</th>
                                        <th>Record ID</th>
                                        <th>Timestamp</th>
                                        <th>IP Address</th>
                                        <th>Details</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($logs as $log)
                                        <tr>
                                            <td>{{ $log->id }}</td>
                                            <td>
                                                <span class="badge bg-info">{{ $log->user->username ?? 'Unknown' }}</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{
                                                    strtoupper($log->action) === 'INSERT' || strtoupper($log->action) === 'CREATE' ? 'success' :
                                                    (strtoupper($log->action) === 'UPDATE' ? 'warning' :
                                                    (strtoupper($log->action) === 'DELETE' ? 'danger' :
                                                    (strtoupper($log->action) === 'LOGIN' ? 'info' :
                                                    (strtoupper($log->action) === 'LOGOUT' ? 'secondary' : 'primary'))))
                                                }}">
                                                    {{ $log->action }}
                                                </span>
                                            </td>
                                            <td>{{ $log->table_name }}</td>
                                            <td>{{ $log->record_id }}</td>
                                            <td>{{ $log->created_at }}</td>
                                            <td>{{ $log->ip_address }}</td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary" onclick="showDetails({{ htmlspecialchars(json_encode([
                                                    'id' => $log->id,
                                                    'username' => $log->user->username ?? 'Unknown',
                                                    'action' => $log->action,
                                                    'table_name' => $log->table_name,
                                                    'record_id' => $log->record_id,
                                                    'timestamp' => $log->created_at,
                                                    'ip_address' => $log->ip_address,
                                                    'old_values' => $log->old_values,
                                                    'new_values' => $log->new_values,
                                                ]), ENT_QUOTES, 'UTF-8') }})">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- Pagination -->
                        @if($logs->lastPage() > 1)
                            <nav aria-label="Audit logs pagination">
                                <ul class="pagination justify-content-center">
                                    @for($i = 1; $i <= $logs->lastPage(); $i++)
                                        <li class="page-item {{ $i == $logs->currentPage() ? 'active' : '' }}">
                                            <a class="page-link" href="{{ $logs->url($i) }}">{{ $i }}</a>
                                        </li>
                                    @endfor
                                </ul>
                            </nav>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Details Modal -->
<div class="modal fade" id="detailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Audit Log Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="detailsContent"></div>
            </div>
        </div>
    </div>
</div>

<script>
function showDetails(log) {
    let oldValues = log.old_values ? JSON.stringify(log.old_values, null, 2) : '';
    let newValues = log.new_values ? JSON.stringify(log.new_values, null, 2) : '';
    let content = `
        <div class="row">
            <div class="col-md-6">
                <h6>Basic Information</h6>
                <table class="table table-sm">
                    <tr><td><strong>ID:</strong></td><td>${log.id}</td></tr>
                    <tr><td><strong>User:</strong></td><td>${log.username}</td></tr>
                    <tr><td><strong>Action:</strong></td><td>${log.action}</td></tr>
                    <tr><td><strong>Table:</strong></td><td>${log.table_name}</td></tr>
                    <tr><td><strong>Record ID:</strong></td><td>${log.record_id}</td></tr>
                    <tr><td><strong>Timestamp:</strong></td><td>${log.timestamp}</td></tr>
                    <tr><td><strong>IP Address:</strong></td><td>${log.ip_address}</td></tr>
                </table>
            </div>
            <div class="col-md-6">
                <h6>Data Changes</h6>
                ${oldValues ? `<h6 class='text-danger'>Old Values:</h6><pre class='bg-light p-2'>${oldValues}</pre>` : ''}
                ${newValues ? `<h6 class='text-success'>New Values:</h6><pre class='bg-light p-2'>${newValues}</pre>` : ''}
            </div>
        </div>
    `;
    document.getElementById('detailsContent').innerHTML = content;
    new bootstrap.Modal(document.getElementById('detailsModal')).show();
}
</script>
@endsection
