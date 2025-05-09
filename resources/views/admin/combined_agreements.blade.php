@extends('layout.admin')

@section('content')
    <div class="page-wrapper">
        <div class="page-breadcrumb">
            <div class="row">
                <div class="col-12 align-self-center">
                    <div class="d-flex justify-content-between">
                        <div> <h3 class="page-title text-truncate text-dark font-weight-medium mb-1">Combined Agreements</h3></div>
                        <div> <a href="{{ route('agreements') }}" class="btn btn-info">Electricity</a>
                            <a href="{{ route('gas.list') }}" class="btn btn-success">Gas</a>
                            <a href="{{ route('combined.list') }}" class="btn btn-warning">Combined</a></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-header">
                            <h2 class="">Agreements</h2>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="bg-dark text-white">
                                            <tr>
                                                <th>#</th>
                                                <th>Product Name</th>
                                                <th>Provider</th>
                                                <th>Agent Commision</th>
                                                <th>Agreement Type</th>
                                                <th>Customer Type</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($agreements as $item)
                                                <tr>
                                                    <td>{{ $item->id }}</td>
                                                    <td>{{ $item->product_name }}</td>
                                                    <td>{{ $item->provider_name }}</td>
                                                    <td>
                                                     {{ $item->sales_commision }}
                                                    </td>
                                                    <td>
                                                   {{ $item->agreement_type }}
                                                    </td>
                                                    <td>
                                                   {{ $item->customer_type }}
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
