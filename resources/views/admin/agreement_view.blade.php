@extends('layout.admin')

@section('content')
    <div class="page-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-header bg-dark text-white">
                            <h2 class="mb-0">Agreement Information</h2>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <p><strong>Title:</strong> {{ $agreements->title }}</p>
                                        <p><strong>Description:</strong> {{ $agreements->description }}</p>

                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>status:</strong> {{ $agreements->status }}</p>
                                        <p><strong>Created At:</strong> {{ $agreements->created_at->format('d M, Y') }}</p>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- end of card --}}

                </div>
            </div>
        </div>
@endsection
