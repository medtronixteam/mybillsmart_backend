@extends('layout.admin')

@section('content')
    <div class="page-wrapper">
        <div class="page-breadcrumb">
            <div class="row">
                <div class="col-7 align-self-center">
                    <h3 class="page-title text-truncate text-dark font-weight-medium mb-1">View Offer</h3>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-header">
                            <h2 class="">Offer</h2>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                @if(!$offer)
                                    <div class="alert alert-danger">
                                        Offer not found for this contract
                                    </div>
                                @else
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="bg-dark text-white">
                                            <tr>
                                                <th>#</th>
                                                <th>Product Name</th>
                                                <th>Provider Name</th>
                                                <th>Saving</th>
                                                <th>Sales Commission</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>{{ $offer->id }}</td>
                                                <td>{{ $offer->product_name}}</td>
                                                <td>{{ $offer->provider_name}}</td>
                                                <td>{{ $offer->saving }}</td>
                                                <td>{{ $offer->sales_commission}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
