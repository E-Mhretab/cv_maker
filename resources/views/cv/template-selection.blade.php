<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New CV - Template Selection</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .template-card {
            transition: all 0.3s ease;
            cursor: pointer;
            border: 2px solid transparent;
        }
        .template-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
            border-color: #0d6efd;
        }
        .template-card.selected {
            border-color: #0d6efd;
            background-color: #f8f9fa;
        }
        .template-preview {
            height: 200px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 3rem;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body class="bg-light">
    <!-- Navigation -->
    <nav class="navbar navbar-dark bg-primary">
        <div class="container">
            <a href="{{ route('home') }}" class="navbar-brand">
                <i class="fas fa-arrow-left me-2"></i>Back to Home
            </a>
        </div>
    </nav>

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h2 class="h4 mb-0">
                            <i class="fas fa-plus-circle me-2"></i>Create New CV
                        </h2>
                        <p class="mb-0 mt-2">Step 1: Choose a template for your CV</p>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('cv.create.form') }}" method="POST" id="templateForm">
                            <div class="row">
                                <!-- Nathan Template -->
                                <div class="col-md-6 mb-4">
                                    <div class="template-card card h-100" onclick="selectTemplate('nathan')">
                                        <div class="card-body text-center">
                                            <div class="template-preview">
                                                <i class="fas fa-user"></i>
                                            </div>
                                            <h5 class="card-title">Nathan Template</h5>
                                            <p class="card-text text-muted">
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
                                    <div class="template-card card h-100" onclick="selectTemplate('esey')">
                                        <div class="card-body text-center">
                                            <div class="template-preview" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                                                <i class="fas fa-graduation-cap"></i>
                                            </div>
                                            <h5 class="card-title">Esey Template</h5>
                                            <p class="card-text text-muted">
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

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
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
        }

        // Form validation
        document.getElementById('templateForm').addEventListener('submit', function(e) {
            const selectedTemplate = document.querySelector('input[name="template_type"]:checked');
            if (!selectedTemplate) {
                e.preventDefault();
                alert('Please select a template before continuing.');
            }
        });
    </script>
</body>
</html>
