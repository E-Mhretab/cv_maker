<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create CV - {{ ucfirst($templateType) }} Template</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Inter', sans-serif;
        }
        
        .form-section {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            border: 1px solid rgba(255,255,255,0.2);
        }
        
        .section-header {
            color: #0d6efd;
            border-bottom: 3px solid #0d6efd;
            padding-bottom: 0.75rem;
            margin-bottom: 1.5rem;
            font-weight: 700;
            font-size: 1.25rem;
        }
        
        .dynamic-section {
            border: 2px dashed #dee2e6;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            background: #f8f9fa;
            transition: all 0.3s ease;
        }
        
        .dynamic-section:hover {
            border-color: #0d6efd;
            background: #f0f8ff;
        }
        
        .btn-add {
            background: linear-gradient(135deg, #28a745, #20c997);
            border: none;
            color: white;
            border-radius: 25px;
            padding: 8px 20px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-add:hover {
            background: linear-gradient(135deg, #218838, #1ea085);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.4);
        }
        
        .btn-remove {
            background: linear-gradient(135deg, #dc3545, #fd7e14);
            border: none;
            color: white;
            border-radius: 20px;
            padding: 6px 15px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-remove:hover {
            background: linear-gradient(135deg, #c82333, #e55a00);
            color: white;
            transform: translateY(-1px);
        }
        
        .main-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            border: none;
            border-radius: 20px 20px 0 0 !important;
            padding: 2rem;
        }
        
        .card-header h2 {
            font-size: 2rem;
            font-weight: 800;
            color: white;
        }
        
        .card-header p {
            font-size: 1.1rem;
            opacity: 0.9;
            color: white;
        }
        
        .form-control, .form-select {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 12px 16px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 50px;
            padding: 12px 30px;
            font-weight: 600;
            font-size: 1.1rem;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.6);
            background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
        }
        
        .btn-secondary {
            background: linear-gradient(135deg, #6c757d, #495057);
            border: none;
            border-radius: 50px;
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-secondary:hover {
            transform: translateY(-2px);
            background: linear-gradient(135deg, #5a6268, #343a40);
        }
        
        .navbar {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .navbar-brand {
            color: #2c3e50 !important;
            font-weight: 700;
        }
        
        .form-label {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }
        
        .form-text {
            color: #6c757d;
            font-size: 0.875rem;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a href="{{ route('cvs.create') }}" class="navbar-brand">
                <i class="fas fa-arrow-left me-2"></i>Back to Template Selection
            </a>
        </div>
    </nav>

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card main-card shadow">
                    <div class="card-header text-white">
                        <h2 class="h4 mb-0">
                            <i class="fas fa-edit me-2"></i>Create CV - {{ ucfirst($templateType) }} Template
                        </h2>
                        <p class="mb-0 mt-2">Step 2: Fill in your CV information</p>
                    </div>
                    <div class="card-body p-0">
                        <form action="http://127.0.0.1:8000/cvs" method="POST" id="cvForm" onsubmit="console.log('Form onsubmit triggered'); return true;">
                        <!-- Debug: Form action should be /cvs -->
                        <script>
                            console.log('Form action URL:', '/cvs');
                            console.log('Current page URL:', window.location.href);
                        </script>
                            @csrf
                            <input type="hidden" name="template_type" value="{{ $templateId }}">
                            
                            <!-- Personal Information -->
                            <div class="form-section">
                                <h3 class="section-header">
                                    <i class="fas fa-user me-2"></i>Personal Information
                                </h3>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label">Full Name *</label>
                                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                                        @error('name')
                                            <div class="text-danger mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label">Email Address *</label>
                                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                                        @error('email')
                                            <div class="text-danger mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="phone_number" class="form-label">Phone Number</label>
                                        <input type="text" class="form-control" id="phone_number" name="phone_number" value="{{ old('phone_number') }}" placeholder="+1234567890 or 1234567890">
                                        <div class="form-text">Format: +1234567890 or 1234567890 (7-15 digits)</div>
                                        @error('phone_number')
                                            <div class="text-danger mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="date_of_birth" class="form-label">Date of Birth</label>
                                        <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth') }}">
                                        @error('date_of_birth')
                                            <div class="text-danger mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="address" class="form-label">Address</label>
                                        <input type="text" class="form-control" id="address" name="address" value="{{ old('address') }}" placeholder="Your full address">
                                        @error('address')
                                            <div class="text-danger mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="linkedin_profile" class="form-label">LinkedIn Profile</label>
                                        <input type="url" class="form-control" id="linkedin_profile" name="linkedin_profile" value="{{ old('linkedin_profile') }}" placeholder="https://linkedin.com/in/yourprofile">
                                        @error('linkedin_profile')
                                            <div class="text-danger mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="portfolio" class="form-label">Portfolio Website</label>
                                        <input type="url" class="form-control" id="portfolio" name="portfolio" value="{{ old('portfolio') }}" placeholder="https://yourportfolio.com">
                                        @error('portfolio')
                                            <div class="text-danger mt-2">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Profile Summary -->
                            <div class="form-section">
                                <h3 class="section-header">
                                    <i class="fas fa-user-circle me-2"></i>Profile Summary
                                </h3>
                                <div class="mb-3">
                                    <label for="profile_summary" class="form-label">Professional Summary *</label>
                                    <textarea class="form-control" id="profile_summary" name="profile_summary" rows="4" required placeholder="Write a brief summary of your professional background, skills, and career objectives...">{{ old('profile_summary') }}</textarea>
                                    @error('profile_summary')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Publication Settings -->
                            <div class="form-section">
                                <h3 class="section-header">
                                    <i class="fas fa-globe me-2"></i>Publication Settings
                                </h3>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_public" id="is_public" 
                                           value="1" {{ old('is_public') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_public">
                                        Make this CV public (visible to others)
                                    </label>
                                </div>
                            </div>

                            <!-- Work Experience -->
                            <div class="form-section">
                                <h3 class="section-header">
                                    <i class="fas fa-briefcase me-2"></i>Work Experience
                                </h3>
                                <div id="workExperienceContainer">
                                    <div class="dynamic-section work-entry">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Job Title</label>
                                                <input type="text" class="form-control" name="work_title[]" placeholder="e.g., Software Developer">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Company Name</label>
                                                <input type="text" class="form-control" name="work_company[]" placeholder="e.g., Tech Company Inc.">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Start Date</label>
                                                <input type="date" class="form-control" name="work_start[]">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">End Date</label>
                                                <input type="date" class="form-control" name="work_end[]">
                                            </div>
                                            <div class="col-md-12 mb-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="work_current[]" value="1">
                                                    <label class="form-check-label">I currently work here</label>
                                                </div>
                                            </div>
                                            <div class="col-md-12 mb-3">
                                                <label class="form-label">Description</label>
                                                <textarea class="form-control" name="work_description[]" rows="3" placeholder="Describe your responsibilities and achievements..."></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-add btn-sm" onclick="addWorkExperience()">
                                    <i class="fas fa-plus me-1"></i>Add Work Experience
                                </button>
                            </div>

                            <!-- Education -->
                            <div class="form-section">
                                <h3 class="section-header">
                                    <i class="fas fa-graduation-cap me-2"></i>Education
                                </h3>
                                <div id="educationContainer">
                                    <div class="dynamic-section education-entry">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Degree</label>
                                                <input type="text" class="form-control" name="education_degree[]" placeholder="e.g., Bachelor of Science">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Institution</label>
                                                <input type="text" class="form-control" name="education_institution[]" placeholder="e.g., University Name">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Start Date</label>
                                                <input type="date" class="form-control" name="education_start[]">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">End Date</label>
                                                <input type="date" class="form-control" name="education_end[]">
                                            </div>
                                            <div class="col-md-12 mb-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="education_current[]" value="1">
                                                    <label class="form-check-label">I am currently studying</label>
                                                </div>
                                            </div>
                                            <div class="col-md-12 mb-3">
                                                <label class="form-label">Description</label>
                                                <textarea class="form-control" name="education_description[]" rows="3" placeholder="Describe your academic achievements, relevant coursework, or projects..."></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-add btn-sm" onclick="addEducation()">
                                    <i class="fas fa-plus me-1"></i>Add Education
                                </button>
                            </div>

                            <!-- Skills -->
                            <div class="form-section">
                                <h3 class="section-header">
                                    <i class="fas fa-cogs me-2"></i>Skills
                                </h3>
                                <div id="skillsContainer">
                                    <div class="dynamic-section skill-entry">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Skill Name</label>
                                                <input type="text" class="form-control" name="skill_name[]" placeholder="e.g., JavaScript, Project Management">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Description</label>
                                                <input type="text" class="form-control" name="skill_description[]" placeholder="Optional description">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-add btn-sm" onclick="addSkill()">
                                    <i class="fas fa-plus me-1"></i>Add Skill
                                </button>
                            </div>

                            <!-- Languages -->
                            <div class="form-section">
                                <h3 class="section-header">
                                    <i class="fas fa-language me-2"></i>Languages
                                </h3>
                                <div id="languagesContainer">
                                    <div class="dynamic-section language-entry">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Language</label>
                                                <input type="text" class="form-control" name="language_name[]" placeholder="e.g., English, Spanish">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Proficiency Level</label>
                                                <select class="form-select" name="language_proficiency[]">
                                                    <option value="basic">Basic</option>
                                                    <option value="conversational">Conversational</option>
                                                    <option value="fluent">Fluent</option>
                                                    <option value="native">Native</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-add btn-sm" onclick="addLanguage()">
                                    <i class="fas fa-plus me-1"></i>Add Language
                                </button>
                            </div>

                            <!-- Hobbies & Interests -->
                            <div class="form-section">
                                <h3 class="section-header">
                                    <i class="fas fa-heart me-2"></i>Hobbies & Interests
                                </h3>
                                <div id="hobbiesContainer">
                                    <div class="dynamic-section hobby-entry">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Hobby/Interest</label>
                                                <input type="text" class="form-control" name="hobby_name[]" placeholder="e.g., Photography, Reading">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Description</label>
                                                <input type="text" class="form-control" name="hobby_description[]" placeholder="Optional description">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-add btn-sm" onclick="addHobby()">
                                    <i class="fas fa-plus me-1"></i>Add Hobby
                                </button>
                            </div>

                            <!-- Form Actions -->
                            <div class="form-section">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('cvs.create') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left me-2"></i>Back to Template Selection
                                    </a>
                                    <div>
                                        <button type="button" class="btn btn-warning me-2" onclick="testRoute()">
                                            <i class="fas fa-test-tube me-2"></i>Test Route
                                        </button>
                                        <button type="submit" class="btn btn-primary btn-lg">
                                            <i class="fas fa-save me-2"></i>Create CV
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        let workExperienceCount = 1;
        let educationCount = 1;
        let skillsCount = 1;
        let languagesCount = 1;
        let hobbiesCount = 1;

        function addWorkExperience() {
            const container = document.getElementById('workExperienceContainer');
            const newEntry = document.createElement('div');
            newEntry.className = 'dynamic-section work-entry';
            newEntry.innerHTML = `
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Job Title</label>
                        <input type="text" class="form-control" name="work_title[]" placeholder="e.g., Software Developer">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Company Name</label>
                        <input type="text" class="form-control" name="work_company[]" placeholder="e.g., Tech Company Inc.">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Start Date</label>
                        <input type="date" class="form-control" name="work_start[]">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">End Date</label>
                        <input type="date" class="form-control" name="work_end[]">
                    </div>
                    <div class="col-md-12 mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="work_current[]" value="1">
                            <label class="form-check-label">I currently work here</label>
                        </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="work_description[]" rows="3" placeholder="Describe your responsibilities and achievements..."></textarea>
                    </div>
                </div>
                <button type="button" class="btn btn-remove btn-sm" onclick="removeWorkExperience(this)">
                    <i class="fas fa-trash me-1"></i>Remove
                </button>
            `;
            container.appendChild(newEntry);
            workExperienceCount++;
        }

        function removeWorkExperience(button) {
            button.parentElement.remove();
        }

        function addEducation() {
            const container = document.getElementById('educationContainer');
            const newEntry = document.createElement('div');
            newEntry.className = 'dynamic-section education-entry';
            newEntry.innerHTML = `
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Degree</label>
                        <input type="text" class="form-control" name="education_degree[]" placeholder="e.g., Bachelor of Science">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Institution</label>
                        <input type="text" class="form-control" name="education_institution[]" placeholder="e.g., University Name">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Start Date</label>
                        <input type="date" class="form-control" name="education_start[]">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">End Date</label>
                        <input type="date" class="form-control" name="education_end[]">
                    </div>
                    <div class="col-md-12 mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="education_current[]" value="1">
                            <label class="form-check-label">I am currently studying</label>
                        </div>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="education_description[]" rows="3" placeholder="Describe your academic achievements, relevant coursework, or projects..."></textarea>
                    </div>
                </div>
                <button type="button" class="btn btn-remove btn-sm" onclick="removeEducation(this)">
                    <i class="fas fa-trash me-1"></i>Remove
                </button>
            `;
            container.appendChild(newEntry);
            educationCount++;
        }

        function removeEducation(button) {
            button.parentElement.remove();
        }

        function addSkill() {
            const container = document.getElementById('skillsContainer');
            const newEntry = document.createElement('div');
            newEntry.className = 'dynamic-section skill-entry';
            newEntry.innerHTML = `
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Skill Name</label>
                        <input type="text" class="form-control" name="skill_name[]" placeholder="e.g., JavaScript, Project Management">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Description</label>
                        <input type="text" class="form-control" name="skill_description[]" placeholder="Optional description">
                    </div>
                </div>
                <button type="button" class="btn btn-remove btn-sm" onclick="removeSkill(this)">
                    <i class="fas fa-trash me-1"></i>Remove
                </button>
            `;
            container.appendChild(newEntry);
            skillsCount++;
        }

        function removeSkill(button) {
            button.parentElement.remove();
        }

        function addLanguage() {
            const container = document.getElementById('languagesContainer');
            const newEntry = document.createElement('div');
            newEntry.className = 'dynamic-section language-entry';
            newEntry.innerHTML = `
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Language</label>
                        <input type="text" class="form-control" name="language_name[]" placeholder="e.g., English, Spanish">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Proficiency Level</label>
                        <select class="form-select" name="language_proficiency[]">
                            <option value="basic">Basic</option>
                            <option value="conversational">Conversational</option>
                            <option value="fluent">Fluent</option>
                            <option value="native">Native</option>
                        </select>
                    </div>
                </div>
                <button type="button" class="btn btn-remove btn-sm" onclick="removeLanguage(this)">
                    <i class="fas fa-trash me-1"></i>Remove
                </button>
            `;
            container.appendChild(newEntry);
            languagesCount++;
        }

        function removeLanguage(button) {
            button.parentElement.remove();
        }

        function addHobby() {
            const container = document.getElementById('hobbiesContainer');
            const newEntry = document.createElement('div');
            newEntry.className = 'dynamic-section hobby-entry';
            newEntry.innerHTML = `
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Hobby/Interest</label>
                        <input type="text" class="form-control" name="hobby_name[]" placeholder="e.g., Photography, Reading">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Description</label>
                        <input type="text" class="form-control" name="hobby_description[]" placeholder="Optional description">
                    </div>
                </div>
                <button type="button" class="btn btn-remove btn-sm" onclick="removeHobby(this)">
                    <i class="fas fa-trash me-1"></i>Remove
                </button>
            `;
            container.appendChild(newEntry);
            hobbiesCount++;
        }

        function removeHobby(button) {
            button.parentElement.remove();
        }

        // Test function to check if the route works
        function testRoute() {
            console.log('Testing route: http://127.0.0.1:8000/cvs');
            
            // Create a simple form data
            const formData = new FormData();
            formData.append('_token', document.querySelector('input[name="_token"]').value);
            formData.append('template_type', '1');
            formData.append('name', 'Test User');
            formData.append('email', 'test@example.com');
            formData.append('profile_summary', 'Test summary');
            
            // Try to submit via fetch
            fetch('http://127.0.0.1:8000/cvs', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                console.log('Response status:', response.status);
                console.log('Response URL:', response.url);
                if (response.redirected) {
                    console.log('Redirected to:', response.url);
                }
                return response.text();
            })
            .then(data => {
                console.log('Response data:', data);
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }

        // Form validation - TEMPORARILY DISABLED FOR DEBUGGING
        document.getElementById('cvForm').addEventListener('submit', function(e) {
            console.log('Form submission started');
            console.log('Form action:', this.action);
            console.log('Form method:', this.method);
            console.log('Current URL:', window.location.href);
            
            // TEMPORARILY DISABLE VALIDATION TO TEST FORM SUBMISSION
            console.log('Form validation disabled - allowing submission...');
            return true;
        });

        // Helper functions for form validation
        function showFieldError(fieldId, message) {
            const field = typeof fieldId === 'string' ? document.getElementById(fieldId) : fieldId;
            if (!field) return;

            // Add error class
            field.classList.add('is-invalid');

            // Create or update error message
            let errorDiv = field.parentNode.querySelector('.invalid-feedback');
            if (!errorDiv) {
                errorDiv = document.createElement('div');
                errorDiv.className = 'invalid-feedback';
                field.parentNode.appendChild(errorDiv);
            }
            errorDiv.textContent = message;
        }

        function clearErrorMessages() {
            // Remove error classes and messages
            document.querySelectorAll('.is-invalid').forEach(field => {
                field.classList.remove('is-invalid');
            });
            document.querySelectorAll('.invalid-feedback').forEach(errorDiv => {
                errorDiv.remove();
            });
        }

        // Real-time validation for phone number
        document.getElementById('phone_number').addEventListener('input', function() {
            const phoneNumber = this.value.trim();
            if (phoneNumber) {
                const phoneRegex = /^\+?[0-9]{7,15}$/;
                if (!phoneRegex.test(phoneNumber)) {
                    this.classList.add('is-invalid');
                    let errorDiv = this.parentNode.querySelector('.invalid-feedback');
                    if (!errorDiv) {
                        errorDiv = document.createElement('div');
                        errorDiv.className = 'invalid-feedback';
                        this.parentNode.appendChild(errorDiv);
                    }
                    errorDiv.textContent = 'Please enter a valid phone number (7-15 digits, optional + at start)';
                } else {
                    this.classList.remove('is-invalid');
                    const errorDiv = this.parentNode.querySelector('.invalid-feedback');
                    if (errorDiv) {
                        errorDiv.remove();
                    }
                }
            } else {
                this.classList.remove('is-invalid');
                const errorDiv = this.parentNode.querySelector('.invalid-feedback');
                if (errorDiv) {
                    errorDiv.remove();
                }
            }
        });
    </script>
</body>
</html>