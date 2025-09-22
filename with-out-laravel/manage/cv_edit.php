<?php
// Start session first, before any output
session_start();

require_once '../connection.php';
require_once '../includes/auth.php';
require_once '../includes/middleware.php';
require_once '../includes/template_utils.php';

// Use global auth instance and initialize middleware
$auth = $GLOBALS['auth'] ?? new Auth($conn);
$middleware = new Middleware($auth);

// Get CV ID from URL
$cvId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$cvId) {
    header('Location: cv_list.php');
    exit;
}

// Check if user can access this CV
requireCVAccess($cvId);

// Get user context
$userContext = $middleware->getUserContext();

// Check for error messages
$errorMessage = '';
if (isset($_GET['error'])) {
    $errorMessage = htmlspecialchars($_GET['error']);
}

// Get CV data from database with metadata
$cvQuery = "SELECT c.*, m.template_type, m.is_public, m.published_at 
            FROM cv c 
            LEFT JOIN cv_metadata m ON c.id = m.cv_id 
            WHERE c.id = ? LIMIT 1";
$cvStmt = $conn->prepare($cvQuery);
$cvStmt->bind_param("i", $cvId);
$cvStmt->execute();
$cvResult = $cvStmt->get_result();

if ($cvResult->num_rows === 0) {
    die('<div class="container py-5"><div class="alert alert-danger">CV not found</div></div>');
}

$cv = $cvResult->fetch_assoc();
$cvStmt->close();

// Convert template type to name if it's numeric
if (isset($cv['template_type']) && is_numeric($cv['template_type'])) {
    $cv['template_type'] = getTemplateName($cv['template_type']);
}

// Get work experience
$workQuery = "SELECT * FROM work_experience WHERE cv_id = ? ORDER BY work_start DESC";
$workStmt = $conn->prepare($workQuery);
$workStmt->bind_param("i", $cvId);
$workStmt->execute();
$workResult = $workStmt->get_result();
$workData = $workResult->fetch_all(MYSQLI_ASSOC);
$workStmt->close();

// Get education
$educationQuery = "SELECT * FROM education WHERE cv_id = ? ORDER BY education_start DESC";
$educationStmt = $conn->prepare($educationQuery);
$educationStmt->bind_param("i", $cvId);
$educationStmt->execute();
$educationResult = $educationStmt->get_result();
$educationData = $educationResult->fetch_all(MYSQLI_ASSOC);
$educationStmt->close();

// Get skills
$skillsQuery = "SELECT * FROM skills WHERE cv_id = ? ORDER BY id";
$skillsStmt = $conn->prepare($skillsQuery);
$skillsStmt->bind_param("i", $cvId);
$skillsStmt->execute();
$skillsResult = $skillsStmt->get_result();
$skillsData = $skillsResult->fetch_all(MYSQLI_ASSOC);
$skillsStmt->close();

// Get languages
$languagesQuery = "SELECT * FROM languages WHERE cv_id = ? ORDER BY id";
$languagesStmt = $conn->prepare($languagesQuery);
$languagesStmt->bind_param("i", $cvId);
$languagesStmt->execute();
$languagesResult = $languagesStmt->get_result();
$languagesData = $languagesResult->fetch_all(MYSQLI_ASSOC);
$languagesStmt->close();

// Get hobbies
$hobbiesQuery = "SELECT * FROM hobbies WHERE cv_id = ? ORDER BY id";
$hobbiesStmt = $conn->prepare($hobbiesQuery);
$hobbiesStmt->bind_param("i", $cvId);
$hobbiesStmt->execute();
$hobbiesResult = $hobbiesStmt->get_result();
$hobbiesData = $hobbiesResult->fetch_all(MYSQLI_ASSOC);
$hobbiesStmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit CV - <?php echo htmlspecialchars($cv['name']); ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .form-section {
            background: white;
            border-radius: 10px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .section-header {
            color: #0d6efd;
            border-bottom: 2px solid #0d6efd;
            padding-bottom: 0.5rem;
            margin-bottom: 1.5rem;
        }
        .dynamic-section {
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            background: #f8f9fa;
        }
        .btn-add {
            background: linear-gradient(135deg, #28a745, #20c997);
            border: none;
            color: white;
        }
        .btn-add:hover {
            background: linear-gradient(135deg, #218838, #1ea085);
            color: white;
        }
        .btn-remove {
            background: linear-gradient(135deg, #dc3545, #fd7e14);
            border: none;
            color: white;
        }
        .btn-remove:hover {
            background: linear-gradient(135deg, #c82333, #e55a00);
            color: white;
        }
        .template-info {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 2rem;
        }
    </style>
</head>
<body class="bg-light">
    <!-- Fixed Navigation Header -->
    <nav class="navbar navbar-dark bg-primary fixed-top" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important; box-shadow: 0 2px 20px rgba(0,0,0,0.15);">
        <div class="container">
            <a href="cv_list.php" class="navbar-brand fw-bold">
                <i class="fas fa-arrow-left me-2"></i>Back to CV List
            </a>
            <span class="navbar-text fw-semibold">
                <i class="fas fa-edit me-2"></i>Edit CV
            </span>
            <div class="navbar-nav ms-auto">
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user me-1"></i><?php echo htmlspecialchars($userContext['user']['username']); ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="../user_profile.php"><i class="fas fa-user me-2"></i>My Profile</a></li>
                        <li><a class="dropdown-item" href="../index.php"><i class="fas fa-home me-2"></i>Homepage</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="../logout.php"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="container my-5" style="padding-top: 120px;">
        <!-- Template Info -->
        <div class="template-info">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2 class="h4 mb-2">
                        <i class="fas fa-edit me-2"></i>Edit CV: <?php echo htmlspecialchars($cv['name']); ?>
                    </h2>
                    <p class="mb-0">Using <?php echo ucfirst($cv['template_type'] ?? 'nathan'); ?> Template</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <a href="cv_preview.php?id=<?php echo $cvId; ?>" class="btn btn-light">
                        <i class="fas fa-eye me-1"></i>Preview CV
                    </a>
                </div>
            </div>
        </div>

        <?php if ($errorMessage): ?>
        <div class="alert alert-danger" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <?php echo $errorMessage; ?>
        </div>
        <?php endif; ?>

        <form action="cv_update.php" method="POST" id="cvEditForm">
            <input type="hidden" name="cv_id" value="<?php echo $cvId; ?>">
            <input type="hidden" name="template_type" value="<?php echo $cv['template_type'] ?? 'nathan'; ?>">
            
            <!-- Personal Information -->
            <div class="form-section">
                <h3 class="section-header">
                    <i class="fas fa-user me-2"></i>Personal Information
                </h3>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">Full Name *</label>
                        <input type="text" class="form-control" id="name" name="name" 
                               value="<?php echo htmlspecialchars($cv['name']); ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email Address *</label>
                        <input type="email" class="form-control" id="email" name="email" 
                               value="<?php echo htmlspecialchars($cv['email']); ?>" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="phone_number" class="form-label">Phone Number</label>
                        <input type="tel" class="form-control" id="phone_number" name="phone_number" 
                               value="<?php echo htmlspecialchars($cv['phone_number'] ?? ''); ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="date_of_birth" class="form-label">Date of Birth</label>
                        <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" 
                               value="<?php echo $cv['date_of_birth']; ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="address" class="form-label">Address</label>
                        <input type="text" class="form-control" id="address" name="address" 
                               value="<?php echo htmlspecialchars($cv['address'] ?? ''); ?>">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="linkedin_profile" class="form-label">LinkedIn Profile</label>
                        <input type="url" class="form-control" id="linkedin_profile" name="linkedin_profile" 
                               value="<?php echo htmlspecialchars($cv['linkedin_profile'] ?? ''); ?>">
                    </div>
                </div>
                <div class="mb-3">
                    <label for="portfolio" class="form-label">Portfolio Website</label>
                    <input type="url" class="form-control" id="portfolio" name="portfolio" 
                           value="<?php echo htmlspecialchars($cv['portfolio'] ?? ''); ?>">
                </div>
            </div>

            <!-- Profile Summary -->
            <div class="form-section">
                <h3 class="section-header">
                    <i class="fas fa-file-alt me-2"></i>Profile Summary
                </h3>
                <div class="mb-3">
                    <label for="profile_summary" class="form-label">Professional Summary *</label>
                    <textarea class="form-control" id="profile_summary" name="profile_summary" rows="4" required><?php echo htmlspecialchars($cv['profile_summary'] ?? ''); ?></textarea>
                </div>
            </div>

            <!-- Work Experience -->
            <div class="form-section">
                <h3 class="section-header">
                    <i class="fas fa-briefcase me-2"></i>Work Experience
                </h3>
                <div id="workExperienceContainer">
                    <?php if (empty($workData)): ?>
                        <div class="dynamic-section work-entry">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Job Title</label>
                                    <input type="text" class="form-control" name="work_title[]">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Company Name</label>
                                    <input type="text" class="form-control" name="work_company[]">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Start Date</label>
                                    <input type="date" class="form-control" name="work_start[]">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">End Date</label>
                                    <input type="date" class="form-control" name="work_end[]">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Currently Working</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="work_current[]" value="1">
                                        <label class="form-check-label">Yes</label>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" name="work_description[]" rows="3"></textarea>
                            </div>
                            <button type="button" class="btn btn-remove btn-sm" onclick="removeWorkEntry(this)">
                                <i class="fas fa-trash me-1"></i>Remove Entry
                            </button>
                        </div>
                    <?php else: ?>
                        <?php foreach ($workData as $index => $work): ?>
                        <div class="dynamic-section work-entry">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Job Title</label>
                                    <input type="text" class="form-control" name="work_title[]" 
                                           value="<?php echo htmlspecialchars($work['job_title']); ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Company Name</label>
                                    <input type="text" class="form-control" name="work_company[]" 
                                           value="<?php echo htmlspecialchars($work['company_name']); ?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Start Date</label>
                                    <input type="date" class="form-control" name="work_start[]" 
                                           value="<?php echo $work['work_start']; ?>">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">End Date</label>
                                    <input type="date" class="form-control" name="work_end[]" 
                                           value="<?php echo $work['work_end']; ?>">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Currently Working</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="work_current[]" value="1" 
                                               <?php echo $work['is_current'] ? 'checked' : ''; ?>>
                                        <label class="form-check-label">Yes</label>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" name="work_description[]" rows="3"><?php echo htmlspecialchars($work['description']); ?></textarea>
                            </div>
                            <button type="button" class="btn btn-remove btn-sm" onclick="removeWorkEntry(this)">
                                <i class="fas fa-trash me-1"></i>Remove Entry
                            </button>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <button type="button" class="btn btn-add" onclick="addWorkEntry()">
                    <i class="fas fa-plus me-1"></i>Add Work Experience
                </button>
            </div>

            <!-- Education -->
            <div class="form-section">
                <h3 class="section-header">
                    <i class="fas fa-graduation-cap me-2"></i>Education
                </h3>
                <div id="educationContainer">
                    <?php if (empty($educationData)): ?>
                        <div class="dynamic-section education-entry">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Degree/Qualification</label>
                                    <input type="text" class="form-control" name="education_degree[]">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Institution</label>
                                    <input type="text" class="form-control" name="education_institution[]">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Start Date</label>
                                    <input type="date" class="form-control" name="education_start[]">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">End Date</label>
                                    <input type="date" class="form-control" name="education_end[]">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Currently Studying</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="education_current[]" value="1">
                                        <label class="form-check-label">Yes</label>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" name="education_description[]" rows="3"></textarea>
                            </div>
                            <button type="button" class="btn btn-remove btn-sm" onclick="removeEducationEntry(this)">
                                <i class="fas fa-trash me-1"></i>Remove Entry
                            </button>
                        </div>
                    <?php else: ?>
                        <?php foreach ($educationData as $index => $education): ?>
                        <div class="dynamic-section education-entry">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Degree/Qualification</label>
                                    <input type="text" class="form-control" name="education_degree[]" 
                                           value="<?php echo htmlspecialchars($education['degree']); ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Institution</label>
                                    <input type="text" class="form-control" name="education_institution[]" 
                                           value="<?php echo htmlspecialchars($education['institution']); ?>">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Start Date</label>
                                    <input type="date" class="form-control" name="education_start[]" 
                                           value="<?php echo $education['education_start']; ?>">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">End Date</label>
                                    <input type="date" class="form-control" name="education_end[]" 
                                           value="<?php echo $education['education_end']; ?>">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Currently Studying</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="education_current[]" value="1" 
                                               <?php echo $education['is_current'] ? 'checked' : ''; ?>>
                                        <label class="form-check-label">Yes</label>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" name="education_description[]" rows="3"><?php echo htmlspecialchars($education['description']); ?></textarea>
                            </div>
                            <button type="button" class="btn btn-remove btn-sm" onclick="removeEducationEntry(this)">
                                <i class="fas fa-trash me-1"></i>Remove Entry
                            </button>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <button type="button" class="btn btn-add" onclick="addEducationEntry()">
                    <i class="fas fa-plus me-1"></i>Add Education
                </button>
            </div>

            <!-- Hobbies & Interests -->
            <div class="form-section">
                <h3 class="section-header">
                    <i class="fas fa-heart me-2"></i>Hobbies & Interests
                </h3>
                <div id="hobbiesContainer">
                    <?php if (empty($hobbiesData)): ?>
                        <div class="dynamic-section hobby-entry">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Hobby Name</label>
                                    <input type="text" class="form-control" name="hobby_name[]">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Description</label>
                                    <input type="text" class="form-control" name="hobby_description[]">
                                </div>
                            </div>
                            <button type="button" class="btn btn-remove btn-sm" onclick="removeHobbyEntry(this)">
                                <i class="fas fa-trash me-1"></i>Remove Entry
                            </button>
                        </div>
                    <?php else: ?>
                        <?php foreach ($hobbiesData as $index => $hobby): ?>
                        <div class="dynamic-section hobby-entry">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Hobby Name</label>
                                    <input type="text" class="form-control" name="hobby_name[]" 
                                           value="<?php echo htmlspecialchars($hobby['hobby_name']); ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Description</label>
                                    <input type="text" class="form-control" name="hobby_description[]" 
                                           value="<?php echo htmlspecialchars($hobby['description']); ?>">
                                </div>
                            </div>
                            <button type="button" class="btn btn-remove btn-sm" onclick="removeHobbyEntry(this)">
                                <i class="fas fa-trash me-1"></i>Remove Entry
                            </button>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <button type="button" class="btn btn-add" onclick="addHobbyEntry()">
                    <i class="fas fa-plus me-1"></i>Add Hobby
                </button>
            </div>

            <!-- Submit Buttons -->
            <div class="form-section">
                <div class="d-grid gap-2 d-md-flex justify-content-md-between">
                    <a href="cv_list.php" class="btn btn-secondary btn-lg">
                        <i class="fas fa-arrow-left me-2"></i>Cancel
                    </a>
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-save me-2"></i>Update CV
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Work Experience Functions
        function addWorkEntry() {
            const container = document.getElementById('workExperienceContainer');
            const newEntry = container.querySelector('.work-entry').cloneNode(true);
            newEntry.querySelectorAll('input, textarea').forEach(input => input.value = '');
            container.appendChild(newEntry);
        }

        function removeWorkEntry(button) {
            const container = document.getElementById('workExperienceContainer');
            if (container.children.length > 1) {
                button.closest('.work-entry').remove();
            }
        }

        // Education Functions
        function addEducationEntry() {
            const container = document.getElementById('educationContainer');
            const newEntry = container.querySelector('.education-entry').cloneNode(true);
            newEntry.querySelectorAll('input, textarea').forEach(input => input.value = '');
            container.appendChild(newEntry);
        }

        function removeEducationEntry(button) {
            const container = document.getElementById('educationContainer');
            if (container.children.length > 1) {
                button.closest('.education-entry').remove();
            }
        }

        // Hobbies Functions
        function addHobbyEntry() {
            const container = document.getElementById('hobbiesContainer');
            const newEntry = container.querySelector('.hobby-entry').cloneNode(true);
            newEntry.querySelectorAll('input').forEach(input => input.value = '');
            container.appendChild(newEntry);
        }

        function removeHobbyEntry(button) {
            const container = document.getElementById('hobbiesContainer');
            if (container.children.length > 1) {
                button.closest('.hobby-entry').remove();
            }
        }

        // Form validation
        document.getElementById('cvEditForm').addEventListener('submit', function(e) {
            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            const summary = document.getElementById('profile_summary').value.trim();

            if (!name || !email || !summary) {
                e.preventDefault();
                alert('Please fill in all required fields (Name, Email, and Profile Summary).');
                return;
            }

            // Validate email format
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                e.preventDefault();
                alert('Please enter a valid email address.');
                return;
            }
        });
    </script>
</body>
</html>

<?php
$conn->close();
?>
