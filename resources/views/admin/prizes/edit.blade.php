@extends('layouts.app')

@section('content')
    <div class="container-fluid">

        <h1 class="h3 mb-4 text-gray-800">Edit Prize</h1>

        <div class="card mb-4">
            <div class="card-body">
                <form action="{{ route('admin.prizes.update', $prize) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Prize Name</label>
                        <input type="text" name="name" value="{{ old('name', $prize->name) }}" class="form-control"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Quantity</label>
                        <input type="number" name="quantity" value="{{ old('quantity', $prize->quantity) }}"
                            class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('admin.prizes.index') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>

    </div>
@endsection
