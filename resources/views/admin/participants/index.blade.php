@extends('layouts.admin')

@section('content')
    <!-- Page Heading -->
    <div class="d-flex flex-md-row justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Participants</h1>
        <div class="align-items-center">
            <!-- Bulk Delete Button -->
            <button id="bulkDeleteBtn" class="btn btn-deped d-sm-inline-block fw-bold mt-md-0 ml-2 px-3"
                title="Delete Selected">
                <i class="fas fa-trash-alt me-1"></i>
            </button>

            <a href="{{ route('admin.participants.export') }}" class="btn btn-deped d-sm-inline-block fw-bold ml-2 px-3"
                title="Export Excel">
                <i class="fas fa-file-excel me-1"></i>
            </a>

            <!-- Import Excel Button -->
            <button type="button" class="btn btn-deped d-sm-inline-block fw-bold ml-2 px-3" data-toggle="modal"
                data-target="#importModal" title="Import Excel">
                <i class="fas fa-upload me-1"></i>
            </button>

            <a href="{{ route('admin.participants.downloadTemplate') }}"
                class="btn btn-deped d-sm-inline-block fw-bold ml-2 px-3" title="Download Template">
                <i class="fas fa-file-download me-1"></i>
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif



    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2 mb-4 ">
                <!-- Search Form with Input Group -->
                <form action="{{ route('admin.participants.index') }}" method="GET" class="flex-grow-1">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control form-control-lg bg-default border-1 small"
                            placeholder="Search by name, district, municipality, school, email, or contact number"
                            aria-label="Search" aria-describedby="search-addon" value="{{ request('search') }}">
                        <div class="input-group-append">
                            <button class="btn btn-lg btn-deped fw-bold" type="submit" id="search-addon">
                                <i class="fas fa-search fa-sm"></i> Search
                            </button>
                        </div>
                    </div>
                </form>

            </div>

            <div class="table-responsive">
                <table class="table table-striped">
                    <thead class="bg-theme">
                        <tr>
                            <th width="40">
                                <input type="checkbox" id="selectAllPage"> <!-- Select visible page -->
                                <small>
                                    <input type="checkbox" id="selectAllAcross">
                                </small>
                            </th>
                            <th>Full Name</th>
                            <th>District/Division</th>
                            <th>Municipality/Division</th>
                            <th>School/Office Name</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($participants as $participant)
                            <tr id="row-{{ $participant->id }}">
                                <td>
                                    <input type="checkbox" class="selectItem" value="{{ $participant->id }}">
                                </td>
                                <td>{{ $participant->full_name }}</td>
                                <td>{{ $participant->district_division }}</td>
                                <td>{{ $participant->municipality }}</td>
                                <td>{{ $participant->school_office }}</td>
                                <td>
                                    <a href="{{ route('admin.participants.show', $participant) }}"
                                        class="btn btn-sm btn-deped">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <form action="{{ route('admin.participants.destroy', $participant) }}" method="POST"
                                        class="d-inline">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-deped"
                                            onclick="return confirm('Delete this participant?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center">No record found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Pagination links -->
                {{ $participants->links() }}
            </div>

        </div>
    </div>

    <div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="importForm" enctype="multipart/form-data" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="importModalLabel">
                        <i class="fas fa-file-excel me-1"></i> Import Participants
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="importErrors"></div>
                    <div class="form-group">
                        <label for="file" class="fw-bold">Select Excel File</label>
                        <input type="file" name="file" class="form-control" id="file" required>
                        <small class="form-text text-muted">Allowed types: xlsx, xls, csv</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark fw-bold" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-deped fw-bold">Upload</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: "{{ session('success') }}",
                timer: 2500,
                showConfirmButton: false
            });
        </script>
    @endif


    <script type="text/javascript">
        let selectedIds = []; // Tracks all selected participants across pages
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            // Select all visible checkboxes on current page
            $('#selectAllPage').click(function() {
                $('.selectItem').prop('checked', this.checked);

                // Add/remove visible IDs to selectedIds
                $('.selectItem').each(function() {
                    const id = this.value;
                    if (this.checked && !selectedIds.includes(id)) {
                        selectedIds.push(id);
                    } else if (!this.checked && selectedIds.includes(id)) {
                        selectedIds = selectedIds.filter(i => i != id);
                    }
                });
            });

            // Individual checkbox click
            $('.selectItem').click(function() {
                const id = this.value;
                if (this.checked && !selectedIds.includes(id)) {
                    selectedIds.push(id);
                } else if (!this.checked && selectedIds.includes(id)) {
                    selectedIds = selectedIds.filter(i => i != id);
                }
            });

            // Select all across pages
            $('#selectAllAcross').click(function() {
                if (this.checked) {
                    Swal.fire({
                        title: 'Select All?',
                        text: 'This will select all participants across all pages!',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#003399',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, select all'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Tell server to get all IDs
                            $.getJSON("{{ route('admin.participants.getAllIds') }}", function(
                                data) {
                                selectedIds = data.ids;
                                // check all checkboxes on current page
                                $('.selectItem').prop('checked', true);
                                $('#selectAllPage').prop('checked', true);
                                Swal.fire('Selected!', 'All participants selected.',
                                    'success');
                            });
                        } else {
                            $('#selectAllAcross').prop('checked', false);
                        }
                    });
                } else {
                    // unselect all
                    selectedIds = [];
                    $('.selectItem').prop('checked', false);
                    $('#selectAllPage').prop('checked', false);
                }
            });

            // Bulk Delete Button
            $('#bulkDeleteBtn').click(function() {
                if (selectedIds.length === 0) {
                    Swal.fire('Warning', 'Please select at least one participant.', 'warning');
                    return;
                }

                Swal.fire({
                    title: 'Are you sure?',
                    text: "Selected participants will be deleted permanently!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#003399',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete!',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('admin.participants.bulkDelete') }}",
                            type: 'POST',
                            contentType: 'application/json',
                            data: JSON.stringify({
                                _token: "{{ csrf_token() }}",
                                _method: 'DELETE',
                                ids: selectedIds
                            }),
                            success: function(response) {
                                if (response.success) {
                                    // remove deleted rows from table
                                    selectedIds.forEach(function(id) {
                                        $('#row-' + id).remove();
                                    });
                                    Swal.fire('Deleted!', response.message, 'success');
                                    selectedIds = []; // reset selection
                                } else {
                                    Swal.fire('Error', response.message, 'error');
                                }
                            },
                            error: function(xhr) {
                                console.error(xhr.responseText);
                                Swal.fire('Error', 'Something went wrong.', 'error');
                            }
                        });
                    }
                });
            });

        });
    </script>

    <script>
        $('#importForm').on('submit', function(e) {
            e.preventDefault();

            let formData = new FormData(this);

            $.ajax({
                url: "{{ route('admin.participants.import') }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                success: function(data) {
                    if (data.success) {
                        // Close modal
                        $('#importModal').modal('hide');

                        Swal.fire({
                            icon: 'success',
                            title: 'Imported!',
                            text: data.message,
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => location.reload());
                    }
                },
                error: function(xhr) {
                    // Clear old errors
                    $('#importErrors').html('');

                    if (xhr.status === 422 && xhr.responseJSON?.errors) {
                        // Validation errors
                        let errorList = xhr.responseJSON.errors.map(err => `<li>${err}</li>`).join('');
                        $('#importErrors').html(`
                        <div class="alert alert-danger">
                            <ul class="mb-0">${errorList}</ul>
                        </div>
                    `);
                    } else {
                        // Generic error
                        Swal.fire('Error!', xhr.responseJSON?.message ||
                            'Something went wrong while importing.', 'error');
                    }
                }
            });
        });

        $('#importModal').on('hidden.bs.modal', function() {
            // Reset form
            $('#importForm')[0].reset();

            // Clear error messages
            $('#importErrors').html('');
        });
    </script>



@endpush
