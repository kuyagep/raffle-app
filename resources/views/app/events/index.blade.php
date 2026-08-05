@extends('layouts.main')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-calendar-alt text-danger mr-2"></i>Events Management</h1>
            @if (auth()->user()->role === 'admin')
                <button class="btn btn-sm btn-dark-red" data-toggle="modal" data-target="#createEventModal">
                    <i class="fas fa-plus-circle mr-1"></i> Create Event
                </button>
            @endif
        </div>

        <div id="alertContainer"></div>

        <div class="card shadow mb-4">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped mb-0" id="eventsTable">
                        <thead class="bg-theme text-white">
                            <tr>
                                <th>Title</th>
                                <th>Location</th>
                                <th>Event Date</th>
                                <th>Join Link</th>
                                <th>Participants</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($events as $event)
                                <tr id="event-row-{{ $event->id }}">
                                    <td class="align-middle font-weight-bold">{{ $event->title }}</td>
                                    <td class="align-middle">{{ $event->location }}</td>
                                    <td class="align-middle text-muted small">
                                        <i class="far fa-clock text-danger mr-1"></i>
                                        {{ $event->formatted_date_range }}
                                    </td>
                                    <td class="align-middle">
                                        <div class="input-group input-group-sm" style="max-width: 200px;">
                                            <input type="text" class="form-control" value="{{ $event->join_url }}"
                                                readonly id="link-{{ $event->id }}">
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-secondary copy-btn"
                                                    data-id="{{ $event->id }}" title="Copy Join Link">
                                                    <i class="fas fa-copy"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        <button class="btn btn-sm btn-link p-0 view-participants"
                                            data-id="{{ $event->id }}">
                                            <span
                                                id="participant-count-{{ $event->id }}">{{ $event->participants_count }}</span>
                                            Participant(s)
                                        </button>
                                    </td>
                                    <td class="align-middle text-right">
                                        @php $isJoined = $event->isJoinedBy(auth()->id()); @endphp
                                        <button
                                            class="btn btn-sm {{ $isJoined ? 'btn-outline-danger' : 'btn-success' }} join-btn"
                                            data-id="{{ $event->id }}">
                                            <i class="fas {{ $isJoined ? 'fa-user-minus' : 'fa-user-plus' }} mr-1"></i>
                                            <span class="btn-text">{{ $isJoined ? 'Leave Event' : 'Join Event' }}</span>
                                            <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">No events scheduled.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Event Modal -->
    <div class="modal fade" id="createEventModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-theme text-white">
                    <h5 class="modal-title"><i class="fas fa-calendar-plus mr-2"></i>Create Event</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <form id="createEventForm">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="small font-weight-bold">Event Title *</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label class="small font-weight-bold">Start Date & Time *</label>
                                <input type="datetime-local" name="start_date" id="start_date" class="form-control"
                                    required>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="small font-weight-bold">End Date & Time *</label>
                                <input type="datetime-local" name="end_date" id="end_date" class="form-control" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="small font-weight-bold">Location *</label>
                            <input type="text" name="location" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label class="small font-weight-bold">Capacity (Optional)</label>
                            <input type="number" name="capacity" class="form-control" placeholder="Unlimited if blank">
                        </div>

                        <div class="form-group mb-0">
                            <label class="small font-weight-bold">Description</label>
                            <textarea name="description" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-sm btn-dark-red">Save Event</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const csrfToken = "{{ csrf_token() }}";

            function showAlert(message, type = 'success') {
                document.getElementById("alertContainer").innerHTML = `
            <div class="alert alert-${type} alert-dismissible fade show shadow-sm" role="alert">
                <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'} mr-2"></i>${message}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        `;
            }

            // Copy Link Functionality
            document.addEventListener("click", function(e) {
                const copyBtn = e.target.closest(".copy-btn");
                if (!copyBtn) return;

                const eventId = copyBtn.getAttribute("data-id");
                const input = document.getElementById(`link-${eventId}`);

                if (input) {
                    input.select();
                    input.setSelectionRange(0, 99999); // Mobile compatibility
                    navigator.clipboard.writeText(input.value);
                    showAlert("Join link copied to clipboard!", "success");
                }
            });

            // Create Event Form Handler
            const createEventForm = document.getElementById("createEventForm");
            if (createEventForm) {
                createEventForm.addEventListener("submit", function(e) {
                    e.preventDefault();
                    fetch("{{ route('admin.events.store') }}", {
                            method: "POST",
                            headers: {
                                "X-CSRF-TOKEN": csrfToken,
                                "Accept": "application/json"
                            },
                            body: new FormData(this)
                        })
                        .then(res => res.json())
                        .then(data => {
                            $('#createEventModal').modal('hide');
                            createEventForm.reset();
                            showAlert(data.message + " Join Link: " + data.join_url);
                            location.reload();
                        });
                });
            }
        });
    </script>
@endpush
