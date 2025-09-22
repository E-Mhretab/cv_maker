<?php
// Nathan Template - Bootstrap-based layout with sidebar
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Curriculum Vitae — <?php echo e($cv['name']); ?></title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <!-- Custom CSS -->
  <link rel="stylesheet" href="../css/nathan.css">
</head>
<body class="bg-light">
  <!-- Mobile Navigation Toggle -->
  <nav class="navbar navbar-expand-lg d-lg-none bg-primary text-white">
    <div class="container-fluid">
      <a class="navbar-brand" href="<?php echo $GLOBALS['index_path']; ?>">
        <i class="fas fa-home me-2"></i>Back to Home
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mobileSidebar">
        <span class="navbar-toggler-icon"></span>
      </button>
    </div>
  </nav>

  <div class="container-fluid">
    <div class="row g-0">
      <!-- Sidebar -->
      <div class="col-lg-4 col-md-12 bg-primary text-white p-4 collapse d-lg-block" id="mobileSidebar">
        <!-- Navigation -->
        <div class="mb-4">
          <a href="<?php echo $GLOBALS['index_path']; ?>" class="btn btn-outline-light btn-sm mb-3 d-flex align-items-center">
            <i class="fas fa-home me-2"></i>Back to Home
          </a>
        </div>

        <header class="text-center mb-4 position-relative">
          <div class="mb-3">
            <i class="fas fa-user-circle fa-5x"></i>
          </div>
          <h1 class="h2 mb-3"><?php echo e($cv['name']); ?></h1>
          <p class="lead">Software Developer</p>
          <p>Address: <?php echo e($cv['address']); ?> | Phone: <?php echo e($cv['phone_number']); ?> | Email: <?php echo e($cv['email']); ?></p>
          <p>Date of Birth: <?php echo $cv['date_of_birth'] ? date('Y', strtotime($cv['date_of_birth'])) : 'N/A'; ?> | 
            <?php if ($cv['linkedin_profile']): ?>
              <a href="<?php echo e($cv['linkedin_profile']); ?>" target="_blank" class="text-white">LinkedIn Profile</a>
            <?php else: ?>
              <span class="text-white-50">LinkedIn Profile</span>
            <?php endif; ?>
          </p>
          
          <!-- Export Buttons - Show for guest creators or logged-in guest users -->
          <?php if (isset($GLOBALS['userContext']) && (($GLOBALS['userContext']['is_guest_creator'] ?? false) || ($GLOBALS['userContext']['is_logged_in'] && $GLOBALS['userContext']['is_guest']))): ?>
          <div class="position-absolute top-0 end-0 p-2">
            <div class="d-flex flex-column gap-1">
              <button onclick="window.print()" class="btn btn-info btn-sm">
                <i class="fas fa-print me-1"></i>Print
              </button>
              <a href="../login.php?redirect=<?php echo urlencode('manage/cv_list.php'); ?>&message=export_required" class="btn btn-danger btn-sm">
                <i class="fas fa-file-pdf me-1"></i>PDF
              </a>
              <a href="../login.php?redirect=<?php echo urlencode('manage/cv_list.php'); ?>&message=export_required" class="btn btn-success btn-sm">
                <i class="fas fa-file-code me-1"></i>XML
              </a>
            </div>
          </div>
          <?php endif; ?>
        </header>

        <!-- Skills Section -->
        <?php if ($GLOBALS['skillsResult'] && $GLOBALS['skillsResult']->num_rows > 0): ?>
        <div class="mb-4">
          <h3 class="h5 mb-3"><i class="fas fa-cogs me-2"></i>Skills</h3>
          <?php 
          $skillsResult = $GLOBALS['skillsResult'];
          $skillsResult->data_seek(0); // Reset pointer
          while ($skill = $skillsResult->fetch_assoc()): ?>
            <div class="mb-2">
              <strong><?php echo e($skill['skill_name']); ?></strong>
              <?php if ($skill['description']): ?>
                <br><small class="text-white-75"><?php echo e($skill['description']); ?></small>
              <?php endif; ?>
            </div>
          <?php endwhile; ?>
        </div>
        <?php endif; ?>

        <!-- Languages Section -->
        <?php if ($GLOBALS['languagesResult'] && $GLOBALS['languagesResult']->num_rows > 0): ?>
        <div class="mb-4">
          <h3 class="h5 mb-3"><i class="fas fa-language me-2"></i>Languages</h3>
          <?php 
          $languagesResult = $GLOBALS['languagesResult'];
          $languagesResult->data_seek(0); // Reset pointer
          while ($language = $languagesResult->fetch_assoc()): ?>
            <div class="mb-2">
              <strong><?php echo e($language['language_name']); ?></strong>
              <span class="badge bg-light text-dark ms-2"><?php echo ucfirst($language['proficiency']); ?></span>
            </div>
          <?php endwhile; ?>
        </div>
        <?php endif; ?>

        <div class="mb-4">
          <h3 class="h5 mb-3"><i class="fas fa-heart me-2"></i>Hobbies and Interests</h3>
          <?php while ($hobby = $hobbiesResult->fetch_assoc()): ?>
            <p class="mb-2"><?php echo e($hobby['hobby_name']); ?></p>
          <?php endwhile; ?>
        </div>
      </div>

      <!-- Main Content -->
      <div class="col-lg-8 col-md-12">
        <main class="p-4">
          <!-- Summary Section -->
          <section class="mb-5">
            <h2 class="h4 text-primary border-bottom pb-2 mb-3">
              <i class="fas fa-user me-2"></i>Profile Summary
            </h2>
            <p><?php echo e($cv['profile_summary']); ?></p>
          </section>

          <!-- Work Experience Section -->
          <section class="mb-5">
            <h2 class="h4 text-primary border-bottom pb-2 mb-3">
              <i class="fas fa-briefcase me-2"></i>Work Experience
            </h2>
            <?php while ($work = $workResult->fetch_assoc()): ?>
              <div class="card mb-3 shadow-sm">
                <div class="card-body">
                  <h3 class="h5 card-title mb-1"><?php echo e($work['job_title']); ?></h3>
                  <p class="card-subtitle text-muted mb-1"><?php echo e($work['company_name']); ?></p>
                  <p class="card-subtitle text-muted mb-2">
                    <?php echo $work['work_start'] ? date('F Y', strtotime($work['work_start'])) : 'Start date not specified'; ?> - 
                    <?php echo $work['work_end'] ? date('F Y', strtotime($work['work_end'])) : 'Present'; ?>
                  </p>
                  <?php if ($work['description']): ?>
                    <p class="card-text"><?php echo e($work['description']); ?></p>
                  <?php endif; ?>
                </div>
              </div>
            <?php endwhile; ?>
          </section>

          <!-- Education Section -->
          <section class="mb-5">
            <h2 class="h4 text-primary border-bottom pb-2 mb-3">
              <i class="fas fa-graduation-cap me-2"></i>Education
            </h2>
            <?php while ($education = $educationResult->fetch_assoc()): ?>
              <div class="card mb-3 shadow-sm">
                <div class="card-body">
                  <h3 class="h5 card-title mb-1"><?php echo e($education['degree']); ?></h3>
                  <p class="card-subtitle text-muted mb-1"><?php echo e($education['institution']); ?></p>
                  <p class="card-subtitle text-muted mb-2">
                    <?php echo $education['education_start'] ? date('F Y', strtotime($education['education_start'])) : 'Start date not specified'; ?> - 
                    <?php echo $education['education_end'] ? date('F Y', strtotime($education['education_end'])) : 'Present'; ?>
                  </p>
                  <?php if ($education['description']): ?>
                    <p class="card-text"><?php echo e($education['description']); ?></p>
                  <?php endif; ?>
                </div>
              </div>
            <?php endwhile; ?>
          </section>
        </main>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  
  <!-- Print Styles -->
  <style>
    @media print {
      /* Hide export buttons when printing */
      .position-absolute.top-0.end-0 {
        display: none !important;
      }
      
      /* Hide navigation elements */
      .navbar, .btn {
        display: none !important;
      }
      
      /* Ensure proper page breaks */
      .card {
        break-inside: avoid;
        page-break-inside: avoid;
      }
      
      /* Remove shadows and borders for cleaner print */
      .card {
        box-shadow: none !important;
        border: 1px solid #ddd !important;
      }
    }
  </style>
</body>
</html>
