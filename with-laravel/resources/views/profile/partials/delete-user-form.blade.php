<section>
    <p class="text-muted small">
        Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.
    </p>

    <button type="button" class="btn btn-danger mt-3" data-bs-toggle="modal" data-bs-target="#confirmUserDeletionModal">
        <i class="fas fa-trash-alt me-2"></i>Delete Account
    </button>
</section>

<!-- Delete Account Modal -->
<div class="modal fade" id="confirmUserDeletionModal" tabindex="-1" aria-labelledby="confirmUserDeletionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('profile.destroy') }}">
                @csrf
                @method('DELETE')
                
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="confirmUserDeletionModalLabel">
                        <i class="fas fa-exclamation-triangle me-2"></i>Delete Account
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body">
                    <div class="alert alert-danger">
                        <strong>Warning!</strong> This action cannot be undone.
                    </div>
                    
                    <p class="mb-3">
                        Are you sure you want to delete your account? Once your account is deleted, all of its resources and data will be permanently deleted.
                    </p>
                    
                    <p class="mb-3">
                        Please enter your password to confirm you would like to permanently delete your account.
                    </p>
                    
                    <div class="mb-3">
                        <label for="delete_password" class="form-label">Password</label>
                        <input type="password" class="form-control @error('password', 'userDeletion') is-invalid @enderror" 
                               id="delete_password" name="password" required placeholder="Enter your password">
                        @error('password', 'userDeletion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash-alt me-2"></i>Delete Account
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@if($errors->userDeletion->isNotEmpty())
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var myModal = new bootstrap.Modal(document.getElementById('confirmUserDeletionModal'));
        myModal.show();
    });
</script>
@endif
