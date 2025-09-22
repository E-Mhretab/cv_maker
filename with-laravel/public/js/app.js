// CV Maker JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Initialize popovers
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });

    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        var alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            var bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);

    // Form validation enhancement
    var forms = document.querySelectorAll('.needs-validation');
    Array.prototype.slice.call(forms).forEach(function(form) {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });

    // Dynamic form field management
    window.addWorkExperience = function() {
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
                    <input type="text" class="form-control" name="work_company[]" placeholder="e.g., Tech Corp">
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="form-label">Start Date</label>
                    <input type="date" class="form-control" name="work_start[]">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">End Date</label>
                    <input type="date" class="form-control" name="work_end[]">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Current Position</label>
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" name="work_current[]" value="1">
                        <label class="form-check-label">I currently work here</label>
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea class="form-control" name="work_description[]" rows="3" placeholder="Describe your responsibilities and achievements..."></textarea>
            </div>
            <button type="button" class="btn btn-remove btn-sm" onclick="removeWorkExperience(this)">
                <i class="fas fa-trash me-1"></i>Remove
            </button>
        `;
        container.appendChild(newEntry);
    };

    window.removeWorkExperience = function(button) {
        button.parentElement.remove();
    };

    // Similar functions for other dynamic sections
    window.addEducation = function() {
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
            </div>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="form-label">Start Date</label>
                    <input type="date" class="form-control" name="education_start[]">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">End Date</label>
                    <input type="date" class="form-control" name="education_end[]">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Current Student</label>
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" name="education_current[]" value="1">
                        <label class="form-check-label">I am currently studying</label>
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea class="form-control" name="education_description[]" rows="3" placeholder="Describe your academic achievements, relevant coursework, or projects..."></textarea>
            </div>
            <button type="button" class="btn btn-remove btn-sm" onclick="removeEducation(this)">
                <i class="fas fa-trash me-1"></i>Remove
            </button>
        `;
        container.appendChild(newEntry);
    };

    window.removeEducation = function(button) {
        button.parentElement.remove();
    };

    // Skills management
    window.addSkill = function() {
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
    };

    window.removeSkill = function(button) {
        button.parentElement.remove();
    };

    // Languages management
    window.addLanguage = function() {
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
    };

    window.removeLanguage = function(button) {
        button.parentElement.remove();
    };

    // Hobbies management
    window.addHobby = function() {
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
    };

    window.removeHobby = function(button) {
        button.parentElement.remove();
    };
});
