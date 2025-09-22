<?php
// Esey Template - Clean single-column layout
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>CV - <?php echo e($cv['name']); ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-primary shadow-sm">
  <div class="container">
    <a href="<?php echo $GLOBALS['index_path']; ?>" class="navbar-brand fw-bold">
      <i class="fas fa-home me-2"></i>Back to Home
    </a>
  </div>
</nav>

<header class="bg-primary text-white text-center py-5 position-relative">
  <div class="container">
    <h1 class="display-4 fw-bold"><?php echo e($cv['name']); ?></h1>
    <p class="lead mb-1">
      <i class="fas fa-map-marker-alt"></i>
      <?php echo e($cv['address']); ?>
      &nbsp;|&nbsp;
      <i class="fas fa-phone"></i>
      <?php echo e($cv['phone_number']); ?>
      &nbsp;|&nbsp;
      <i class="fas fa-envelope"></i>
      <a class="text-white" href="mailto:<?php echo e($cv['email']); ?>"><?php echo e($cv['email']); ?></a>
    </p>
    <p class="mb-0">
      <i class="fas fa-birthday-cake"></i>
      <?php echo $cv['date_of_birth'] ? e($cv['date_of_birth']) : ''; ?>
      <?php if($cv['portfolio']): ?>
        &nbsp;|&nbsp;<a class="text-warning fw-bold" target="_blank" href="<?php echo e($cv['portfolio']); ?>">Portfolio</a>
      <?php endif; ?>
      <?php if($cv['linkedin_profile']): ?>
        &nbsp;|&nbsp;<a class="text-white" target="_blank" href="<?php echo e($cv['linkedin_profile']); ?>"><i class="fab fa-linkedin"></i></a>
      <?php endif; ?>
    </p>
    
    <!-- Export Buttons - Show for guest creators or logged-in guest users -->
    <?php if (isset($GLOBALS['userContext']) && (($GLOBALS['userContext']['is_guest_creator'] ?? false) || ($GLOBALS['userContext']['is_logged_in'] && $GLOBALS['userContext']['is_guest']))): ?>
    <div class="position-absolute top-0 end-0 p-3">
      <div class="d-flex gap-2">
        <button onclick="window.print()" class="btn btn-info btn-sm">
          <i class="fas fa-print me-1"></i>Print
        </button>
        <a href="../login.php?redirect=<?php echo urlencode('manage/cv_list.php'); ?>&message=export_required" class="btn btn-danger btn-sm">
          <i class="fas fa-file-pdf me-1"></i>Export PDF
        </a>
        <a href="../login.php?redirect=<?php echo urlencode('manage/cv_list.php'); ?>&message=export_required" class="btn btn-success btn-sm">
          <i class="fas fa-file-code me-1"></i>Export XML
        </a>
      </div>
    </div>
    <?php endif; ?>
  </div>
</header>

<main class="container my-5">
  <!-- Summary -->
  <section class="mb-5">
    <h2 class="h4 text-primary border-bottom pb-2"><i class="fas fa-user"></i> Summary</h2>
    <p class="fs-5"><?php echo $cv['profile_summary'] ? nl2br(e($cv['profile_summary'])) : 'Profile summary not available.'; ?></p>
  </section>

  <div class="row">
    <div class="col-md-8">
      <!-- Work Experience -->
      <section class="mb-5">
        <h2 class="h4 text-primary border-bottom pb-2"><i class="fas fa-briefcase"></i> Work Experience</h2>
        <?php if($workResult->num_rows > 0): ?>
          <?php while($w = $workResult->fetch_assoc()): ?>
            <div class="card mb-3 shadow-sm">
              <div class="card-body">
                <h3 class="h5 card-title"><i class="fas fa-user-tie me-2"></i><?php echo e($w['job_title']); ?></h3>
                <p class="card-subtitle mb-2 text-muted">
                  <strong><?php echo e($w['company_name']); ?></strong>
                  <?php if($w['work_start'] || $w['work_end']): ?>
                    &nbsp;|&nbsp; <?php echo e($w['work_start']); ?> <?php if($w['work_end']): ?> – <?php echo e($w['work_end']); ?><?php else: ?> – Present<?php endif; ?>
                  <?php endif; ?>
                </p>
                <?php if(!empty($w['description'])): ?>
                  <p class="card-text"><?php echo nl2br(e($w['description'])); ?></p>
                <?php endif; ?>
              </div>
            </div>
          <?php endwhile; ?>
        <?php else: ?>
          <p class="text-muted">No work experience available.</p>
        <?php endif; ?>
      </section>

      <!-- Education -->
      <section class="mb-5">
        <h2 class="h4 text-primary border-bottom pb-2"><i class="fas fa-graduation-cap"></i> Education</h2>
        <?php if($educationResult->num_rows > 0): ?>
          <?php while($edu = $educationResult->fetch_assoc()): ?>
            <div class="card mb-3 shadow-sm">
              <div class="card-body">
                <h3 class="h5 card-title"><i class="fas fa-school me-2"></i><?php echo e($edu['degree']); ?></h3>
                <p class="card-subtitle mb-2 text-muted">
                  <strong><?php echo e($edu['institution']); ?></strong>
                  <?php if($edu['education_start'] || $edu['education_end']): ?>
                    &nbsp;|&nbsp; <?php echo e($edu['education_start']); ?> <?php if($edu['education_end']): ?> – <?php echo e($edu['education_end']); ?><?php else: ?> – Present<?php endif; ?>
                  <?php endif; ?>
                </p>
                <?php if(!empty($edu['description'])): ?>
                  <p class="card-text"><?php echo nl2br(e($edu['description'])); ?></p>
                <?php endif; ?>
              </div>
            </div>
          <?php endwhile; ?>
        <?php else: ?>
          <p class="text-muted">No education data available.</p>
        <?php endif; ?>
      </section>
    </div>

    <aside class="col-md-4">
      <div class="card mb-3">
        <div class="card-body">
          <h5 class="card-title">Contact</h5>
          <p class="mb-1"><strong>Email:</strong> <a href="mailto:<?php echo e($cv['email']); ?>"><?php echo e($cv['email']); ?></a></p>
          <?php if($cv['phone_number']): ?><p class="mb-1"><strong>Phone:</strong> <?php echo e($cv['phone_number']); ?></p><?php endif; ?>
          <?php if($cv['date_of_birth']): ?><p class="mb-1"><strong>DOB:</strong> <?php echo e($cv['date_of_birth']); ?></p><?php endif; ?>
          <?php if($cv['linkedin_profile']): ?><p class="mb-1"><a href="<?php echo e($cv['linkedin_profile']); ?>" target="_blank" rel="noopener">LinkedIn</a></p><?php endif; ?>
          <?php if($cv['portfolio']): ?><p class="mb-1"><a href="<?php echo e($cv['portfolio']); ?>" target="_blank" rel="noopener">Portfolio</a></p><?php endif; ?>
        </div>
      </div>

      <!-- Skills Card -->
      <?php if ($GLOBALS['skillsResult'] && $GLOBALS['skillsResult']->num_rows > 0): ?>
      <div class="card mb-3">
        <div class="card-body">
          <h5 class="card-title"><i class="fas fa-cogs me-2"></i>Skills</h5>
          <?php 
          $skillsResult = $GLOBALS['skillsResult'];
          $skillsResult->data_seek(0); // Reset pointer
          ?>
          <ul class="mb-0">
            <?php while($skill = $skillsResult->fetch_assoc()): ?>
              <li class="mb-2">
                <strong><?php echo e($skill['skill_name']); ?></strong>
                <?php if($skill['description']): ?>
                  <br><small class="text-muted"><?php echo e($skill['description']); ?></small>
                <?php endif; ?>
              </li>
            <?php endwhile; ?>
          </ul>
        </div>
      </div>
      <?php endif; ?>

      <!-- Languages Card -->
      <?php if ($GLOBALS['languagesResult'] && $GLOBALS['languagesResult']->num_rows > 0): ?>
      <div class="card mb-3">
        <div class="card-body">
          <h5 class="card-title"><i class="fas fa-language me-2"></i>Languages</h5>
          <?php 
          $languagesResult = $GLOBALS['languagesResult'];
          $languagesResult->data_seek(0); // Reset pointer
          ?>
          <ul class="mb-0">
            <?php while($language = $languagesResult->fetch_assoc()): ?>
              <li class="mb-2">
                <strong><?php echo e($language['language_name']); ?></strong>
                <span class="badge bg-primary ms-2"><?php echo ucfirst($language['proficiency']); ?></span>
              </li>
            <?php endwhile; ?>
          </ul>
        </div>
      </div>
      <?php endif; ?>

      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Hobbies</h5>
          <?php if($hobbiesResult->num_rows > 0): ?>
            <ul class="mb-0">
              <?php while($hb = $hobbiesResult->fetch_assoc()): ?>
                <li><strong><?php echo e($hb['hobby_name']); ?></strong><?php if($hb['description']): ?> — <?php echo e($hb['description']); ?><?php endif; ?></li>
              <?php endwhile; ?>
            </ul>
          <?php else: ?>
            <p class="text-muted mb-0">No hobbies listed.</p>
          <?php endif; ?>
        </div>
      </div>
    </aside>
  </div>

  <div class="mt-4">
    <a href="<?php echo $GLOBALS['index_path']; ?>" class="btn btn-secondary">
      <i class="fas fa-arrow-left me-2"></i>Back to Home
    </a>
  </div>
</main>

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
