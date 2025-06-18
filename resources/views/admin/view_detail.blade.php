@extends('layout.admin')

@section('content')
    <div class="page-wrapper">
        <div class="page-breadcrumb">
            <div class="row">
                <div class="col-7 align-self-center">
                    <h3 class="page-title text-truncate text-dark font-weight-medium mb-1">Invoice Details</h3>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-header">
                            <h2>Invoice Information</h2>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Bill Type:</strong> {{ $invoice->bill_type }}</p>
                                    <p><strong>Address:</strong> {{ $invoice->address }}</p>
                                    <p><strong>CUPS:</strong> {{ $invoice->CUPS }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Billing Period:</strong> {{ $invoice->billing_period }}</p>
                                    <p><strong>Agent:</strong> {{ $invoice->user->name }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow mt-4">
                        <div class="card-header">
                            <h2>Related Offers</h2>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered dataTable">
                                        <thead class="bg-dark text-white">
                                            <tr>
                                                <th>#</th>
                                                <th>Product Name</th>

                                                <th>provider</th>
                                                <th>Saving</th>
                                                <th>sales Commission</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($invoice->offers as $offer)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $offer->product_name }}</td>

                                                    <td>{{ $offer->provider_name }}</td>
                                                    <td>{{ $offer->saving }}</td>
                                                    <td>{{ $offer->sales_commission }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center">No offers found for this invoice</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
