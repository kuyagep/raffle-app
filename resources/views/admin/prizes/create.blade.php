@extends('layouts.app')
@section('title', 'Create Prizes')
@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1 class="h3 text-gray-800">Add Prize</h1>
            <a href="{{ route('admin.prizes.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Prizes
            </a>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-body">
                        <form action="{{ route('admin.prizes.store') }}" method="POST">
                            @csrf

                            <!-- Prize Name -->
                            <div class="form-group">
                                <label for="name" class="font-weight-bold">Prize Name</label>
                                <input type="text" name="name" id="name" placeholder="Enter the prize name"
                                    class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}"
                                    required>
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Quantity -->
                            <div class="form-group">
                                <label for="quantity" class="font-weight-bold">Quantity</label>
                                <input type="number" name="quantity" id="quantity" placeholder="Enter the quantity"
                                    class="form-control @error('quantity') is-invalid @enderror"
                                    value="{{ old('quantity') }}" min="1" required>
                                @error('quantity')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Submit -->
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Prize
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
