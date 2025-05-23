@extends('layout.admin')

@section('content')
    <div class="page-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-header bg-dark text-white">
                            <h2 class="mb-0">Electricity Agreement Details</h2>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-6"><strong>Product Name:</strong> {{ $elecdata->product_name }}</div>
                                <div class="col-md-6"><strong>Provider Name:</strong> {{ $elecdata->provider_name }}</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4"><strong>Light Category:</strong> {{ $elecdata->light_category }}</div>
                                <div class="col-md-4"><strong>Fixed Rate:</strong> €{{ $elecdata->fixed_rate }}</div>
                                <div class="col-md-4"><strong>Customer Type:</strong> {{ ucfirst($elecdata->customer_type) }}</div>
                            </div>
                            <div class="row mb-3">
                                @for ($i = 1; $i <= 6; $i++)
                                    <div class="col-md-2"><strong>P{{ $i }}:</strong> {{ $elecdata->{'p'.$i} }}</div>
                                @endfor
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4"><strong>Discount Period Start:</strong> {{ $elecdata->discount_period_start }}</div>
                                <div class="col-md-4"><strong>Discount Period End:</strong> {{ $elecdata->discount_period_end }}</div>
                                <div class="col-md-4"><strong>Commission Type:</strong> {{ ucfirst($elecdata->commision_type) }}</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4"><strong>Meter Rental:</strong> €{{ $elecdata->meter_rental }}</div>
                                <div class="col-md-4"><strong>Sales Commission:</strong> €{{ $elecdata->sales_commission }}</div>
                                <div class="col-md-4"><strong>Points Per Deal:</strong> {{ $elecdata->points_per_deal }}</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6"><strong>Validity From:</strong> {{ $elecdata->validity_period_from }}</div>
                                <div class="col-md-6"><strong>Validity To:</strong> {{ $elecdata->validity_period_to }}</div>
                            </div>
                            <div class="mb-3">
                                <strong>Contract Terms:</strong>
                                <div class="border p-2">{{ $elecdata->contact_terms }}</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4"><strong>Contract Duration:</strong> {{ $elecdata->contract_duration }}</div>
                                <div class="col-md-4"><strong>Power Term:</strong> €{{ $elecdata->power_term }}</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6"><strong>Peak Hours:</strong> {{ $elecdata->peak }}</div>
                                <div class="col-md-6"><strong>Off-Peak Hours:</strong> {{ $elecdata->off_peak }}</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6"><strong>Energy Term By Time:</strong> {{ $elecdata->energy_term_by_time }}</div>
                                <div class="col-md-6"><strong>Variable Term By Tariff:</strong> {{ $elecdata->variable_term_by_tariff }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
