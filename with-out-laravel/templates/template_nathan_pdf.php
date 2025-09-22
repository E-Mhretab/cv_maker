<?php
// Nathan Template - PDF Optimized Version
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Curriculum Vitae — <?php echo e($cv['name']); ?></title>
  <style>
    /* PDF-optimized CSS for Nathan template */
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
    
    /* Sidebar Styles */
    .sidebar {
      background: #007bff;
      color: white;
      padding: 30px 20px;
      width: 35%;
      float: left;
      min-height: 100vh;
    }
    
    .main-content {
      width: 65%;
      float: right;
      padding: 30px 20px;
    }
    
    /* Header Styles */
    .header {
      text-align: center;
      margin-bottom: 30px;
    }
    
    .header h1 {
      font-size: 24px;
      font-weight: bold;
      margin-bottom: 10px;
    }
    
    .header .title {
      font-size: 16px;
      margin-bottom: 15px;
      color: #e3f2fd;
    }
    
    .header .contact-info {
      font-size: 12px;
      line-height: 1.4;
    }
    
    .header .contact-info p {
      margin-bottom: 5px;
    }
    
    .header .contact-info a {
      color: white;
      text-decoration: none;
    }
    
    /* Section Styles */
    .section {
      margin-bottom: 25px;
    }
    
    .section h2 {
      color: white;
      font-size: 16px;
      font-weight: bold;
      border-bottom: 2px solid #e3f2fd;
      padding-bottom: 5px;
      margin-bottom: 15px;
    }
    
    .section h3 {
      color: #333;
      font-size: 14px;
      font-weight: bold;
      margin-bottom: 5px;
    }
    
    /* Main Content Styles */
    .main-section h2 {
      color: #007bff;
      font-size: 18px;
      font-weight: bold;
      border-bottom: 2px solid #007bff;
      padding-bottom: 5px;
      margin-bottom: 15px;
    }
    
    .main-section h3 {
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
      font-size: 14px;
      font-weight: bold;
      margin-bottom: 8px;
    }
    
    .card-subtitle {
      color: #666;
      font-size: 12px;
      margin-bottom: 10px;
    }
    
    .card-text {
      color: #555;
      font-size: 11px;
      line-height: 1.5;
    }
    
    /* Skills */
    .skills-list {
      list-style: none;
      padding: 0;
    }
    
    .skills-list li {
      margin-bottom: 5px;
      font-size: 12px;
    }
    
    /* Work Experience & Education */
    .experience-item, .education-item {
      margin-bottom: 20px;
      padding-bottom: 15px;
      border-bottom: 1px solid #eee;
    }
    
    .experience-item:last-child, .education-item:last-child {
      border-bottom: none;
    }
    
    /* Hobbies */
    .hobbies-list {
      list-style: none;
      padding: 0;
    }
    
    .hobbies-list li {
      margin-bottom: 5px;
      font-size: 12px;
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
      body { font-size: 10px; }
      .container { padding: 10px; }
      .sidebar { padding: 20px 15px; }
      .main-content { padding: 20px 15px; }
      .header h1 { font-size: 20px; }
      .main-section h2 { font-size: 16px; }
      .section h2 { font-size: 14px; }
      .card-body { padding: 10px; }
    }
  </style>
</head>
<body>

<div class="container">
  <div class="sidebar">
    <!-- Header -->
    <header class="header">
      <h1><?php echo e($cv['name']); ?></h1>
      <p class="title">Software Developer</p>
      <div class="contact-info">
        <p>📍 <?php echo e($cv['address']); ?></p>
        <p>📞 <?php echo e($cv['phone_number']); ?></p>
        <p>✉️ <a href="mailto:<?php echo e($cv['email']); ?>"><?php echo e($cv['email']); ?></a></p>
        <p>🎂 <?php echo $cv['date_of_birth'] ? date('Y', strtotime($cv['date_of_birth'])) : 'N/A'; ?></p>
        <?php if ($cv['linkedin_profile']): ?>
          <p>💼 <a href="<?php echo e($cv['linkedin_profile']); ?>">LinkedIn Profile</a></p>
        <?php endif; ?>
        <?php if ($cv['portfolio']): ?>
          <p>🌐 <a href="<?php echo e($cv['portfolio']); ?>">Portfolio</a></p>
        <?php endif; ?>
      </div>
    </header>

    <!-- Skills -->
    <section class="section">
      <h2>🛠️ Skills</h2>
      <ul class="skills-list">
        <li>PHP Development</li>
        <li>JavaScript</li>
        <li>HTML/CSS</li>
        <li>MySQL</li>
        <li>Bootstrap</li>
        <li>Git</li>
        <li>Problem Solving</li>
        <li>Team Collaboration</li>
      </ul>
    </section>

    <!-- Languages -->
    <section class="section">
      <h2>🌍 Languages</h2>
      <ul class="skills-list">
        <li>English - Fluent</li>
        <li>Dutch - Native</li>
        <li>German - Basic</li>
      </ul>
    </section>

    <!-- Hobbies -->
    <section class="section">
      <h2>🎯 Hobbies</h2>
      <?php if($hobbiesResult->num_rows > 0): ?>
        <ul class="hobbies-list">
          <?php while($hb = $hobbiesResult->fetch_assoc()): ?>
            <li><strong><?php echo e($hb['hobby_name']); ?></strong><?php if($hb['description']): ?> — <?php echo e($hb['description']); ?><?php endif; ?></li>
          <?php endwhile; ?>
        </ul>
      <?php else: ?>
        <p class="text-muted">No hobbies listed.</p>
      <?php endif; ?>
    </section>
  </div>

  <div class="main-content">
    <!-- Summary -->
    <section class="main-section">
      <h2>📋 Professional Summary</h2>
      <p><?php echo $cv['profile_summary'] ? nl2br(e($cv['profile_summary'])) : 'Profile summary not available.'; ?></p>
    </section>

    <!-- Work Experience -->
    <section class="main-section">
      <h2>💼 Work Experience</h2>
      <?php if($workResult->num_rows > 0): ?>
        <?php while($w = $workResult->fetch_assoc()): ?>
          <div class="experience-item">
            <h3><?php echo e($w['job_title']); ?></h3>
            <p class="text-muted">
              <strong><?php echo e($w['company_name']); ?></strong>
              <?php if($w['work_start'] || $w['work_end']): ?>
                | <?php echo e($w['work_start']); ?> <?php if($w['work_end']): ?> – <?php echo e($w['work_end']); ?><?php else: ?> – Present<?php endif; ?>
              <?php endif; ?>
            </p>
            <?php if(!empty($w['description'])): ?>
              <p><?php echo nl2br(e($w['description'])); ?></p>
            <?php endif; ?>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p class="text-muted">No work experience available.</p>
      <?php endif; ?>
    </section>

    <!-- Education -->
    <section class="main-section">
      <h2>🎓 Education</h2>
      <?php if($educationResult->num_rows > 0): ?>
        <?php while($edu = $educationResult->fetch_assoc()): ?>
          <div class="education-item">
            <h3><?php echo e($edu['degree']); ?></h3>
            <p class="text-muted">
              <strong><?php echo e($edu['institution']); ?></strong>
              <?php if($edu['education_start'] || $edu['education_end']): ?>
                | <?php echo e($edu['education_start']); ?> <?php if($edu['education_end']): ?> – <?php echo e($edu['education_end']); ?><?php else: ?> – Present<?php endif; ?>
              <?php endif; ?>
            </p>
            <?php if(!empty($edu['description'])): ?>
              <p><?php echo nl2br(e($edu['description'])); ?></p>
            <?php endif; ?>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p class="text-muted">No education data available.</p>
      <?php endif; ?>
    </section>
  </div>
</div>

</body>
</html>
