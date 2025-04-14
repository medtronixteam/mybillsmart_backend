@extends('layout.admin')

@section('content')
    <div class="page-wrapper">
        <div class="page-breadcrumb">
            <div class="row">
                <div class="col-7 align-self-center">
                    <h3 class="page-title text-truncate text-dark font-weight-medium mb-1">Invoice List</h3>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-header">
                            <h2 class="">Invoices</h2>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="bg-dark text-white">
                                            <tr>
                                                <th>#</th>
                                                <th>Bill Type</th>
                                                <th>Address</th>
                                                <th>CUPS</th>
                                                <th>Billing Period </th>
                                                <th>Agent </th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($invoiceList as $item)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $item->bill_type }}</td>
                                                    <td>{{ $item->address}}</td>
                                                    <td>{{ $item->CUPS }}</td>
                                                    <td>{{ $item->billing_period  }}</td>
                                                    <td>{{ $item->user->name }}</td>
                                                    <td>
                                                                <a href="{{ route('view.detail', $item->id) }}"
                                                                    class="btn btn-primary">
                                                                    View
                                                                </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="d-flex justify-content-center">
                                    {{ $invoiceList->links('pagination::bootstrap-5') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
