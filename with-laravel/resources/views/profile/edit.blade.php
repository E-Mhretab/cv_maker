<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>My Profile - CV Builder Pro</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <style>
        .profile-photo-preview {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #667eea;
        }
        .profile-photo-placeholder {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 3rem;
        }
        .card-custom {
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .user-profile-photo {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid white;
            margin-right: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.15);
        }
        .user-profile-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255,255,255,0.2);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
        }
    </style>
</head>
<body class="bg-light">
    <!-- Fixed Navigation Header -->
    <nav class="navbar navbar-dark bg-primary fixed-top" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important; box-shadow: 0 2px 20px rgba(0,0,0,0.15);">
        <div class="container">
            <a href="{{ route('welcome') }}" class="navbar-brand fw-bold">
                <i class="fas fa-arrow-left me-2"></i>Back to Home
            </a>
            <span class="navbar-text fw-semibold">
                <i class="fas fa-user-circle me-2"></i>My Profile
            </span>
            
            <!-- User Menu -->
            <div class="navbar-nav ms-auto">
                @auth
                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                            @if(auth()->user()->profile_photo)
                                <img src="{{ auth()->user()->profile_photo_url }}" alt="Profile" class="user-profile-photo">
                            @else
                                <div class="user-profile-icon">
                                    <i class="fas fa-user"></i>
                                </div>
                            @endif
                            {{ auth()->user()->name }}
                            @if(auth()->user()->role === 'admin')
                                <span class="badge bg-light text-dark ms-1">Admin</span>
                            @endif
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="fas fa-user me-2"></i>My Profile</a></li>
                            <li><a class="dropdown-item" href="{{ route('cvs.index') }}"><i class="fas fa-list me-2"></i>My CVs</a></li>
                            <li><a class="dropdown-item" href="{{ route('cvs.create') }}"><i class="fas fa-plus me-2"></i>Create CV</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item"><i class="fas fa-sign-out-alt me-2"></i>Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @endauth
            </div>
        </div>
    </nav>

    <div class="container my-5" style="padding-top: 120px;">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="h2 mb-3">
                    <i class="fas fa-user-circle me-2"></i>Profile Settings
                </h1>
                <p class="text-muted">Manage your account information and profile photo</p>
            </div>
        </div>

        <!-- Success Messages -->
        @if (session('status') === 'profile-updated')
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>Profile updated successfully!
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('status') === 'google-drive-connected')
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fab fa-google-drive me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('status') === 'google-drive-disconnected')
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <i class="fas fa-info-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Profile Photo Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card card-custom">
                    <div class="card-body p-4">
                        <h3 class="h5 mb-4"><i class="fas fa-camera me-2"></i>Profile Photo</h3>
                        <div class="d-flex align-items-center">
                            @if($user->profile_photo)
                                <img src="{{ $user->profile_photo_url }}" alt="Profile photo" class="profile-photo-preview me-4" id="profilePhotoPreview">
                            @else
                                <div class="profile-photo-placeholder me-4" id="profilePhotoPlaceholder">
                                    <i class="fas fa-user"></i>
                                </div>
                            @endif
                            <div class="flex-grow-1">
                                @if($user->profile_photo)
                                    <p class="mb-2"><strong>Current Profile Photo</strong></p>
                                    <p class="text-muted small mb-3">Upload a new photo to replace this one</p>
                                    
                                    @if($user->role === 'admin' && $user->hasGoogleDrivePhoto())
                                        <div class="alert alert-success py-2 px-3 mb-3">
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="fab fa-google-drive me-2"></i>
                                                <strong class="small">Synced to Google Drive</strong>
                                            </div>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ $user->google_drive_web_link }}" target="_blank" class="btn btn-outline-success btn-sm">
                                                    <i class="fas fa-eye me-1"></i>View
                                                </a>
                                                <a href="{{ $user->google_drive_download_link }}" target="_blank" class="btn btn-outline-info btn-sm">
                                                    <i class="fas fa-download me-1"></i>Download
                                                </a>
                                            </div>
                                        </div>
                                    @endif
                                @else
                                    <p class="mb-2"><strong>No Profile Photo</strong></p>
                                    <p class="text-muted small mb-3">Upload a photo to personalize your profile</p>
                                @endif
                                
                                <!-- Photo Upload Form -->
                                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" id="photoUploadForm">
                                    @csrf
                                    @method('PATCH')
                                    
                                    <!-- Hidden inputs to preserve other fields -->
                                    <input type="hidden" name="name" value="{{ $user->name }}">
                                    <input type="hidden" name="email" value="{{ $user->email }}">
                                    
                                    <div class="d-flex gap-2 align-items-center flex-wrap">
                                        <!-- Upload/Change Button -->
                                        <label for="profile_photo_upload" class="btn btn-primary btn-sm mb-0" style="cursor: pointer;">
                                            <i class="fas fa-upload me-1"></i>{{ $user->profile_photo ? 'Change Photo' : 'Upload Photo' }}
                                        </label>
                                        <input type="file" class="d-none" id="profile_photo_upload" name="profile_photo" accept="image/*" onchange="handlePhotoChange(this)">
                                        
                                        <!-- Remove Button (only if photo exists) -->
                                        @if($user->profile_photo)
                                            <button type="button" class="btn btn-danger btn-sm" onclick="removeProfilePhoto()">
                                                <i class="fas fa-trash me-1"></i>Remove Photo
                                            </button>
                                        @endif
                                        
                                        <!-- Upload button (hidden, shown when file selected) -->
                                        <button type="submit" class="btn btn-success btn-sm d-none" id="uploadPhotoBtn">
                                            <i class="fas fa-check me-1"></i>Save Photo
                                        </button>
                                        
                                        <!-- Cancel button (hidden, shown when file selected) -->
                                        <button type="button" class="btn btn-secondary btn-sm d-none" id="cancelPhotoBtn" onclick="cancelPhotoUpload()">
                                            <i class="fas fa-times me-1"></i>Cancel
                                        </button>
                                    </div>
                                    
                                    <small class="d-block text-muted mt-2">
                                        Accepted formats: JPEG, PNG, JPG, GIF. Max size: 2MB
                                    </small>
                                    
                                    @error('profile_photo')
                                        <div class="text-danger small mt-2">{{ $message }}</div>
                                    @enderror
                                </form>
                                
                                <!-- Remove Photo Form (hidden) -->
                                <form method="POST" action="{{ route('profile.update') }}" id="removePhotoForm" class="d-none">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="name" value="{{ $user->name }}">
                                    <input type="hidden" name="email" value="{{ $user->email }}">
                                    <input type="hidden" name="remove_photo" value="1">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Google Drive Connection Section (ADMIN ONLY) -->
        @if($user->role === 'admin')
        <div class="row mb-4">
            <div class="col-12">
                @php
                    $adminToken = \App\Models\AdminGoogleToken::getToken();
                    $isConnected = $adminToken && $adminToken->refresh_token;
                @endphp
                <div class="card card-custom {{ $isConnected ? 'border-success' : 'border-warning' }}">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <h3 class="h5 mb-2">
                                    <i class="fab fa-google-drive me-2"></i>Google Drive Cloud Backup (System-wide)
                                    <span class="badge bg-warning text-dark">Admin Only</span>
                                </h3>
                                @if($isConnected)
                                    <div class="alert alert-success py-2 px-3 mb-3">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-check-circle me-2 fs-5"></i>
                                            <div>
                                                <strong>Connected & Active</strong>
                                                <p class="mb-0 small">All users' profile photos are automatically backed up to your Google Drive</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <p class="small mb-1"><strong>Storage Location:</strong></p>
                                        <p class="small text-muted mb-1">
                                            <i class="fas fa-user me-1"></i>Account: <strong>nathanjethoe007@gmail.com</strong>
                                        </p>
                                        <p class="small text-muted mb-0">
                                            <i class="fas fa-folder me-1"></i>Folder: <strong>laravel_uploads</strong>
                                        </p>
                                    </div>
                                @else
                                    <div class="alert alert-warning py-2 px-3 mb-3">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        <strong>Not Connected</strong> - Profile photos are stored locally only
                                    </div>
                                @endif
                            </div>
                        </div>

                        @if($isConnected)
                            <div class="border-top pt-3 mt-3">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h6 class="small mb-2"><strong>System Status:</strong></h6>
                                        <ul class="small text-muted mb-0">
                                            <li>Status: <span class="text-success fw-bold">Active & Syncing</span></li>
                                            <li>Token expires: {{ $adminToken->expires_at ? $adminToken->expires_at->diffForHumans() : 'Never' }}</li>
                                            <li>Auto-refresh: <span class="text-success">Enabled</span></li>
                                        </ul>
                                    </div>
                                    <div class="col-md-4 text-end">
                                        <form method="POST" action="{{ route('google.disconnect') }}" onsubmit="return confirm('⚠️ WARNING: This will disconnect Google Drive backup for ALL users.\n\nExisting files will remain in your Drive, but new uploads will not be synced.\n\nContinue?')">
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="fas fa-unlink me-1"></i>Disconnect System
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                
                                <!-- Admin Sync Actions -->
                                <div class="border-top pt-3 mt-3">
                                    <h6 class="small mb-3"><strong>Admin Actions:</strong></h6>
                                    <div class="d-flex gap-2">
                                        @if($user->profile_photo && !$user->hasGoogleDrivePhoto())
                                            <form method="POST" action="{{ route('google.sync-photo') }}">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-primary btn-sm">
                                                    <i class="fas fa-sync me-1"></i>Sync My Photo to Drive
                                                </button>
                                            </form>
                                        @endif
                                        
                                        <form method="POST" action="{{ route('google.sync-all-photos') }}">
                                            @csrf
                                            <button type="submit" class="btn btn-primary btn-sm">
                                                <i class="fas fa-sync-alt me-1"></i>Sync All Users' Photos
                                            </button>
                                        </form>
                                        
                                        <a href="https://drive.google.com/drive/folders/{{ env('GOOGLE_DRIVE_FOLDER_ID', '1Js5d8gjLylWTA6AsxstCjbEaleq19TkX') }}" target="_blank" class="btn btn-outline-success btn-sm">
                                            <i class="fab fa-google-drive me-1"></i>Open Drive Folder
                                        </a>
                                    </div>
                                    <p class="small text-muted mt-2 mb-0">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Sync all will upload/update ALL users' current profile photos to Google Drive
                                    </p>
                                </div>
                            </div>
                        @else
                            <div class="border-top pt-3 mt-3">
                                <h6 class="small mb-3"><strong>Setup Google Drive Backup:</strong></h6>
                                <div class="d-flex gap-3 align-items-start">
                                    <div class="flex-grow-1">
                                        <ol class="small text-muted mb-0">
                                            <li>Add <code>nathanjethoe007@gmail.com</code> as test user in Google Cloud Console</li>
                                            <li>Click "Connect Google Drive" button →</li>
                                            <li>Authorize with <strong>nathanjethoe007@gmail.com</strong></li>
                                            <li>All users' photos will be backed up automatically!</li>
                                        </ol>
                                    </div>
                                    <div>
                                        <a href="{{ route('google.connect') }}" class="btn btn-primary">
                                            <i class="fab fa-google-drive me-2"></i>Connect Google Drive
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Profile Information Form -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card card-custom">
                    <div class="card-body p-4">
                        <h3 class="h5 mb-4"><i class="fas fa-info-circle me-2"></i>Profile Information</h3>
                        <p class="text-muted small mb-4">Update your account's profile information, email address, and profile photo.</p>

                        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')

                            <!-- Name Field -->
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Email Field -->
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                
                                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                                    <div class="alert alert-warning mt-2">
                                        <small>Your email address is unverified.</small>
                                    </div>
                                @endif
                            </div>


                            <!-- Submit Button -->
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Update Password Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card card-custom">
                    <div class="card-body p-4">
                        <h3 class="h5 mb-4"><i class="fas fa-lock me-2"></i>Update Password</h3>
                    @include('profile.partials.update-password-form')
                    </div>
                </div>
                </div>
            </div>

        <!-- Delete Account Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card card-custom border-danger">
                    <div class="card-body p-4">
                        <h3 class="h5 mb-4 text-danger"><i class="fas fa-exclamation-triangle me-2"></i>Delete Account</h3>
                    @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Profile Photo Management Script -->
    <script>
        function handlePhotoChange(input) {
            const file = input.files[0];
            if (file) {
                // Validate file size (2MB)
                if (file.size > 2 * 1024 * 1024) {
                    alert('File size must be less than 2MB');
                    input.value = '';
                    return;
                }
                
                // Validate file type
                const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
                if (!validTypes.includes(file.type)) {
                    alert('Please select a valid image file (JPEG, PNG, JPG, or GIF)');
                    input.value = '';
                    return;
                }
                
                // Show preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('profilePhotoPreview');
                    const placeholder = document.getElementById('profilePhotoPlaceholder');
                    
                    if (preview) {
                        preview.src = e.target.result;
                    } else if (placeholder) {
                        // Replace placeholder with preview
                        placeholder.outerHTML = `<img src="${e.target.result}" alt="Profile photo preview" class="profile-photo-preview me-4" id="profilePhotoPreview">`;
                    }
                }
                reader.readAsDataURL(file);
                
                // Show save and cancel buttons
                document.getElementById('uploadPhotoBtn').classList.remove('d-none');
                document.getElementById('cancelPhotoBtn').classList.remove('d-none');
            }
        }
        
        function cancelPhotoUpload() {
            // Reset file input
            document.getElementById('profile_photo_upload').value = '';
            
            // Hide save and cancel buttons
            document.getElementById('uploadPhotoBtn').classList.add('d-none');
            document.getElementById('cancelPhotoBtn').classList.add('d-none');
            
            // Restore original photo or placeholder
            location.reload();
        }
        
        function removeProfilePhoto() {
            if (confirm('Are you sure you want to remove your profile photo?')) {
                document.getElementById('removePhotoForm').submit();
            }
        }
    </script>
</body>
</html>
