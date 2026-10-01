@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <!-- Header with Title and Print Action -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4 d-print-none">
            <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-award text-warning me-2"></i>School Winners</h1>
            <div>
                <!-- Button in View Header -->
                <button type="button" id="printSelectedBtn" class="btn btn-primary btn-sm shadow-sm">
                    <i class="fas fa-print me-1"></i> Print Selected
                </button>

                <script>
                    document.getElementById('printSelectedBtn').addEventListener('click', function() {
                        const selectedIds = Array.from(document.querySelectorAll('.winner-checkbox:checked'))
                            .map(cb => cb.value);

                        if (selectedIds.length === 0) {
                            alert('Please select at least one school winner to print.');
                            return;
                        }

                        const printUrl = "{{ route('admin.school-winners.print') }}?ids=" + selectedIds.join(',');
                        window.open(printUrl, '_blank');
                    });
                </script>
            </div>
        </div>

        <!-- Filter Card -->
        <div class="card shadow mb-4 d-print-none">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-filter me-1"></i> Filter Winners</h6>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('admin.school-winners.index') }}" class="row g-3 align-items-center">
                    <div class="col-md-5">
                        <label for="prize_id" class="form-label font-weight-bold">Filter by Prize:</label>
                        <select name="prize_id" id="prize_id" class="form-control" onchange="this.form.submit()">
                            <option value="">-- All Prizes --</option>
                            @foreach ($prizes as $prize)
                                <option value="{{ $prize->id }}"
                                    {{ request('prize_id') == $prize->id ? 'selected' : '' }}>
                                    {{ $prize->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mt-md-4">
                        <a href="{{ route('admin.school-winners.index') }}" class="btn btn-secondary">
                            <i class="fas fa-undo me-1"></i> Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Winners Table Card -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center d-print-none">
                <h6 class="m-0 font-weight-bold text-primary">List of Winning Schools</h6>
                <span class="text-muted small">Total Selected: <strong id="selectedCount">0</strong></span>
            </div>
            <div class="card-body">
                <!-- Print Header (Visible ONLY when printing) -->
                <div class="d-none d-print-block text-center mb-4">
                    <h2 class="mb-1"><strong>Official School Winners List</strong></h2>
                    <p class="text-muted mb-0">Generated Date: {{ now()->format('F d, Y h:i A') }}</p>
                    <hr>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="winnersTable" width="100%" cellspacing="0">
                        <thead class="thead-light">
                            <tr>
                                <th class="text-center d-print-none" style="width: 40px;">
                                    <input type="checkbox" id="selectAll">
                                </th>
                                <th style="width: 50px;">#</th>
                                <th>School Name</th>
                                <th>District / Municipality</th>
                                <th>Prize Won</th>
                                <th>Won Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($winners as $index => $winner)
                                <tr>
                                    <td class="text-center d-print-none">
                                        <input type="checkbox" class="winner-checkbox" value="{{ $winner->id }}">
                                    </td>
                                    <td>{{ $winners->firstItem() + $index }}</td>
                                    <td><strong>{{ $winner->school->school_name ?? 'N/A' }}</strong></td>
                                    <td>
                                        {{ $winner->school->district_name ?? '' }}
                                        @if ($winner->school->municipality)
                                            <span class="badge badge-info">{{ $winner->school->municipality }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-success">
                                            {{ $winner->prize->name ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td>{{ $winner->won_at ? \Carbon\Carbon::parse($winner->won_at)->format('M d, Y h:i A') : $winner->created_at->format('M d, Y h:i A') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        No school winners recorded yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-end d-print-none mt-3">
                    {{ $winners->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Custom Print Styling -->
    <style type="text/css">
        @media print {
            body * {
                visibility: hidden;
            }

            .card-body,
            .card-body * {
                visibility: visible;
            }

            .card-body {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }

            .d-print-none,
            .navbar,
            .sidebar,
            footer {
                display: none !important;
            }
        }
    </style>

    <!-- Select All Checkbox Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectAll = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.winner-checkbox');
            const selectedCount = document.getElementById('selectedCount');

            function updateCount() {
                const checked = document.querySelectorAll('.winner-checkbox:checked');
                if (selectedCount) {
                    selectedCount.textContent = checked.length;
                }
            }

            if (selectAll) {
                selectAll.addEventListener('change', function() {
                    checkboxes.forEach(cb => cb.checked = this.checked);
                    updateCount();
                });
            }

            checkboxes.forEach(cb => {
                cb.addEventListener('change', function() {
                    if (!this.checked && selectAll) {
                        selectAll.checked = false;
                    } else if (document.querySelectorAll('.winner-checkbox:checked').length ===
                        checkboxes.length) {
                        selectAll.checked = true;
                    }
                    updateCount();
                });
            });
        });
    </script>
@endsection
