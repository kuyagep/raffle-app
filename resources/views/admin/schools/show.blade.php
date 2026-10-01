@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow border-0">
                    <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold"><i class="fas fa-school me-1"></i> School Details</h6>
                        <div>
                            <a href="{{ route('admin.schools.edit', $school->id) }}"
                                class="btn btn-sm btn-light me-1">Edit</a>
                            <a href="{{ route('admin.schools.index') }}" class="btn btn-sm btn-dark">Back</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered mb-0">
                            <tr>
                                <th width="30%" class="bg-light">School Name</th>
                                <td><strong>{{ $school->school_name }}</strong></td>
                            </tr>
                            <tr>
                                <th class="bg-light">District Name</th>
                                <td>{{ $school->district_name }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Municipality</th>
                                <td>{{ $school->municipality }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Designation</th>
                                <td>{{ $school->designation ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">School / Office</th>
                                <td>{{ $school->school_office ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Sex</th>
                                <td>{{ $school->sex ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Email</th>
                                <td>{{ $school->email ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Contact Number</th>
                                <td>{{ $school->contact_number ?? 'N/A' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
