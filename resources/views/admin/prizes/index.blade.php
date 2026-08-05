@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h3 text-gray-800">Prizes</h1>
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
                            <th>Name</th>
                            <th>Quantity</th>
                            <th>Winners</th>
                            <th>Remaining</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($prizes as $index => $prize)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $prize->name }}</td>
                                <td>{{ $prize->quantity }}</td>
                                <td>{{ $prize->winners->count() }}</td>
                                <td>{{ $prize->quantity - $prize->winners->count() }}</td>
                                <td>
                                    @if ($prize->winners->count() < $prize->quantity)
                                        <form action="{{ route('admin.prizes.draw', $prize->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-deped btn-sm">
                                                <i class="fas fa-random"></i> Pre Draw ALL
                                            </button>
                                        </form>

                                        <!-- Pre Draw Button -->
                                        <form action="{{ route('admin.prizes.preDraw', $prize->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-deped btn-sm">
                                                <i class="fas fa-dice"></i> Pre Draw
                                            </button>
                                        </form>
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
                                            <button type="submit" class="btn btn-sm btn-danger">
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
@endsection
