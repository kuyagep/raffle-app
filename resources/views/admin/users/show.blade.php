@extends('layouts.app')

@section('content')
    <div class="container-fluid">

        <!-- Page Heading & Actions -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">Edit User Profile</h1>
            <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-secondary">
                <i class="fas fa-arrow-left mr-1"></i> Back to Users
            </a>
        </div>

        <div id="alertContainer"></div>

        <!-- Session Alerts -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="row">
            <div class="col-lg-8 col-md-10">

                <!-- Edit User Card -->
                <div class="card shadow mb-4">
                    <div class="card-header bg-theme text-white py-3">
                        <h6 class="m-0 font-weight-bold"><i class="fas fa-id-card mr-2"></i>User Details</h6>
                    </div>
                    <div class="card-body">
                        <form id="updateUserForm">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label class="small font-weight-bold text-dark">Full Name <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" class="form-control"
                                    value="{{ $user->name }}" required>
                                <div class="invalid-feedback" id="err-name"></div>
                            </div>

                            <div class="form-group">
                                <label class="small font-weight-bold text-dark">Email Address <span
                                        class="text-danger">*</span></label>
                                <input type="email" name="email" id="email" class="form-control"
                                    value="{{ $user->email }}" required>
                                <div class="invalid-feedback" id="err-email"></div>
                            </div>

                            <div class="form-group">
                                <label class="small font-weight-bold text-dark">Role <span
                                        class="text-danger">*</span></label>
                                <select name="role" id="role" class="form-control" required>
                                    <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>User</option>
                                    <option value="staff" {{ $user->role === 'staff' ? 'selected' : '' }}>Staff</option>
                                    <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                </select>
                                <div class="invalid-feedback" id="err-role"></div>
                            </div>

                            <hr class="my-4">

                            <div class="form-group mb-4">
                                <label class="small font-weight-bold text-dark">Change Password</label>
                                <input type="password" name="password" id="password" class="form-control"
                                    placeholder="Leave blank to keep current password">
                                <small class="form-text text-muted">Minimum of 8 characters if changing.</small>
                                <div class="invalid-feedback" id="err-password"></div>
                            </div>

                            <div class="d-flex justify-content-end">
                                <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-secondary mr-2">Cancel</a>
                                <button type="submit" id="updateBtn" class="btn btn-sm btn-deped px-4">
                                    <span id="btnText"><i class="fas fa-save mr-1"></i> Save Changes</span>
                                    <span id="btnSpinner" class="spinner-border spinner-border-sm d-none"
                                        role="status"></span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const updateUserForm = document.getElementById("updateUserForm");

            if (updateUserForm) {
                updateUserForm.addEventListener("submit", function(e) {
                    e.preventDefault();

                    // Reset field validation UI
                    document.querySelectorAll(".form-control").forEach(el => el.classList.remove(
                        "is-invalid"));
                    document.querySelectorAll(".invalid-feedback").forEach(el => el.textContent = "");
                    document.getElementById("alertContainer").innerHTML = "";

                    const updateBtn = document.getElementById("updateBtn");
                    const btnText = document.getElementById("btnText");
                    const btnSpinner = document.getElementById("btnSpinner");

                    updateBtn.disabled = true;
                    btnText.classList.add("d-none");
                    btnSpinner.classList.remove("d-none");

                    fetch("{{ route('admin.users.update', $user->id) }}", {
                            method: "POST",
                            headers: {
                                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                "Accept": "application/json"
                            },
                            body: new FormData(this)
                        })
                        .then(async response => {
                            const data = await response.json();
                            if (!response.ok) throw {
                                status: response.status,
                                data: data
                            };
                            return data;
                        })
                        .then(data => {
                            // Display success banner dynamic message
                            document.getElementById("alertContainer").innerHTML = `
                            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                                <i class="fas fa-check-circle mr-2"></i>${data.message || 'User updated successfully!'}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        `;

                            // Reset password field
                            document.getElementById("password").value = "";

                            // Restore button state (No page reload)
                            updateBtn.disabled = false;
                            btnText.classList.remove("d-none");
                            btnSpinner.classList.add("d-none");

                            // Auto-hide alert after 5 seconds
                            setTimeout(() => {
                                const alertEl = document.querySelector(
                                    "#alertContainer .alert");
                                if (alertEl) {
                                    $(alertEl).alert('close');
                                }
                            }, 5000);
                        })
                        .catch(error => {
                            updateBtn.disabled = false;
                            btnText.classList.remove("d-none");
                            btnSpinner.classList.add("d-none");

                            if (error.status === 422 && error.data.errors) {
                                Object.keys(error.data.errors).forEach(field => {
                                    const input = document.getElementById(field);
                                    const errDiv = document.getElementById(`err-${field}`);
                                    if (input) input.classList.add("is-invalid");
                                    if (errDiv) errDiv.textContent = error.data.errors[field][
                                        0
                                    ];
                                });
                            } else {
                                document.getElementById("alertContainer").innerHTML = `
                                <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>An unexpected error occurred while saving changes.
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            `;
                            }
                        });
                });
            }
        });
    </script>
@endpush
