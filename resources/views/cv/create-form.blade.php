<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create CV - {{ ucfirst($templateType) }} Template</title>
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
    </style>
</head>
<body class="bg-light">
    <!-- Navigation -->
    <nav class="navbar navbar-dark bg-primary">
        <div class="container">
            <a href="{{ route('cv.create') }}" class="navbar-brand">
                <i class="fas fa-arrow-left me-2"></i>Back to Template Selection
            </a>
        </div>
    </nav>

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h2 class="h4 mb-0">
                            <i class="fas fa-edit me-2"></i>Create CV - {{ ucfirst($templateType) }} Template
                        </h2>
                        <p class="mb-0 mt-2">Step 2: Fill in your CV information</p>
                    </div>
                    <div class="card-body p-0">
                        <form action="{{ route('cv.store') }}" method="POST" id="cvForm">
                            @csrf
                            <input type="hidden" name="template_type" value="{{ $templateType }}">
                            
                            <!-- Personal Information -->
                            <div class="form-section">
                                <h3 class="section-header">
                                    <i class="fas fa-user me-2"></i>Personal Information
                                </h3>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label">Full Name *</label>
                                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label">Email Address *</label>
                                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="phone_number" class="form-label">Phone Number</label>
                                        <input type="tel" class="form-control" id="phone_number" name="phone_number" 
                                               placeholder="+1234567890 or 1234567890" 
                                               pattern="^\+?[0-9]{7,15}$" 
                                               title="Enter a valid phone number (7-15 digits, optional + at start)"
                                               value="{{ old('phone_number') }}">
                                        <div class="form-text">Format: +1234567890 or 1234567890 (7-15 digits)</div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="date_of_birth" class="form-label">Date of Birth</label>
                                        <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth') }}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="address" class="form-label">Address</label>
                                        <input type="text" class="form-control" id="address" name="address" value="{{ old('address') }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="linkedin_profile" class="form-label">LinkedIn Profile</label>
                                        <input type="url" class="form-control" id="linkedin_profile" name="linkedin_profile" value="{{ old('linkedin_profile') }}">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="portfolio" class="form-label">Portfolio Website</label>
                                    <input type="url" class="form-control" id="portfolio" name="portfolio" value="{{ old('portfolio') }}">
                                </div>
                            </div>

                            <!-- Profile Summary -->
                            <div class="form-section">
                                <h3 class="section-header">
                                    <i class="fas fa-file-alt me-2"></i>Profile Summary
                                </h3>
                                <div class="mb-3">
                                    <label for="profile_summary" class="form-label">Professional Summary *</label>
                                    <textarea class="form-control" id="profile_summary" name="profile_summary" rows="4" required placeholder="Write a brief summary of your professional background, skills, and career objectives...">{{ old('profile_summary') }}</textarea>
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
                                                <input type="text" class="form-control" name="work_title[]" value="{{ old('work_title.0') }}">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Company Name</label>
                                                <input type="text" class="form-control" name="work_company[]" value="{{ old('work_company.0') }}">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">Start Date</label>
                                                <input type="date" class="form-control" name="work_start[]" value="{{ old('work_start.0') }}">
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">End Date</label>
                                                <input type="date" class="form-control" name="work_end[]" value="{{ old('work_end.0') }}">
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">Currently Working</label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="work_current[]" value="1" {{ old('work_current.0') ? 'checked' : '' }}>
                                                    <label class="form-check-label">Yes</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Description</label>
                                            <textarea class="form-control" name="work_description[]" rows="3" placeholder="Describe your responsibilities and achievements...">{{ old('work_description.0') }}</textarea>
                                        </div>
                                        <button type="button" class="btn btn-remove btn-sm" onclick="removeWorkEntry(this)">
                                            <i class="fas fa-trash me-1"></i>Remove Entry
                                        </button>
                                    </div>
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
                                    <div class="dynamic-section education-entry">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Degree/Qualification</label>
                                                <input type="text" class="form-control" name="education_degree[]" value="{{ old('education_degree.0') }}">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Institution</label>
                                                <input type="text" class="form-control" name="education_institution[]" value="{{ old('education_institution.0') }}">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">Start Date</label>
                                                <input type="date" class="form-control" name="education_start[]" value="{{ old('education_start.0') }}">
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">End Date</label>
                                                <input type="date" class="form-control" name="education_end[]" value="{{ old('education_end.0') }}">
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label">Currently Studying</label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="education_current[]" value="1" {{ old('education_current.0') ? 'checked' : '' }}>
                                                    <label class="form-check-label">Yes</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Description</label>
                                            <textarea class="form-control" name="education_description[]" rows="3" placeholder="Additional details about your education...">{{ old('education_description.0') }}</textarea>
                                        </div>
                                        <button type="button" class="btn btn-remove btn-sm" onclick="removeEducationEntry(this)">
                                            <i class="fas fa-trash me-1"></i>Remove Entry
                                        </button>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-add" onclick="addEducationEntry()">
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
                                                <input type="text" class="form-control" name="skill_name[]" placeholder="e.g., PHP, JavaScript, Project Management" value="{{ old('skill_name.0') }}">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Description</label>
                                                <input type="text" class="form-control" name="skill_description[]" placeholder="Brief description of your proficiency..." value="{{ old('skill_description.0') }}">
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-remove btn-sm" onclick="removeSkillEntry(this)">
                                            <i class="fas fa-trash me-1"></i>Remove Entry
                                        </button>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-add" onclick="addSkillEntry()">
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
                                                <input type="text" class="form-control" name="language_name[]" placeholder="e.g., English, Spanish, French" value="{{ old('language_name.0') }}">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Proficiency Level</label>
                                                <select class="form-select" name="language_proficiency[]">
                                                    <option value="basic" {{ old('language_proficiency.0') == 'basic' ? 'selected' : '' }}>Basic</option>
                                                    <option value="conversational" {{ old('language_proficiency.0') == 'conversational' ? 'selected' : '' }}>Conversational</option>
                                                    <option value="fluent" {{ old('language_proficiency.0') == 'fluent' ? 'selected' : '' }}>Fluent</option>
                                                    <option value="native" {{ old('language_proficiency.0') == 'native' ? 'selected' : '' }}>Native</option>
                                                </select>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-remove btn-sm" onclick="removeLanguageEntry(this)">
                                            <i class="fas fa-trash me-1"></i>Remove Entry
                                        </button>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-add" onclick="addLanguageEntry()">
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
                                                <label class="form-label">Hobby Name</label>
                                                <input type="text" class="form-control" name="hobby_name[]" value="{{ old('hobby_name.0') }}">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label">Description</label>
                                                <input type="text" class="form-control" name="hobby_description[]" placeholder="Brief description of this hobby..." value="{{ old('hobby_description.0') }}">
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-remove btn-sm" onclick="removeHobbyEntry(this)">
                                            <i class="fas fa-trash me-1"></i>Remove Entry
                                        </button>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-add" onclick="addHobbyEntry()">
                                    <i class="fas fa-plus me-1"></i>Add Hobby
                                </button>
                            </div>

                            <!-- Publication Settings -->
                            <div class="form-section">
                                <h3 class="section-header">
                                    <i class="fas fa-globe me-2"></i>Publication Settings
                                </h3>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="is_public" name="is_public" value="1" {{ old('is_public') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_public">
                                                <strong>Make this CV public</strong>
                                            </label>
                                            <div class="form-text">
                                                <i class="fas fa-info-circle me-1"></i>
                                                Public CVs will be visible to everyone on the homepage. Private CVs are only visible to you.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="form-section">
                                <div class="d-grid gap-2 d-md-flex justify-content-md-between">
                                    <a href="{{ route('cv.create') }}" class="btn btn-secondary btn-lg">
                                        <i class="fas fa-arrow-left me-2"></i>Back to Templates
                                    </a>
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fas fa-save me-2"></i>Create CV
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
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

        // Skills Functions
        function addSkillEntry() {
            const container = document.getElementById('skillsContainer');
            const newEntry = container.querySelector('.skill-entry').cloneNode(true);
            newEntry.querySelectorAll('input').forEach(input => input.value = '');
            container.appendChild(newEntry);
        }

        function removeSkillEntry(button) {
            const container = document.getElementById('skillsContainer');
            if (container.children.length > 1) {
                button.closest('.skill-entry').remove();
            }
        }

        // Languages Functions
        function addLanguageEntry() {
            const container = document.getElementById('languagesContainer');
            const newEntry = container.querySelector('.language-entry').cloneNode(true);
            newEntry.querySelectorAll('input, select').forEach(input => {
                if (input.type === 'text') input.value = '';
                if (input.tagName === 'SELECT') input.selectedIndex = 0;
            });
            container.appendChild(newEntry);
        }

        function removeLanguageEntry(button) {
            const container = document.getElementById('languagesContainer');
            if (container.children.length > 1) {
                button.closest('.language-entry').remove();
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
        document.getElementById('cvForm').addEventListener('submit', function(e) {
            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            const summary = document.getElementById('profile_summary').value.trim();
            const phoneNumber = document.getElementById('phone_number').value.trim();

            // Clear previous error messages
            clearErrorMessages();

            let hasErrors = false;

            // Validate required fields
            if (!name) {
                showFieldError('name', 'Name is required');
                hasErrors = true;
            }

            if (!email) {
                showFieldError('email', 'Email address is required');
                hasErrors = true;
            }

            if (!summary) {
                showFieldError('profile_summary', 'Profile summary is required');
                hasErrors = true;
            }

            // Validate email format
            if (email) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(email)) {
                    showFieldError('email', 'Please enter a valid email address');
                    hasErrors = true;
                }
            }

            // Validate phone number format
            if (phoneNumber) {
                const phoneRegex = /^\+?[0-9]{7,15}$/;
                if (!phoneRegex.test(phoneNumber)) {
                    showFieldError('phone_number', 'Please enter a valid phone number (7-15 digits, optional + at start)');
                    hasErrors = true;
                }
            }

            // Validate work experience dates
            const workStartDates = document.querySelectorAll('input[name="work_start[]"]');
            const workEndDates = document.querySelectorAll('input[name="work_end[]"]');
            
            for (let i = 0; i < workStartDates.length; i++) {
                const startDate = workStartDates[i].value;
                const endDate = workEndDates[i].value;
                
                if (startDate && endDate && new Date(endDate) < new Date(startDate)) {
                    showFieldError(workEndDates[i], 'End date must be on or after the start date');
                    hasErrors = true;
                }
            }

            // Validate education dates
            const educationStartDates = document.querySelectorAll('input[name="education_start[]"]');
            const educationEndDates = document.querySelectorAll('input[name="education_end[]"]');
            
            for (let i = 0; i < educationStartDates.length; i++) {
                const startDate = educationStartDates[i].value;
                const endDate = educationEndDates[i].value;
                
                if (startDate && endDate && new Date(endDate) < new Date(startDate)) {
                    showFieldError(educationEndDates[i], 'End date must be on or after the start date');
                    hasErrors = true;
                }
            }

            if (hasErrors) {
                e.preventDefault();
                alert('Please fix the errors before submitting the form.');
                return;
            }
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
