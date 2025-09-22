<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New CV - Template Selection</title>
    
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
    }
    
    .template-card {
        transition: all 0.3s ease;
        cursor: pointer;
        border: 2px solid transparent;
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    
    .template-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.2);
        border-color: #0d6efd;
    }
    
    .template-card.selected {
        border-color: #0d6efd;
        background-color: #f8f9fa;
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    }
    
    .template-preview {
        height: 200px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 4rem;
        margin: 0;
        position: relative;
        overflow: hidden;
    }
    
    .template-preview::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(45deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%);
        pointer-events: none;
    }
    
    .template-preview.esey {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }
    
    .template-preview.mirian {
        background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
    }
    
    .card-body {
        padding: 2rem;
        text-align: center;
    }
    
    .card-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #2c3e50;
        margin: 1rem 0;
    }
    
    .card-text {
        color: #6c757d;
        line-height: 1.6;
        margin-bottom: 1.5rem;
    }
    
    .form-check {
        margin-top: 1rem;
    }
    
    .form-check-input {
        width: 1.2rem;
        height: 1.2rem;
    }
    
    .form-check-label {
        font-weight: 600;
        color: #0d6efd;
        cursor: pointer;
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
    
    .navbar {
        background: rgba(255, 255, 255, 0.95) !important;
        backdrop-filter: blur(10px);
        border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .navbar-brand {
        color: #2c3e50 !important;
        font-weight: 700;
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
    }
    
    .card-header p {
        font-size: 1.1rem;
        opacity: 0.9;
    }
</style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a href="{{ route('welcome') }}" class="navbar-brand">
                <i class="fas fa-arrow-left me-2"></i>Back to Home
            </a>
        </div>
    </nav>

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card main-card shadow">
                    <div class="card-header text-white">
                        <h2 class="h4 mb-0">
                            <i class="fas fa-plus-circle me-2"></i>Create New CV
                        </h2>
                        <p class="mb-0 mt-2">Step 1: Choose a template for your CV</p>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('cvs.create-form') }}" method="POST" id="templateForm">
                            @csrf
                            <div class="row">
                                <!-- Nathan Template -->
                                <div class="col-md-6 mb-4">
                                    <div class="template-card" onclick="selectTemplate('nathan')">
                                        <div class="template-preview">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <div class="card-body">
                                            <h5 class="card-title">Nathan Template</h5>
                                            <p class="card-text">
                                                Modern sidebar layout with Bootstrap styling. 
                                                Perfect for software developers and technical professionals.
                                            </p>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="template_type" value="nathan" id="template_nathan" required>
                                                <label class="form-check-label" for="template_nathan">
                                                    Select Nathan Template
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Esey Template -->
                                <div class="col-md-6 mb-4">
                                    <div class="template-card" onclick="selectTemplate('esey')">
                                        <div class="template-preview esey">
                                            <i class="fas fa-graduation-cap"></i>
                                        </div>
                                        <div class="card-body">
                                            <h5 class="card-title">Esey Template</h5>
                                            <p class="card-text">
                                                Clean single-column layout with card-based design. 
                                                Ideal for students and general professionals.
                                            </p>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="template_type" value="esey" id="template_esey" required>
                                                <label class="form-check-label" for="template_esey">
                                                    Select Esey Template
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-arrow-right me-2"></i>Continue to CV Form
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function selectTemplate(templateType) {
            // Remove selected class from all cards
            document.querySelectorAll('.template-card').forEach(card => {
                card.classList.remove('selected');
            });
            
            // Add selected class to clicked card
            event.currentTarget.classList.add('selected');
            
            // Check the corresponding radio button
            document.getElementById('template_' + templateType).checked = true;
            
            // Add visual feedback
            event.currentTarget.style.transform = 'translateY(-5px)';
            setTimeout(() => {
                event.currentTarget.style.transform = '';
            }, 200);
        }

        // Form validation
        document.getElementById('templateForm').addEventListener('submit', function(e) {
            const selectedTemplate = document.querySelector('input[name="template_type"]:checked');
            if (!selectedTemplate) {
                e.preventDefault();
                alert('Please select a template before continuing.');
                return;
            }
            
            // Add loading state to button
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Loading...';
            submitBtn.disabled = true;
            
            // Re-enable after a short delay (in case of errors)
            setTimeout(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }, 3000);
        });

        // Add hover effects
        document.querySelectorAll('.template-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                if (!this.classList.contains('selected')) {
                    this.style.transform = 'translateY(-3px)';
                }
            });
            
            card.addEventListener('mouseleave', function() {
                if (!this.classList.contains('selected')) {
                    this.style.transform = 'translateY(0)';
                }
            });
        });
    </script>
</html>
