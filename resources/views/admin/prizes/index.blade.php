@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h3 text-gray-800">Prize Management</h1>
            <a href="{{ route('admin.prizes.create') }}" class="btn btn-sm btn-deped">
                <i class="fas fa-plus"></i> Add Prize
            </a>
        </div>
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <div class="card shadow mb-4">
            <div class="card-header bg-theme py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold ">Prize List</h6>

            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead class="bg-theme">
                        <tr>
                            <th>#</th>
                            <th>Prize Name</th>
                            <th>Quantity</th>
                            <th>Winners</th>
                            <th>Remaining</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($prizes as $index => $prize)
                            @php
                                $drawnCount = $prize->winners->count();
                                $remainingCount = $prize->quantity - $drawnCount;
                            @endphp
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $prize->name }}</td>
                                <td>{{ $prize->quantity }}</td>
                                <td>{{ $drawnCount }}</td>
                                <td>{{ $remainingCount }}</td>
                                <td>
                                    @if ($prize->winners->count() < $prize->quantity)
                                        {{-- <form action="{{ route('admin.prizes.draw', $prize->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-deped btn-sm">
                                                <i class="fas fa-random"></i> Draw
                                            </button>
                                        </form> --}}

                                        <!-- Filtered Pre-Draw Button Triggering Modal -->
                                        <button type="button" class="btn btn-sm btn-primary openPreDrawModal"
                                            data-prize-id="{{ $prize->id }}" data-prize-name="{{ $prize->name }}"
                                            data-remaining="{{ $remainingCount }}">
                                            <i class="fas fa-filter me-1"></i> Pre-Draw
                                        </button>
                                    @else
                                        <span class="badge badge-success">Completed</span>
                                    @endif
                                    @if ($prize->winners->count() === 0)
                                        <a href="{{ route('admin.prizes.edit', $prize) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <form action="{{ route('admin.prizes.destroy', $prize) }}" method="POST"
                                            class="d-inline-block"
                                            onsubmit="return confirm('Are you sure you want to delete this prize?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-primary">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </form>
                                    @else
                                        <button class="btn btn-sm btn-secondary" disabled>
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                        <button class="btn btn-sm btn-secondary" disabled>
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pre-Draw Filter Modal -->
    <div class="modal fade" id="preDrawModal" tabindex="-1" role="dialog" aria-labelledby="preDrawModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <form id="preDrawForm" method="POST" action="" class="modal-content">
                @csrf
                <div class="modal-header bg-theme text-white">
                    <h5 class="modal-title font-weight-bold" id="preDrawModalLabel">
                        <i class="fas fa-sliders-h me-2"></i> Pre-Draw Settings (<span id="modalPrizeName"></span>)
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <!-- Municipality Filter -->
                    <div class="form-group mb-3">
                        <label for="municipalitySelect" class="font-weight-bold">Select Municipality:</label>
                        <select name="municipality" id="municipalitySelect" class="form-control" required>
                            <option value="all">-- All Municipalities --</option>
                            <option value="all_except_division">-- All Municipalities (Except Division Office) --</option>

                            @foreach (\App\Models\Participant::distinct()->whereNotNull('municipality')->where('municipality', '!=', 'Division Office')->pluck('municipality')->sort() as $municipality)
                                <option value="{{ $municipality }}">{{ $municipality }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Position / Designation Filter -->
                    <div class="form-group mb-3">
                        <label for="positionSelect" class="font-weight-bold">Select Position / Designation:</label>
                        <select name="position" id="positionSelect" class="form-control" required>
                            <option value="all">-- All Positions --</option>
                            @foreach (\App\Models\Participant::distinct()->whereNotNull('designation')->pluck('designation')->sort() as $position)
                                <option value="{{ $position }}">{{ $position }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Number of Possible Winners -->
                    <div class="form-group mb-3">
                        <label for="winnerCountInput" class="font-weight-bold">Number of Winners to Draw:</label>
                        <input type="number" name="count" id="winnerCountInput" class="form-control" min="1"
                            value="1" required>
                        <small class="form-text text-muted">
                            Maximum available for this prize: <strong id="maxRemainingText">0</strong>
                        </small>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary font-weight-bold" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary font-weight-bold">
                        <i class="fas fa-trophy me-1"></i> Execute Pre-Draw
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Open Modal and Populate Settings
            document.querySelectorAll(".openPreDrawModal").forEach(button => {
                button.addEventListener("click", function() {
                    let prizeId = this.dataset.prizeId;
                    let prizeName = this.dataset.prizeName;
                    let remaining = this.dataset.remaining;

                    // Update form action dynamically
                    let actionUrl = "{{ route('admin.prizes.preDraw', ':id') }}".replace(':id',
                        prizeId);
                    document.getElementById("preDrawForm").action = actionUrl;

                    // Display info in UI
                    document.getElementById("modalPrizeName").textContent = prizeName;
                    document.getElementById("maxRemainingText").textContent = remaining;

                    // Set initial value & max limit for count input
                    let countInput = document.getElementById("winnerCountInput");
                    countInput.max = remaining;
                    countInput.value = Math.min(1, remaining);

                    // Show Bootstrap Modal
                    $('#preDrawModal').modal('show');
                });
            });
        });
    </script>
@endpush
