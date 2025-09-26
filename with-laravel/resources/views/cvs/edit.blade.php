<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit CV - {{ $cv->name }}</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
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
            <a href="{{ route('cvs.index') }}" class="navbar-brand fw-bold">
                <i class="fas fa-arrow-left me-2"></i>Back to CV List
            </a>
            <span class="navbar-text fw-semibold">
                <i class="fas fa-edit me-2"></i>Edit CV
            </span>
            <div class="navbar-nav ms-auto">
                <div class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user me-1"></i>{{ auth()->user()->name }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="fas fa-user me-2"></i>My Profile</a></li>
                        <li><a class="dropdown-item" href="{{ route('welcome') }}"><i class="fas fa-home me-2"></i>Homepage</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item"><i class="fas fa-sign-out-alt me-2"></i>Logout</button>
                            </form>
                        </li>
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
                        <i class="fas fa-edit me-2"></i>Edit CV: {{ $cv->name }}
                    </h2>
                    <p class="mb-0">Using {{ ucfirst($cv->metadata->template_type ?? 'nathan') }} Template</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <a href="{{ route('cvs.preview', $cv) }}" class="btn btn-light">
                        <i class="fas fa-eye me-1"></i>Preview CV
                    </a>
                </div>
            </div>
        </div>

        @if ($errors->any())
        <div class="alert alert-danger" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('cvs.update', $cv) }}" method="POST" id="cvEditForm">
            @csrf
            @method('PUT')
            <input type="hidden" name="template_type" value="{{ $cv->metadata->template_type ?? 'nathan' }}">
            
            <!-- Personal Information -->
            <div class="form-section">
                <h3 class="section-header">
                    <i class="fas fa-user me-2"></i>Personal Information
                </h3>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">Full Name *</label>
                        <input type="text" class="form-control" id="name" name="name" 
                               value="{{ old('name', $cv->name) }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email Address *</label>
                        <input type="email" class="form-control" id="email" name="email" 
                               value="{{ old('email', $cv->email) }}" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="phone_number" class="form-label">Phone Number</label>
                        <input type="tel" class="form-control" id="phone_number" name="phone_number" 
                               value="{{ old('phone_number', $cv->phone_number) }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="date_of_birth" class="form-label">Date of Birth</label>
                        <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" 
                               value="{{ old('date_of_birth', $cv->date_of_birth) }}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="address" class="form-label">Address</label>
                        <input type="text" class="form-control" id="address" name="address" 
                               value="{{ old('address', $cv->address) }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="linkedin_profile" class="form-label">LinkedIn Profile</label>
                        <input type="url" class="form-control" id="linkedin_profile" name="linkedin_profile" 
                               value="{{ old('linkedin_profile', $cv->linkedin_profile) }}">
                    </div>
                </div>
                <div class="mb-3">
                    <label for="portfolio" class="form-label">Portfolio Website</label>
                    <input type="url" class="form-control" id="portfolio" name="portfolio" 
                           value="{{ old('portfolio', $cv->portfolio) }}">
                </div>
            </div>

            <!-- Profile Summary -->
            <div class="form-section">
                <h3 class="section-header">
                    <i class="fas fa-file-alt me-2"></i>Profile Summary
                </h3>
                <div class="mb-3">
                    <label for="profile_summary" class="form-label">Professional Summary *</label>
                    <textarea class="form-control" id="profile_summary" name="profile_summary" rows="4" required>{{ old('profile_summary', $cv->profile_summary) }}</textarea>
                </div>
            </div>

            <!-- Work Experience -->
            <div class="form-section">
                <h3 class="section-header">
                    <i class="fas fa-briefcase me-2"></i>Work Experience
                </h3>
                <div id="workExperienceContainer">
                    @if($cv->workExperiences->count() > 0)
                        @foreach($cv->workExperiences as $index => $work)
                        <div class="dynamic-section work-entry">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Job Title</label>
                                    <input type="text" class="form-control" name="work_title[]" 
                                           value="{{ old('work_title.'.$index, $work->job_title) }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Company Name</label>
                                    <input type="text" class="form-control" name="work_company[]" 
                                           value="{{ old('work_company.'.$index, $work->company_name) }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Start Date</label>
                                    <input type="date" class="form-control" name="work_start[]" 
                                           value="{{ old('work_start.'.$index, $work->work_start) }}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">End Date</label>
                                    <input type="date" class="form-control" name="work_end[]" 
                                           value="{{ old('work_end.'.$index, $work->work_end) }}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Currently Working</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="work_current[]" value="1" 
                                               {{ old('work_current.'.$index, $work->is_current) ? 'checked' : '' }}>
                                        <label class="form-check-label">Yes</label>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" name="work_description[]" rows="3">{{ old('work_description.'.$index, $work->description) }}</textarea>
                            </div>
                            <button type="button" class="btn btn-remove btn-sm" onclick="removeWorkEntry(this)">
                                <i class="fas fa-trash me-1"></i>Remove Entry
                            </button>
                        </div>
                        @endforeach
                    @else
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
                    @endif
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
                    @if($cv->education->count() > 0)
                        @foreach($cv->education as $index => $education)
                        <div class="dynamic-section education-entry">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Degree/Qualification</label>
                                    <input type="text" class="form-control" name="education_degree[]" 
                                           value="{{ old('education_degree.'.$index, $education->degree) }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Institution</label>
                                    <input type="text" class="form-control" name="education_institution[]" 
                                           value="{{ old('education_institution.'.$index, $education->institution) }}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Start Date</label>
                                    <input type="date" class="form-control" name="education_start[]" 
                                           value="{{ old('education_start.'.$index, $education->education_start) }}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">End Date</label>
                                    <input type="date" class="form-control" name="education_end[]" 
                                           value="{{ old('education_end.'.$index, $education->education_end) }}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Currently Studying</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="education_current[]" value="1" 
                                               {{ old('education_current.'.$index, $education->is_current) ? 'checked' : '' }}>
                                        <label class="form-check-label">Yes</label>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" name="education_description[]" rows="3">{{ old('education_description.'.$index, $education->description) }}</textarea>
                            </div>
                            <button type="button" class="btn btn-remove btn-sm" onclick="removeEducationEntry(this)">
                                <i class="fas fa-trash me-1"></i>Remove Entry
                            </button>
                        </div>
                        @endforeach
                    @else
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
                    @endif
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
                    @if($cv->skills->count() > 0)
                        @foreach($cv->skills as $index => $skill)
                        <div class="dynamic-section skill-entry">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Skill Name</label>
                                    <input type="text" class="form-control" name="skill_name[]" 
                                           value="{{ old('skill_name.'.$index, $skill->skill_name) }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Description</label>
                                    <input type="text" class="form-control" name="skill_description[]" 
                                           value="{{ old('skill_description.'.$index, $skill->description) }}">
                                </div>
                            </div>
                            <button type="button" class="btn btn-remove btn-sm" onclick="removeSkillEntry(this)">
                                <i class="fas fa-trash me-1"></i>Remove Entry
                            </button>
                        </div>
                        @endforeach
                    @else
                        <!-- Empty skill entry for adding new skills -->
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
                            <button type="button" class="btn btn-remove btn-sm" onclick="removeSkillEntry(this)">
                                <i class="fas fa-trash me-1"></i>Remove Entry
                            </button>
                        </div>
                    @endif
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
                    @if($cv->languages->count() > 0)
                        @foreach($cv->languages as $index => $language)
                        <div class="dynamic-section language-entry">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Language</label>
                                    <input type="text" class="form-control" name="language_name[]" 
                                           value="{{ old('language_name.'.$index, $language->language_name) }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Proficiency Level</label>
                                    <select class="form-select" name="language_proficiency[]">
                                        <option value="basic" {{ old('language_proficiency.'.$index, $language->proficiency) == 'basic' ? 'selected' : '' }}>Basic</option>
                                        <option value="conversational" {{ old('language_proficiency.'.$index, $language->proficiency) == 'conversational' ? 'selected' : '' }}>Conversational</option>
                                        <option value="fluent" {{ old('language_proficiency.'.$index, $language->proficiency) == 'fluent' ? 'selected' : '' }}>Fluent</option>
                                        <option value="native" {{ old('language_proficiency.'.$index, $language->proficiency) == 'native' ? 'selected' : '' }}>Native</option>
                                    </select>
                                </div>
                            </div>
                            <button type="button" class="btn btn-remove btn-sm" onclick="removeLanguageEntry(this)">
                                <i class="fas fa-trash me-1"></i>Remove Entry
                            </button>
                        </div>
                        @endforeach
                    @else
                        <!-- Empty language entry for adding new languages -->
                        <div class="dynamic-section language-entry">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Language</label>
                                    <input type="text" class="form-control" name="language_name[]" placeholder="e.g., English, Spanish, French">
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
                            <button type="button" class="btn btn-remove btn-sm" onclick="removeLanguageEntry(this)">
                                <i class="fas fa-trash me-1"></i>Remove Entry
                            </button>
                        </div>
                    @endif
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
                    @if($cv->hobbies->count() > 0)
                        @foreach($cv->hobbies as $index => $hobby)
                        <div class="dynamic-section hobby-entry">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Hobby Name</label>
                                    <input type="text" class="form-control" name="hobby_name[]" 
                                           value="{{ old('hobby_name.'.$index, $hobby->hobby_name) }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Description</label>
                                    <input type="text" class="form-control" name="hobby_description[]" 
                                           value="{{ old('hobby_description.'.$index, $hobby->description) }}">
                                </div>
                            </div>
                            <button type="button" class="btn btn-remove btn-sm" onclick="removeHobbyEntry(this)">
                                <i class="fas fa-trash me-1"></i>Remove Entry
                            </button>
                        </div>
                        @endforeach
                    @else
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
                    @endif
                </div>
                <button type="button" class="btn btn-add" onclick="addHobbyEntry()">
                    <i class="fas fa-plus me-1"></i>Add Hobby
                </button>
            </div>

            <!-- Submit Buttons -->
            <div class="form-section">
                <div class="d-grid gap-2 d-md-flex justify-content-md-between">
                    <a href="{{ route('cvs.index') }}" class="btn btn-secondary btn-lg">
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

        // Skills Functions
        function addSkillEntry() {
            const container = document.getElementById('skillsContainer');
            let newEntry;
            
            // If there are existing entries, clone one
            if (container.querySelector('.skill-entry')) {
                newEntry = container.querySelector('.skill-entry').cloneNode(true);
                newEntry.querySelectorAll('input').forEach(input => input.value = '');
            } else {
                // Create new entry from scratch
                newEntry = document.createElement('div');
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
                    <button type="button" class="btn btn-remove btn-sm" onclick="removeSkillEntry(this)">
                        <i class="fas fa-trash me-1"></i>Remove Entry
                    </button>
                `;
            }
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
            let newEntry;
            
            // If there are existing entries, clone one
            if (container.querySelector('.language-entry')) {
                newEntry = container.querySelector('.language-entry').cloneNode(true);
                newEntry.querySelectorAll('input, select').forEach(input => {
                    if (input.type === 'text') input.value = '';
                    if (input.tagName === 'SELECT') input.selectedIndex = 0;
                });
            } else {
                // Create new entry from scratch
                newEntry = document.createElement('div');
                newEntry.className = 'dynamic-section language-entry';
                newEntry.innerHTML = `
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Language</label>
                            <input type="text" class="form-control" name="language_name[]" placeholder="e.g., English, Spanish, French">
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
                    <button type="button" class="btn btn-remove btn-sm" onclick="removeLanguageEntry(this)">
                        <i class="fas fa-trash me-1"></i>Remove Entry
                    </button>
                `;
            }
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
