@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-calendar-alt text-danger mr-2"></i>Events</h1>
            @if (auth()->user()->role === 'admin')
                <button class="btn btn-sm btn-dark-red" data-toggle="modal" data-target="#createEventModal">
                    <i class="fas fa-plus-circle mr-1"></i> Create Event
                </button>
            @endif
        </div>

        <div id="alertContainer"></div>

        <div class="row" id="eventsContainer">
            @forelse($events as $event)
                <div class="col-md-6 col-lg-4 mb-4" id="event-card-{{ $event->id }}">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-header bg-theme text-white d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0 font-weight-bold text-truncate" title="{{ $event->title }}">
                                {{ $event->title }}
                            </h5>
                            @if (auth()->user()->role == 'admin')
                                <button class="btn btn-sm btn-light font-weight-bold view-participants"
                                    data-id="{{ $event->id }}">
                                    <i class="fas fa-users text-primary mr-1"></i>
                                    <span id="participant-count-{{ $event->id }}">{{ $event->participants_count }}</span>
                                </button>
                            @endif
                        </div>

                        <div class="card-body d-flex flex-column">
                            <!-- Location -->
                            <p class="card-text mb-2 text-secondary">
                                <i class="fas fa-map-marker-alt text-danger mr-2"></i>
                                <span>{{ $event->location }}</span>
                            </p>

                            <!-- Event Date Range -->
                            <p class="card-text mb-3 text-muted small">
                                <i class="far fa-clock text-danger mr-2"></i>
                                {{ $event->formatted_date_range }}
                            </p>

                            <!-- Join Link Copy Input -->
                            <div class="form-group mb-3 mt-auto">
                                <label class="small text-muted font-weight-bold mb-1">Join Link</label>
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control" value="{{ $event->join_url }}" readonly
                                        id="link-{{ $event->id }}">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary copy-btn" data-id="{{ $event->id }}"
                                            title="Copy Join Link">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Footer / Action Button -->
                        <div class="card-footer bg-light border-0 text-right">
                            @php $isJoined = $event->isJoinedBy(auth()->id()); @endphp
                            <button type="button"
                                class="btn btn-sm {{ $isJoined ? 'btn-outline-danger' : 'btn-success' }} join-btn btn-block"
                                data-id="{{ $event->id }}">
                                <i class="fas {{ $isJoined ? 'fa-user-minus' : 'fa-user-plus' }} mr-1"></i>
                                <span class="btn-text">{{ $isJoined ? 'Leave Event' : 'Join Event' }}</span>
                                <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center text-muted py-5">
                    <i class="far fa-calendar-times fa-3x mb-3 d-block text-secondary"></i>
                    <h5>No events scheduled.</h5>
                </div>
            @endforelse
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

        $(document).ready(function() {
            $(document).on('click', '.join-btn', function(e) {
                e.preventDefault();

                let $btn = $(this);
                let id = $btn.data('id');
                let $text = $btn.find('.btn-text');
                let $icon = $btn.find('i');
                let $spinner = $btn.find('.spinner-border');

                // Disable button & show spinner during request
                $btn.prop('disabled', true);
                $spinner.removeClass('d-none');

                $.ajax({
                    url: `/events/${id}/join`,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.isJoined) {
                            // Switch UI to "Leave Event" state
                            $btn.removeClass('btn-success').addClass('btn-outline-danger');
                            $icon.removeClass('fa-user-plus').addClass('fa-user-minus');
                            $text.text('Leave Event');
                        } else {
                            // Switch UI to "Join Event" state
                            $btn.removeClass('btn-outline-danger').addClass('btn-success');
                            $icon.removeClass('fa-user-minus').addClass('fa-user-plus');
                            $text.text('Join Event');
                        }

                        // Update dynamic count badge if present in the row/card
                        $(`.participant-count-${id}`).text(response.total_count);
                    },
                    error: function(xhr) {
                        // Catch 422 capacity errors and display the controller message
                        if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON
                            .message) {
                            alert(xhr.responseJSON.message);
                        } else {
                            alert('An error occurred. Please try again.');
                        }
                    },
                    complete: function() {
                        // Re-enable button & hide spinner
                        $btn.prop('disabled', false);
                        $spinner.addClass('d-none');
                    }
                });
            });
        });
    </script>
@endpush
