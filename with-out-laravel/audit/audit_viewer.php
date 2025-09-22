<?php
/**
 * Audit Viewer - Admin interface for viewing audit logs
 */

// Start session first, before any output
session_start();

function getActionBadgeColor($action) {
    switch (strtoupper($action)) {
        case 'INSERT':
        case 'CREATE':
            return 'success';
        case 'UPDATE':
            return 'warning';
        case 'DELETE':
            return 'danger';
        case 'LOGIN':
            return 'info';
        case 'LOGOUT':
            return 'secondary';
        default:
            return 'primary';
    }
}

require_once '../connection.php';
require_once '../includes/auth.php';
require_once 'audit_logger.php';

// Create auth instance
$auth = new Auth($conn);

// Check if user is admin
if (!$auth->isLoggedIn() || !$auth->isAdmin()) {
    header('Location: ../login.php');
    exit;
}

$auditLogger = new AuditLogger($conn);

// Get filter parameters
$userId = $_GET['user_id'] ?? '';
$action = $_GET['action'] ?? '';
$tableName = $_GET['table_name'] ?? '';
$page = max(1, $_GET['page'] ?? 1);
$limit = 50;
$offset = ($page - 1) * $limit;

// Build query
$whereConditions = [];
$params = [];
$paramTypes = '';

if ($userId) {
    $whereConditions[] = "al.user_id = ?";
    $params[] = $userId;
    $paramTypes .= 'i';
}

if ($action) {
    $whereConditions[] = "al.action = ?";
    $params[] = $action;
    $paramTypes .= 's';
}

if ($tableName) {
    $whereConditions[] = "al.table_name = ?";
    $params[] = $tableName;
    $paramTypes .= 's';
}

$whereClause = $whereConditions ? 'WHERE ' . implode(' AND ', $whereConditions) : '';

// Get total count
$countSql = "SELECT COUNT(*) as total FROM audit_logs al $whereClause";
$countStmt = $conn->prepare($countSql);
if ($params) {
    $countStmt->bind_param($paramTypes, ...$params);
}
$countStmt->execute();
$totalRecords = $countStmt->get_result()->fetch_assoc()['total'];
$countStmt->close();

// Get audit logs
$sql = "
    SELECT al.*, u.username 
    FROM audit_logs al 
    LEFT JOIN users u ON al.user_id = u.id 
    $whereClause
    ORDER BY al.timestamp DESC 
    LIMIT ? OFFSET ?
";
$params[] = $limit;
$params[] = $offset;
$paramTypes .= 'ii';

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param($paramTypes, ...$params);
if (!$stmt->execute()) {
    die("Execute failed: " . $stmt->error);
}

$result = $stmt->get_result();
$auditLogs = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Get unique values for filters
$usersStmt = $conn->query("SELECT DISTINCT u.id, u.username FROM users u ORDER BY u.username");
$users = $usersStmt->fetch_all(MYSQLI_ASSOC);

$actionsStmt = $conn->query("SELECT DISTINCT action FROM audit_logs ORDER BY action");
$actions = $actionsStmt->fetch_all(MYSQLI_ASSOC);

$tablesStmt = $conn->query("SELECT DISTINCT table_name FROM audit_logs ORDER BY table_name");
$tables = $tablesStmt->fetch_all(MYSQLI_ASSOC);

$totalPages = ceil($totalRecords / $limit);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Audit Logs - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 bg-dark text-white min-vh-100 p-3">
                <h5 class="text-center mb-4">Admin Panel</h5>
                <ul class="nav flex-column">
                    <li class="nav-item mb-2">
                        <a class="nav-link text-white" href="../admin_dashboard.php">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a class="nav-link text-white active" href="audit_viewer.php">
                            <i class="fas fa-clipboard-list me-2"></i>Audit Logs
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a class="nav-link text-white" href="../manage/cv_list.php">
                            <i class="fas fa-file-alt me-2"></i>CV Management
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a class="nav-link text-white" href="../logout.php">
                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-clipboard-list me-2"></i>Audit Logs</h2>
                    <div>
                        <span class="badge bg-primary">Total: <?php echo $totalRecords; ?></span>
                        <span class="badge bg-info">Found: <?php echo count($auditLogs); ?></span>
                        <?php if (isset($_GET['debug'])): ?>
                            <span class="badge bg-warning">Debug Mode</span>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if (isset($_GET['debug'])): ?>
                <div class="alert alert-info">
                    <h5>Debug Information:</h5>
                    <p><strong>Query:</strong> <?php echo htmlspecialchars($sql); ?></p>
                    <p><strong>Parameters:</strong> <?php echo htmlspecialchars(implode(', ', $params)); ?></p>
                    <p><strong>Where Clause:</strong> <?php echo htmlspecialchars($whereClause); ?></p>
                    <p><strong>Total Records:</strong> <?php echo $totalRecords; ?></p>
                    <p><strong>Current Page:</strong> <?php echo $page; ?></p>
                    <p><strong>Limit:</strong> <?php echo $limit; ?></p>
                    <p><strong>Offset:</strong> <?php echo $offset; ?></p>
                </div>
                <?php endif; ?>

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
                                    <?php foreach ($users as $user): ?>
                                        <option value="<?php echo $user['id']; ?>" <?php echo $userId == $user['id'] ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($user['username']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="action" class="form-label">Action</label>
                                <select class="form-select" id="action" name="action">
                                    <option value="">All Actions</option>
                                    <?php foreach ($actions as $actionItem): ?>
                                        <option value="<?php echo htmlspecialchars($actionItem['action']); ?>" <?php echo $action == $actionItem['action'] ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($actionItem['action']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="table_name" class="form-label">Table</label>
                                <select class="form-select" id="table_name" name="table_name">
                                    <option value="">All Tables</option>
                                    <?php foreach ($tables as $table): ?>
                                        <option value="<?php echo htmlspecialchars($table['table_name']); ?>" <?php echo $tableName == $table['table_name'] ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($table['table_name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="fas fa-search me-1"></i>Filter
                                </button>
                                <a href="audit_viewer.php" class="btn btn-outline-secondary me-2">
                                    <i class="fas fa-times me-1"></i>Clear
                                </a>
                                <a href="audit_viewer.php?debug=1" class="btn btn-outline-warning">
                                    <i class="fas fa-bug me-1"></i>Debug
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Audit Logs Table -->
                <div class="card">
                    <div class="card-body">
                        <?php if (empty($auditLogs)): ?>
                            <div class="text-center py-4">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No audit logs found</p>
                                <?php if ($totalRecords > 0): ?>
                                    <p class="text-warning">Total records in database: <?php echo $totalRecords; ?>, but none match current filters.</p>
                                <?php else: ?>
                                    <p class="text-info">No audit logs have been created yet. Try logging in/out or creating/editing a CV to generate audit logs.</p>
                                <?php endif; ?>
                            </div>
                        <?php else: ?>
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
                                        <?php foreach ($auditLogs as $log): ?>
                                            <tr>
                                                <td><?php echo $log['id']; ?></td>
                                                <td>
                                                    <span class="badge bg-info"><?php echo htmlspecialchars($log['username'] ?? 'Unknown'); ?></span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-<?php echo getActionBadgeColor($log['action']); ?>">
                                                        <?php echo htmlspecialchars($log['action']); ?>
                                                    </span>
                                                </td>
                                                <td><?php echo htmlspecialchars($log['table_name']); ?></td>
                                                <td><?php echo $log['record_id']; ?></td>
                                                <td><?php echo date('Y-m-d H:i:s', strtotime($log['timestamp'])); ?></td>
                                                <td><?php echo htmlspecialchars($log['ip_address']); ?></td>
                                                <td>
                                                    <button class="btn btn-sm btn-outline-primary" onclick="showDetails(<?php echo htmlspecialchars(json_encode($log)); ?>)">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <?php if ($totalPages > 1): ?>
                                <nav aria-label="Audit logs pagination">
                                    <ul class="pagination justify-content-center">
                                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                            <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                                                <a class="page-link" href="?<?php echo http_build_query(array_merge($_GET, ['page' => $i])); ?>">
                                                    <?php echo $i; ?>
                                                </a>
                                            </li>
                                        <?php endfor; ?>
                                    </ul>
                                </nav>
                            <?php endif; ?>
                        <?php endif; ?>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function showDetails(log) {
            let content = `
                <div class="row">
                    <div class="col-md-6">
                        <h6>Basic Information</h6>
                        <table class="table table-sm">
                            <tr><td><strong>ID:</strong></td><td>${log.id}</td></tr>
                            <tr><td><strong>User:</strong></td><td>${log.username || 'Unknown'}</td></tr>
                            <tr><td><strong>Action:</strong></td><td>${log.action}</td></tr>
                            <tr><td><strong>Table:</strong></td><td>${log.table_name}</td></tr>
                            <tr><td><strong>Record ID:</strong></td><td>${log.record_id}</td></tr>
                            <tr><td><strong>Timestamp:</strong></td><td>${log.timestamp}</td></tr>
                            <tr><td><strong>IP Address:</strong></td><td>${log.ip_address}</td></tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6>Data Changes</h6>
                        ${log.old_values ? `<h6 class="text-danger">Old Values:</h6><pre class="bg-light p-2">${JSON.stringify(JSON.parse(log.old_values), null, 2)}</pre>` : ''}
                        ${log.new_values ? `<h6 class="text-success">New Values:</h6><pre class="bg-light p-2">${JSON.stringify(JSON.parse(log.new_values), null, 2)}</pre>` : ''}
                    </div>
                </div>
            `;
            document.getElementById('detailsContent').innerHTML = content;
            new bootstrap.Modal(document.getElementById('detailsModal')).show();
        }
    </script>
</body>
</html>

