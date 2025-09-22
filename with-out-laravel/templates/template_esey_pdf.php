<?php
// Esey Template - PDF Optimized Version
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>CV - <?php echo e($cv['name']); ?></title>
  <style>
    /* PDF-optimized CSS */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    
    body {
      font-family: Arial, sans-serif;
      line-height: 1.6;
      color: #333;
      background: white;
      font-size: 12px;
    }
    
    .container {
      max-width: 800px;
      margin: 0 auto;
      padding: 20px;
    }
    
    /* Header Styles */
    .header {
      background: #007bff;
      color: white;
      padding: 30px 20px;
      text-align: center;
      margin-bottom: 30px;
    }
    
    .header h1 {
      font-size: 28px;
      font-weight: bold;
      margin-bottom: 10px;
    }
    
    .header .contact-info {
      font-size: 14px;
      margin-bottom: 5px;
    }
    
    .header .contact-info a {
      color: white;
      text-decoration: none;
    }
    
    .header .contact-info a:hover {
      text-decoration: underline;
    }
    
    /* Section Styles */
    .section {
      margin-bottom: 25px;
    }
    
    .section h2 {
      color: #007bff;
      font-size: 18px;
      font-weight: bold;
      border-bottom: 2px solid #007bff;
      padding-bottom: 5px;
      margin-bottom: 15px;
    }
    
    .section h3 {
      color: #333;
      font-size: 16px;
      font-weight: bold;
      margin-bottom: 5px;
    }
    
    /* Card Styles */
    .card {
      border: 1px solid #ddd;
      border-radius: 5px;
      margin-bottom: 15px;
      background: #f9f9f9;
    }
    
    .card-body {
      padding: 15px;
    }
    
    .card-title {
      color: #333;
      font-size: 16px;
      font-weight: bold;
      margin-bottom: 8px;
    }
    
    .card-subtitle {
      color: #666;
      font-size: 14px;
      margin-bottom: 10px;
    }
    
    .card-text {
      color: #555;
      font-size: 12px;
      line-height: 1.5;
    }
    
    /* Layout */
    .row {
      display: flex;
      gap: 20px;
    }
    
    .col-md-8 {
      flex: 2;
    }
    
    .col-md-4 {
      flex: 1;
    }
    
    /* Contact Box */
    .contact-box {
      background: #f8f9fa;
      border: 1px solid #dee2e6;
      border-radius: 5px;
      padding: 15px;
      margin-bottom: 20px;
    }
    
    .contact-box h5 {
      color: #007bff;
      font-size: 16px;
      font-weight: bold;
      margin-bottom: 10px;
    }
    
    .contact-box p {
      margin-bottom: 5px;
      font-size: 12px;
    }
    
    .contact-box a {
      color: #007bff;
      text-decoration: none;
    }
    
    /* Hobbies */
    .hobbies-box {
      background: #f8f9fa;
      border: 1px solid #dee2e6;
      border-radius: 5px;
      padding: 15px;
    }
    
    .hobbies-box h5 {
      color: #007bff;
      font-size: 16px;
      font-weight: bold;
      margin-bottom: 10px;
    }
    
    .hobbies-box ul {
      list-style: none;
      padding: 0;
    }
    
    .hobbies-box li {
      margin-bottom: 5px;
      font-size: 12px;
    }
    
    /* Summary */
    .summary {
      background: #e3f2fd;
      border-left: 4px solid #007bff;
      padding: 15px;
      margin-bottom: 25px;
    }
    
    .summary p {
      font-size: 14px;
      line-height: 1.6;
      margin: 0;
    }
    
    /* Utility Classes */
    .text-muted {
      color: #6c757d;
    }
    
    .text-primary {
      color: #007bff;
    }
    
    .mb-0 { margin-bottom: 0; }
    .mb-1 { margin-bottom: 5px; }
    .mb-2 { margin-bottom: 10px; }
    .mb-3 { margin-bottom: 15px; }
    .mb-4 { margin-bottom: 20px; }
    .mb-5 { margin-bottom: 25px; }
    
    /* Print Styles */
    @media print {
      body { font-size: 11px; }
      .container { padding: 10px; }
      .header { padding: 20px 10px; }
      .header h1 { font-size: 24px; }
      .section h2 { font-size: 16px; }
      .card-body { padding: 10px; }
    }
  </style>
</head>
<body>

<div class="container">
  <!-- Header -->
  <header class="header">
    <h1><?php echo e($cv['name']); ?></h1>
    <div class="contact-info">
      <div>📍 <?php echo e($cv['address']); ?></div>
      <div>📞 <?php echo e($cv['phone_number']); ?> | ✉️ <a href="mailto:<?php echo e($cv['email']); ?>"><?php echo e($cv['email']); ?></a></div>
      <?php if($cv['date_of_birth']): ?>
        <div>🎂 <?php echo e($cv['date_of_birth']); ?></div>
      <?php endif; ?>
      <?php if($cv['portfolio']): ?>
        <div>🌐 <a href="<?php echo e($cv['portfolio']); ?>">Portfolio</a></div>
      <?php endif; ?>
      <?php if($cv['linkedin_profile']): ?>
        <div>💼 <a href="<?php echo e($cv['linkedin_profile']); ?>">LinkedIn</a></div>
      <?php endif; ?>
    </div>
  </header>

  <!-- Summary -->
  <section class="section">
    <div class="summary">
      <h2>📋 Summary</h2>
      <p><?php echo $cv['profile_summary'] ? nl2br(e($cv['profile_summary'])) : 'Profile summary not available.'; ?></p>
    </div>
  </section>

  <div class="row">
    <div class="col-md-8">
      <!-- Work Experience -->
      <section class="section">
        <h2>💼 Work Experience</h2>
        <?php if($workResult->num_rows > 0): ?>
          <?php while($w = $workResult->fetch_assoc()): ?>
            <div class="card">
              <div class="card-body">
                <h3 class="card-title"><?php echo e($w['job_title']); ?></h3>
                <p class="card-subtitle">
                  <strong><?php echo e($w['company_name']); ?></strong>
                  <?php if($w['work_start'] || $w['work_end']): ?>
                    | <?php echo e($w['work_start']); ?> <?php if($w['work_end']): ?> – <?php echo e($w['work_end']); ?><?php else: ?> – Present<?php endif; ?>
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
      <section class="section">
        <h2>🎓 Education</h2>
        <?php if($educationResult->num_rows > 0): ?>
          <?php while($edu = $educationResult->fetch_assoc()): ?>
            <div class="card">
              <div class="card-body">
                <h3 class="card-title"><?php echo e($edu['degree']); ?></h3>
                <p class="card-subtitle">
                  <strong><?php echo e($edu['institution']); ?></strong>
                  <?php if($edu['education_start'] || $edu['education_end']): ?>
                    | <?php echo e($edu['education_start']); ?> <?php if($edu['education_end']): ?> – <?php echo e($edu['education_end']); ?><?php else: ?> – Present<?php endif; ?>
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
      <div class="contact-box">
        <h5>📞 Contact</h5>
        <p><strong>Email:</strong> <a href="mailto:<?php echo e($cv['email']); ?>"><?php echo e($cv['email']); ?></a></p>
        <?php if($cv['phone_number']): ?><p><strong>Phone:</strong> <?php echo e($cv['phone_number']); ?></p><?php endif; ?>
        <?php if($cv['date_of_birth']): ?><p><strong>DOB:</strong> <?php echo e($cv['date_of_birth']); ?></p><?php endif; ?>
        <?php if($cv['linkedin_profile']): ?><p><a href="<?php echo e($cv['linkedin_profile']); ?>">LinkedIn</a></p><?php endif; ?>
        <?php if($cv['portfolio']): ?><p><a href="<?php echo e($cv['portfolio']); ?>">Portfolio</a></p><?php endif; ?>
      </div>

      <div class="hobbies-box">
        <h5>🎯 Hobbies</h5>
        <?php if($hobbiesResult->num_rows > 0): ?>
          <ul>
            <?php while($hb = $hobbiesResult->fetch_assoc()): ?>
              <li><strong><?php echo e($hb['hobby_name']); ?></strong><?php if($hb['description']): ?> — <?php echo e($hb['description']); ?><?php endif; ?></li>
            <?php endwhile; ?>
          </ul>
        <?php else: ?>
          <p class="text-muted">No hobbies listed.</p>
        <?php endif; ?>
      </div>
    </aside>
  </div>
</div>

</body>
</html>
