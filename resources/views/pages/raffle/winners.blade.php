@extends('layouts.public')

@section('content')
    <div class="text-center mb-5 ">
        <h1 class="h3"><i class="fas fa-trophy text-warning"></i> <span class="text-primary"><strong>Raffle Winners</strong></span></h1>
        <p class="text-primary">National Teachers' Day 2025 Celebration</p>
    </div>

    @foreach($prizes as $prize)
        <div class="card mb-5 card-primary">
            <div class="card-header py-3 bg-primary">
                <h6 class="m-0 font-weight-bold text-white">
                    {{ $prize->name }}
                    <span class="badge bg-light text-dark">{{ $prize->quantity }} Winners</span>
                </h6>
            </div>
            <div class="card-body">
                @if($prize->winners->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover align-middle mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Winner Name</th>
                                    <th>School / Office</th>
                                    <th>District / Division</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($prize->winners as $index => $winner)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td class="fw-bold text-primary">{{ $winner->participant->full_name }}</td>
                                        <td>{{ $winner->participant->school_office }}</td>
                                        <td>{{ $winner->participant->district_division }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted mb-0">No winners drawn yet for this prize.</p>
                @endif
            </div>
        </div>
    @endforeach
@endsection
