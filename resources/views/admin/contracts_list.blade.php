@extends('layout.admin')

@section('content')
    <div class="page-wrapper">
        <div class="page-breadcrumb">
            <div class="row">
                <div class="col-7 align-self-center">
                    <h3 class="page-title text-truncate text-dark font-weight-medium mb-1">Contracts List</h3>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-header">
                            <h2 class="">Contracts</h2>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="bg-dark text-white">
                                            <tr>
                                                <th>#</th>
                                                {{-- <th>Client</th> --}}
                                                <th>contracted Provider</th>
                                                <th>contracted Rate</th>
                                                <th>closure Date</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($contracts as $item)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    {{-- <td>{{ $item->client_id }}</td> --}}
                                                    <td>{{ $item->contracted_provider }}</td>
                                                    <td>{{ $item->contracted_rate }}</td>
                                                    <td>{{ $item->closure_date }}</td>
                                                    <td>
                                                        @if ($item->status == 'pending')
                                                            <span class="badge badge-info">Pending</span>
                                                        @elseif ($item->status == 'completed')
                                                            <span class="badge badge-success">Completed</span>
                                                        @else
                                                            <span class="badge badge-warning">Rejected</span>
                                                        @endif
                                                    </td>
                                                        <td>
                                                                <a href="{{ route('offers.view', $item->offer_id) }}"
                                                                    class="btn btn-primary">
                                                                    View Offer
                                                                </a>
                                                        </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
