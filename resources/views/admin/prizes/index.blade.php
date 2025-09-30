@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 text-gray-800">Prizes</h1>
    <a href="{{ route('admin.prizes.create') }}" class="btn btn-deped">
        <i class="fas fa-plus"></i> Add Prize
    </a>
</div>

<div class="card shadow mb-4">
    <div class="card-body">
        <table class="table table-striped" >
           <thead class="bg-theme">
                <tr>
                    <th>Name</th>
                    <th>Quantity</th>
                    <th>Winners</th>
                    <th>Remaining</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($prizes as $prize)
                <tr>
                    <td>{{ $prize->name }}</td>
                    <td>{{ $prize->quantity }}</td>
                    <td>{{ $prize->winners->count() }}</td>
                    <td>{{ $prize->quantity - $prize->winners->count() }}</td>
                    <td>
                        @if($prize->winners->count() < $prize->quantity)
                            <form action="{{ route('admin.prizes.draw', $prize->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-deped btn-sm">
                                    <i class="fas fa-random"></i> Draw Winner
                                </button>
                            </form>
                        @else
                            <span class="badge badge-success">Completed</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
