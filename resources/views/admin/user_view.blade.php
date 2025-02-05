@extends('layout.admin')

@section('content')
    <div class="page-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-header bg-dark text-white">
                            <h2 class="mb-0">Personal Information</h2>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <p><strong>Name:</strong> {{ $user->name }}</p>
                                        <p><strong>Email:</strong> {{ $user->email }}</p>
                                        <p><strong>Created At:</strong> {{ $user->created_at->format('d M, Y') }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Status:</strong>
                                            @if ($user->status == 1)
                                                <span class="badge badge-success">Enable</span>
                                            @else
                                                <span class="badge badge-warning">Disable</span>
                                            @endif
                                        </p>
                                        <p><strong>Role:</strong> {{ ucfirst($user->role) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection
