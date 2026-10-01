@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">

        <!-- Page Title & Actions -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-school text-primary me-2"></i>Schools Management</h1>
            <div>
                <!-- Import Modal Trigger Button -->
                <button type="button" class="btn btn-sm btn-success shadow-sm me-1" data-toggle="modal"
                    data-target="#importModal">
                    <i class="fas fa-file-import fa-sm text-white-50 me-1"></i> Import Excel / CSV
                </button>
                <a href="{{ route('admin.schools.create') }}" class="btn btn-sm btn-primary shadow-sm">
                    <i class="fas fa-plus fa-sm text-white-50 me-1"></i> Add New School
                </a>
            </div>
        </div>

        <!-- Status Alerts -->
        @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-1"></i> {{ session('status') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-1"></i> {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <!-- Import Validation Errors List -->
        @if (session('import_errors'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <h6 class="fw-bold"><i class="fas fa-exclamation-circle me-1"></i> Import Failures:</h6>
                <ul class="mb-0 ps-3 small">
                    @foreach (session('import_errors') as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <!-- Search & Data Table Card -->
        <div class="card shadow border-0 mb-4">
            <div class="card-header py-3 bg-white d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">List of Schools</h6>
                <form method="GET" action="{{ route('admin.schools.index') }}" class="form-inline">
                    <div class="input-group input-group-sm">
                        <input type="text" name="search" class="form-control bg-light border-0 small"
                            placeholder="Search school or district..." value="{{ $search ?? '' }}">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search fa-sm"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>School Name</th>
                                <th>District</th>
                                <th>Municipality</th>

                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($schools as $school)
                                <tr>
                                    <td>{{ $loop->iteration + ($schools->currentPage() - 1) * $schools->perPage() }}</td>
                                    <td class="fw-bold text-dark">{{ $school->school_name }}</td>
                                    <td>{{ $school->district_name }}</td>
                                    <td>{{ $school->municipality }}</td>

                                    <td class="text-center">
                                        <a href="{{ route('admin.schools.show', $school->id) }}"
                                            class="btn btn-sm btn-info" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.schools.edit', $school->id) }}"
                                            class="btn btn-sm btn-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.schools.destroy', $school->id) }}" method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this school?');"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">No schools found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if ($schools->hasPages())
                <div class="card-footer bg-white d-flex justify-content-center py-3">
                    {{ $schools->links() }}
                </div>
            @endif
        </div>

    </div>

    <!-- Import Modal -->
    <div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title font-weight-bold" id="importModalLabel">
                        <i class="fas fa-file-excel me-1"></i> Import Schools Data
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.schools.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">

                        <!-- Download Template Section -->
                        <div class="d-flex align-items-center justify-content-between p-3 mb-3 bg-light rounded border">
                            <div>
                                <h6 class="font-weight-bold text-dark mb-1">Need the file format?</h6>
                                <small class="text-muted d-block">Download the pre-formatted CSV sample template.</small>
                            </div>
                            <a href="{{ route('admin.schools.downloadTemplate') }}"
                                class="btn btn-sm btn-outline-success font-weight-bold">
                                <i class="fas fa-download me-1"></i> Download Template
                            </a>
                        </div>

                        <!-- File Input -->
                        <div class="form-group mb-3">
                            <label for="file" class="font-weight-bold">Select Excel / CSV File <span
                                    class="text-danger">*</span></label>
                            <input type="file" name="file" id="file"
                                class="form-control-file border rounded p-2 w-100" accept=".xlsx, .xls, .csv" required>
                            <small class="text-muted d-block mt-1">Accepted formats: .xlsx, .xls, .csv (Max: 5MB)</small>
                        </div>

                        <!-- Header Preview -->
                        <div class="card bg-light border-0 p-3">
                            <h6 class="font-weight-bold small mb-2"><i class="fas fa-info-circle text-info me-1"></i>
                                Required Columns:</h6>
                            <code class="small text-dark">school_name, district_name, municipality, designation,
                                school_office, sex, email, contact_number</code>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success"><i class="fas fa-upload me-1"></i> Upload &
                            Import</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
