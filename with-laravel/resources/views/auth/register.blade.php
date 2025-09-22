<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - CV Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .auth-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            overflow: hidden;
            max-width: 900px;
            width: 100%;
        }
        .auth-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        .auth-content {
            padding: 2rem;
        }
        .nav-tabs .nav-link {
            border: none;
            color: #6c757d;
            font-weight: 600;
        }
        .nav-tabs .nav-link.active {
            color: #667eea;
            border-bottom: 2px solid #667eea;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
        }
    </style>
</head>
<body class="auth-page">
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="auth-container">
            <div class="auth-header">
                <h1><i class="fas fa-user-shield me-2"></i>CV Management System</h1>
                <p class="mb-0">Sign in or create an account</p>
            </div>
            
            <div class="auth-content">
                @if ($errors->any())
                    <div class="auth-alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        @foreach ($errors->all() as $error)
                            {{ $error }}
                        @endforeach
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                @if (session('status'))
                    <div class="auth-alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                <ul class="nav nav-tabs auth-tabs" id="authTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="login-tab" data-bs-toggle="tab" data-bs-target="#login" type="button" role="tab" aria-selected="false">
                            <i class="fas fa-sign-in-alt me-2"></i>Login
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="register-tab" data-bs-toggle="tab" data-bs-target="#register" type="button" role="tab" aria-selected="true">
                            <i class="fas fa-user-plus me-2"></i>Register
                        </button>
                    </li>
                </ul>
                
                <div class="tab-content mt-4" id="authTabsContent">
                    <!-- Login Tab -->
                    <div class="tab-pane fade" id="login" role="tabpanel" aria-labelledby="login-tab">
                        <form method="POST" action="{{ route('login') }}" class="auth-form">
                            @csrf
                            
                            <div class="mb-3">
                                <label for="login_username" class="form-label">Username or Email</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    <input type="text" class="form-control" id="login_username" name="username" value="{{ old('username') }}" required autofocus>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                            </div>
                            
                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                <label class="form-check-label" for="remember">
                                    Remember me
                                </label>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-sign-in-alt me-2"></i>Login
                                </button>
                            </div>
                            
                            @if (Route::has('password.request'))
                                <div class="text-center mt-3">
                                    <a href="{{ route('password.request') }}" class="text-decoration-none">
                                        Forgot your password?
                                    </a>
                                </div>
                            @endif
                        </form>
                    </div>
                    
                    <!-- Register Tab -->
                    <div class="tab-pane fade show active" id="register" role="tabpanel" aria-labelledby="register-tab">
                        <form method="POST" action="{{ route('register') }}" class="auth-form">
                            @csrf
                            
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    <input type="text" class="form-control" id="username" name="username" value="{{ old('username') }}" required autofocus>
                                </div>
                                <div class="form-text">3-50 characters, letters, numbers, and underscores only</div>
                                @error('username')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="reg_email" class="form-label">Email</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    <input type="email" class="form-control" id="reg_email" name="email" value="{{ old('email') }}" required>
                                </div>
                                @error('email')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="reg_password" class="form-label">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control" id="reg_password" name="password" required>
                                </div>
                                <div class="form-text">Minimum 6 characters</div>
                                @error('password')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Confirm Password</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                                </div>
                                @error('password_confirmation')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-user-plus me-2"></i>Create Account
                                </button>
                            </div>
                            
                            <div class="text-center mt-3">
                                <a href="{{ route('login') }}" class="text-decoration-none">
                                    Already have an account? Login here
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Force Register tab to be active on page load
            const registerTab = document.getElementById('register-tab');
            const registerPane = document.getElementById('register');
            const loginTab = document.getElementById('login-tab');
            const loginPane = document.getElementById('login');
            
            // Remove all active classes first
            loginTab.classList.remove('active');
            loginPane.classList.remove('show', 'active');
            registerTab.classList.remove('active');
            registerPane.classList.remove('show', 'active');
            
            // Add active classes to register
            registerTab.classList.add('active');
            registerPane.classList.add('show', 'active');
            
            // Set aria-selected attributes
            loginTab.setAttribute('aria-selected', 'false');
            registerTab.setAttribute('aria-selected', 'true');
            
            // Focus on first input in register form
            setTimeout(function() {
                const firstInput = registerPane.querySelector('input[name="username"]');
                if (firstInput) {
                    firstInput.focus();
                }
            }, 100);
            
            // Ensure forms have correct actions
            const registerForm = registerPane.querySelector('form');
            const loginForm = loginPane.querySelector('form');
            
            if (registerForm) {
                registerForm.setAttribute('action', '{{ route("register") }}');
                registerForm.setAttribute('method', 'POST');
            }
            
            if (loginForm) {
                loginForm.setAttribute('action', '{{ route("login") }}');
                loginForm.setAttribute('method', 'POST');
            }
        });
        
        // Handle tab switching
        document.querySelectorAll('[data-bs-toggle="tab"]').forEach(function(tab) {
            tab.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Remove active from all tabs and panes
                document.querySelectorAll('.nav-link').forEach(function(navLink) {
                    navLink.classList.remove('active');
                    navLink.setAttribute('aria-selected', 'false');
                });
                document.querySelectorAll('.tab-pane').forEach(function(pane) {
                    pane.classList.remove('show', 'active');
                });
                
                // Add active to clicked tab and corresponding pane
                this.classList.add('active');
                this.setAttribute('aria-selected', 'true');
                
                const targetId = this.getAttribute('data-bs-target');
                const targetPane = document.querySelector(targetId);
                if (targetPane) {
                    targetPane.classList.add('show', 'active');
                    
                    // Focus on first input in the active pane
                    setTimeout(function() {
                        const firstInput = targetPane.querySelector('input');
                        if (firstInput) {
                            firstInput.focus();
                        }
                    }, 100);
                }
            });
        });
    </script>
</body>
</html>