@extends('layouts.app')
@section('title', 'Users')
@section('content')
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                User Management
            </h1>
            <button class="btn btn-sm btn-deped" data-toggle="modal" data-target="#createUserModal">
                <i class="fas fa-plus-circle mr-1"></i> Add New User
            </button>
        </div>

        <!-- Session Alerts -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div id="alertContainer"></div>

        <!-- Filter & Search Card -->
        <div class="card shadow mb-4">
            <div class="card-body py-3">
                <form method="GET" action="{{ route('admin.users.index') }}" class="form-inline row">
                    <div class="col-md-5 my-1">
                        <div class="input-group input-group-sm w-100">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-white">
                                    <i class="fas fa-search text-muted"></i>
                                </span>
                            </div>
                            <input type="text" name="search" class="form-control"
                                placeholder="Search by name or email..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-4 my-1">
                        <select name="role" class="form-control form-control-sm w-100">
                            <option value="">-- All Roles --</option>
                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="staff" {{ request('role') == 'staff' ? 'selected' : '' }}>Staff</option>
                            <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
                        </select>
                    </div>
                    <div class="col-md-3 my-1">
                        <button type="submit" class="btn btn-sm btn-deped btn-block">
                            <i class="fas fa-filter mr-1"></i> Filter Results
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Data Table Card -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered mb-0" id="usersTable">
                        <thead class="bg-theme text-white">
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Created Date</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $user)
                                <tr id="user-row-{{ $user->id }}">
                                    <td class="align-middle font-weight-bold">{{ $user->name }}</td>
                                    <td class="align-middle">{{ $user->email }}</td>
                                    <td class="align-middle">
                                        @if ($user->role === 'admin')
                                            <span class="badge badge-danger px-2 py-1">Admin</span>
                                        @elseif($user->role === 'staff')
                                            <span class="badge badge-info px-2 py-1">Staff</span>
                                        @else
                                            <span class="badge badge-secondary px-2 py-1">User</span>
                                        @endif
                                    </td>
                                    <td class="align-middle text-muted small">
                                        {{ $user->created_at->format('M d, Y h:i A') }}
                                    </td>
                                    <td class="align-middle text-right">
                                        <a href="{{ route('admin.users.show', $user->id) }}"
                                            class="btn btn-sm btn-info mr-1" title="View/Edit Details">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button class="btn btn-sm btn-danger delete-btn" data-id="{{ $user->id }}"
                                            data-name="{{ $user->name }}"
                                            data-url="{{ route('admin.users.destroy', $user->id) }}" title="Delete User">
                                            <i class="fas fa-trash"></i>
                                            <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr id="emptyRow">
                                    <td colspan="5" class="text-center text-muted py-4">
                                        <i class="fas fa-user-slash fa-2x mb-2 d-block"></i> No users found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if ($users->hasPages())
                <div class="card-footer bg-white d-flex justify-content-end py-2">
                    {{ $users->links() }}
                </div>
            @endif
        </div>

    </div>

    <!-- Create User Modal -->
    <div class="modal fade" id="createUserModal" tabindex="-1" role="dialog" aria-labelledby="createUserModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-theme text-white">
                    <h5 class="modal-title" id="createUserModalLabel">
                        <i class="fas fa-user-plus mr-2"></i>Create New User
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="createUserForm">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="small font-weight-bold">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" class="form-control" required>
                            <div class="invalid-feedback" id="err-name"></div>
                        </div>

                        <div class="form-group">
                            <label class="small font-weight-bold">Email Address <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="email" class="form-control" required>
                            <div class="invalid-feedback" id="err-email"></div>
                        </div>

                        <div class="form-group">
                            <label class="small font-weight-bold">System Role <span class="text-danger">*</span></label>
                            <select name="role" id="role" class="form-control" required>
                                <option value="user">User</option>
                                <option value="staff">Staff</option>
                                <option value="admin">Admin</option>
                            </select>
                            <div class="invalid-feedback" id="err-role"></div>
                        </div>

                        <div class="form-group mb-0">
                            <label class="small font-weight-bold">Password</label>
                            <input type="password" name="password" id="password" class="form-control"
                                placeholder="Leave blank to auto-generate temporary password">
                            <div class="invalid-feedback" id="err-password"></div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" id="saveUserBtn" class="btn btn-sm btn-deped">
                            <span id="saveBtnText">Create User</span>
                            <span id="saveBtnSpinner" class="spinner-border spinner-border-sm d-none"
                                role="status"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            // Helper to display dynamic dynamic alerts without page refresh
            function showAlert(message, type = 'success') {
                const alertContainer = document.getElementById("alertContainer");
                const alertHtml = `
                    <div class="alert alert-${type} alert-dismissible fade show shadow-sm" role="alert">
                        <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-2"></i>${message}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                `;
                alertContainer.innerHTML = alertHtml;
            }

            // Handle Create User Form Submission via AJAX
            const createUserForm = document.getElementById("createUserForm");
            if (createUserForm) {
                createUserForm.addEventListener("submit", function(e) {
                    e.preventDefault();

                    document.querySelectorAll(".form-control").forEach(el => el.classList.remove(
                        "is-invalid"));
                    document.querySelectorAll(".invalid-feedback").forEach(el => el.textContent = "");

                    const saveBtn = document.getElementById("saveUserBtn");
                    const btnText = document.getElementById("saveBtnText");
                    const btnSpinner = document.getElementById("saveBtnSpinner");

                    saveBtn.disabled = true;
                    btnText.classList.add("d-none");
                    btnSpinner.classList.remove("d-none");

                    fetch("{{ route('admin.users.store') }}", {
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
                            $('#createUserModal').modal('hide');
                            createUserForm.reset();
                            showAlert(data.message || "User created successfully!");
                            location
                                .reload(); // Reload after creation if you prefer, or handle dynamically
                        })
                        .catch(error => {
                            saveBtn.disabled = false;
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
                            }
                        });
                });
            }

            // Handle Delete Buttons via AJAX without Page Reload
            document.addEventListener("click", function(e) {
                const deleteBtn = e.target.closest(".delete-btn");
                if (!deleteBtn) return;

                const userId = deleteBtn.getAttribute("data-id");
                const userName = deleteBtn.getAttribute("data-name");
                const deleteUrl = deleteBtn.getAttribute("data-url");
                const icon = deleteBtn.querySelector(".fa-trash");
                const spinner = deleteBtn.querySelector(".spinner-border");

                if (confirm(`Are you sure you want to delete user "${userName}"?`)) {
                    // Show button loading spinner
                    deleteBtn.disabled = true;
                    if (icon) icon.classList.add("d-none");
                    if (spinner) spinner.classList.remove("d-none");

                    fetch(deleteUrl, {
                            method: "DELETE",
                            headers: {
                                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                "Accept": "application/json",
                                "Content-Type": "application/json"
                            }
                        })
                        .then(async response => {
                            const data = await response.json();
                            if (!response.ok) throw new Error(data.message ||
                                'Failed to delete user.');
                            return data;
                        })
                        .then(data => {
                            const row = document.getElementById(`user-row-${userId}`);
                            if (row) {
                                // Fade out and remove row smoothly
                                row.style.transition = "opacity 0.4s ease, transform 0.4s ease";
                                row.style.opacity = "0";
                                row.style.transform = "translateX(20px)";

                                setTimeout(() => {
                                    row.remove();

                                    // Check if table is now empty
                                    const tbody = document.querySelector("#usersTable tbody");
                                    if (tbody && tbody.querySelectorAll("tr").length === 0) {
                                        tbody.innerHTML = `
                                            <tr id="emptyRow">
                                                <td colspan="5" class="text-center text-muted py-4">
                                                    <i class="fas fa-user-slash fa-2x mb-2 d-block"></i> No users found.
                                                </td>
                                            </tr>
                                        `;
                                    }
                                }, 400);
                            }

                            showAlert(data.message ||
                                `User "${userName}" has been deleted successfully.`);
                        })
                        .catch(err => {
                            // Restore button state on failure
                            deleteBtn.disabled = false;
                            if (icon) icon.classList.remove("d-none");
                            if (spinner) spinner.classList.add("d-none");

                            showAlert(err.message || "An error occurred while deleting.", "danger");
                        });
                }
            });

        });
    </script>
@endpush
