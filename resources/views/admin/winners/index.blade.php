@extends('layouts.app')
@section('title', 'Winners')
@section('content')
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">Raffle Winners</h1>
            <div>
                <!-- Export to Excel Button -->
                <button onclick="exportToExcel()" class="btn btn-sm btn-success mr-2" title="Export table data to Excel">
                    <i class="fas fa-file-excel"></i> Export Excel
                </button>
                <!-- Print Selected Button -->
                <button onclick="printSelected()" class="btn btn-sm btn-deped">
                    <i class="fas fa-print"></i> Print Selected
                </button>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-body">

                <!-- Filter Controls: Search, Prize Filter & Reload Button -->
                <div class="row mb-3">
                    <!-- Search Input Field -->
                    <div class="col-md-4 mb-2 mb-md-0">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                            </div>
                            <input type="text" id="searchWinner" class="form-control"
                                placeholder="Search by Winner Name...">
                        </div>
                    </div>

                    <!-- Prize Filter Dropdown -->
                    <div class="col-md-4 mb-2 mb-md-0">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-gift"></i></span>
                            </div>
                            <select id="filterPrize" class="form-control">
                                <option value="">All Prizes</option>
                                {{-- Dynamically extract unique prizes from collection --}}
                                @foreach ($winners->pluck('prize.name')->filter()->unique() as $prizeName)
                                    <option value="{{ $prizeName }}">{{ $prizeName }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Reload / Reset Filters Button -->
                    <div class="col-md-4 d-flex align-items-center">
                        <button type="button" id="reloadBtn" class="btn btn-secondary btn-block-sm"
                            title="Reset Filters & Reload View">
                            <i class="fas fa-sync-alt me-1"></i> Reload
                        </button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped" id="winnersTable">
                        <thead class="bg-theme text-white">
                            <tr>
                                <th><input type="checkbox" id="selectAll"></th>
                                <th>Prize</th>
                                <th>Winner Name</th>
                                <th>District</th>
                                <th>School</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($winners as $winner)
                                <tr class="winner-row">
                                    <td>
                                        <input type="checkbox" class="rowCheckbox" value="{{ $winner->id }}"
                                            {{ in_array($winner->id, session('selected_winners', [])) ? 'checked' : '' }}>
                                    </td>
                                    <td class="prize-name">{{ $winner->prize->name }}</td>
                                    <td class="winner-name"><b>{{ $winner->participant->full_name }}</b></td>
                                    <td>{{ $winner->participant->district_division ?? '-' }}</td>
                                    <td>{{ $winner->participant->school_office ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr id="noRecordsRow">
                                    <td colspan="5" class="text-center text-muted py-4">No winners recorded yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let selectedWinners = @json(session('selected_winners', []));

        // Combined Filter Function (Handles both Search Input & Prize Select)
        function filterTable() {
            let nameFilter = document.getElementById("searchWinner").value.toLowerCase().trim();
            let prizeFilter = document.getElementById("filterPrize").value.toLowerCase().trim();
            let rows = document.querySelectorAll("#winnersTable tbody .winner-row");

            rows.forEach(row => {
                let nameCell = row.querySelector(".winner-name");
                let prizeCell = row.querySelector(".prize-name");

                let nameText = nameCell ? (nameCell.textContent || nameCell.innerText).toLowerCase() : "";
                let prizeText = prizeCell ? (prizeCell.textContent || prizeCell.innerText).toLowerCase() : "";

                let matchesName = nameText.includes(nameFilter);
                let matchesPrize = (prizeFilter === "") || (prizeText === prizeFilter);

                if (matchesName && matchesPrize) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        }

        // Keyup Event for Search Input
        document.getElementById("searchWinner").addEventListener("keyup", filterTable);

        // Change Event for Prize Filter Dropdown
        document.getElementById("filterPrize").addEventListener("change", filterTable);

        // Reload Button Click Handler (Resets filters or reloads the page if desired)
        document.getElementById("reloadBtn").addEventListener("click", function() {
            // Visual spin effect on reload button icon
            let icon = this.querySelector("i");
            if (icon) icon.classList.add("fa-spin");

            // Option 1: Perform full page reload to fetch latest data from DB
            window.location.reload();

            /*
            // Option 2: Soft reset filters without full page reload (Uncomment if preferred)
            document.getElementById("searchWinner").value = "";
            document.getElementById("filterPrize").value = "";
            filterTable();
            setTimeout(() => { if (icon) icon.classList.remove("fa-spin"); }, 300);
            */
        });

        // Export Visible Filtered Data to Excel / CSV
        function exportToExcel() {
            let rows = document.querySelectorAll("#winnersTable tbody .winner-row");
            let csvContent = "\uFEFF"; // BOM for Unicode Excel compatibility

            // Add Header Row
            csvContent += "Prize,Winner Name,District,School\n";

            let count = 0;
            rows.forEach(row => {
                if (row.style.display !== "none") {
                    let cells = row.querySelectorAll("td");
                    if (cells.length >= 5) {
                        let prize = '"' + cells[1].innerText.replace(/"/g, '""').trim() + '"';
                        let winner = '"' + cells[2].innerText.replace(/"/g, '""').trim() + '"';
                        let district = '"' + cells[3].innerText.replace(/"/g, '""').trim() + '"';
                        let school = '"' + cells[4].innerText.replace(/"/g, '""').trim() + '"';

                        csvContent += `${prize},${winner},${district},${school}\n`;
                        count++;
                    }
                }
            });

            if (count === 0) {
                Swal.fire("Export Failed", "No visible rows to export.", "warning");
                return;
            }

            // Create Blob and trigger direct browser download
            let blob = new Blob([csvContent], {
                type: "text/csv;charset=utf-8;"
            });
            let link = document.createElement("a");
            let url = URL.createObjectURL(blob);

            let date = new Date().toISOString().slice(0, 10);
            link.setAttribute("href", url);
            link.setAttribute("download", `Raffle_Winners_${date}.csv`);
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        // Checkbox toggle
        document.querySelectorAll(".rowCheckbox").forEach(cb => {
            cb.addEventListener("change", function() {
                let id = this.value;
                if (this.checked) {
                    if (!selectedWinners.includes(id)) {
                        selectedWinners.push(id);
                    }
                } else {
                    selectedWinners = selectedWinners.filter(w => w !== id);
                }

                // Store in session (AJAX)
                fetch("{{ route('admin.winners.updateSelection') }}", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({
                        selected: selectedWinners
                    })
                });
            });
        });

        // Select All (Only checks currently visible rows)
        document.getElementById("selectAll").addEventListener("change", function() {
            document.querySelectorAll(".rowCheckbox").forEach(cb => {
                let row = cb.closest("tr");
                if (row && row.style.display !== "none") {
                    cb.checked = this.checked;
                    cb.dispatchEvent(new Event("change"));
                }
            });
        });

        // Print Selected
        function printSelected() {
            if (selectedWinners.length === 0) {
                Swal.fire("No Selection", "Please select at least one winner to print.", "warning");
                return;
            }
            let url = "{{ route('admin.winners.print') }}?ids=" + selectedWinners.join(",");
            window.open(url, "_blank");
        }

        // On page reload, reset selection
        window.addEventListener("load", function() {
            fetch("{{ route('admin.winners.resetSelection') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Content-Type": "application/json"
                }
            }).then(() => {
                document.querySelectorAll(".rowCheckbox").forEach(cb => cb.checked = false);
                let selectAll = document.getElementById("selectAll");
                if (selectAll) selectAll.checked = false;
                selectedWinners = [];
            });
        });
    </script>
@endpush
