@extends('layout.admin')

@section('content')
    <div class="page-wrapper">
        <div class="page-breadcrumb">
            <div class="row">
                <div class="col-7 align-self-center">
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-12 col-md-10">
                    <div class="card">
                        <div class="card-header">
                            <h2>{{$data?"Update":"Create"}} Electricity Agreement</h2>
                        </div>
                        <div class="card-body">
                            @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif
                            <form action="{{ $data ? route('electricity.update') :  route('electricity.store') }}" method="POST">
                                @csrf
                                <input type="text" name="electricity_id" value="{{$data?$data->id:"" }}" hidden>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="product_name">Product Name</label>
                                            <input type="text" class="form-control" id="product_name" name="product_name" value="{{$data? $data->product_name:''}}" required>
                                            @error('product_name')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="provider_name">Provider Name</label>
                                            <input type="text" class="form-control" id="provider_name" name="provider_name" value="{{$data? $data->provider_name:''}}" required>
                                            @error('provider_name')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="light_category">Light Category</label>
                                            <input type="text" class="form-control" id="light_category" name="light_category" value="{{$data? $data->light_category:''}}" required>
                                            @error('light_category')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="fixed_rate">Fixed Rate (€/kWh)</label>
                                            <input type="number" step="0.001" min="0"  class="form-control" id="fixed_rate" name="fixed_rate" value="{{$data? $data->fixed_rate:''}}" required>
                                            @error('fixed_rate')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="customer_type">Customer Type</label>
                                           <select class="form-control" name="customer_type" id="">
                                               <option {{$data?($data->customer_type=='residential'?'selected':''):''}} value="residential">Residential</option>
                                               <option {{$data?($data->customer_type=='residential'?'selected':''):''}} value="business">business</option>
                                           </select>
                                            @error('customer_type')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="p1">P1</label>
                                            <input type="number" step="0.001"  class="form-control" id="p1" name="p1" value="{{$data? $data->p1:''}}">
                                            @error('p1')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="p2">P2</label>
                                            <input type="number" step="0.001"  class="form-control" id="p2" name="p2" value="{{$data? $data->p2:''}}">
                                            @error('p2')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="p3">P3</label>
                                            <input type="number" step="0.001"  class="form-control" id="p3" name="p3" value="{{$data? $data->p3:''}}">
                                            @error('p3')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="p4">P4</label>
                                            <input type="number" step="0.001"  class="form-control" id="p4" name="p4" value="{{$data? $data->p4:''}}">
                                            @error('p4')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="p5">P5</label>
                                            <input type="number" step="0.001"  class="form-control" id="p5" name="p5" value="{{$data? $data->p5:''}}">
                                            @error('p5')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="p6">P6</label>
                                            <input type="number" step="0.001"  class="form-control" id="p6" name="p6" value="{{$data? $data->p6:''}}">
                                            @error('p6')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                  <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="discount_period_start">Discount Period Start</label>
                                            <input type="date" class="form-control" id="discount_period_start" name="discount_period_start" value="{{$data? $data->discount_period_start:''}}">
                                            @error('discount_period_start')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="discount_period_end">Discount Period End</label>
                                            <input type="date" class="form-control" id="discount_period_end" name="discount_period_end" value="{{$data? $data->discount_period_end:''}}">
                                            @error('discount_period_end')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                      <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="commision_type">Commission Type</label>
                                           <select class="form-control" name="commision_type" id="">
                                               <option {{$data? $data->commision_type == 'percentage' ? 'selected' : '' : ''}} value="percentage">Percentage</option>
                                               <option {{$data? $data->commision_type == 'fixed' ? 'selected' : '' : ''}} value="fixed">Fixed</option>
                                           </select>
                                            @error('commision_type')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="meter_rental">Meter Rental (€)</label>
                                            <input type="number" step="0.01" class="form-control" id="meter_rental" name="meter_rental" value="{{ $data ? $data->meter_rental : '' }}" required>
                                            @error('meter_rental')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="sales_commission">Sales Commission (€)</label>
                                            <input type="number" step="0.01" class="form-control" id="sales_commission" name="sales_commission" value="{{$data? $data->sales_commission:''}}" required>
                                            @error('sales_commission')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="points_per_deal">Points Per Deal</label>
                                            <input value="{{$data? $data->points_per_deal:''}}" type="number" class="form-control" id="points_per_deal" name="points_per_deal"  required>
                                            @error('points_per_deal')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="validity_period_from">Validity Period From</label>
                                            <input type="date" class="form-control" id="validity_period_from" name="validity_period_from" value={{$data? $data->validity_period_from:''}} required>
                                            @error('validity_period_from')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="validity_period_to">Validity Period To</label>
                                            <input type="date" class="form-control" id="validity_period_to" name="validity_period_to" value={{$data? $data->validity_period_to:''}} required>
                                            @error('validity_period_to')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="contact_terms">Contract Terms</label>
                                    <textarea class="form-control" id="contact_terms" name="contact_terms" rows="3" required>{{ $data ? $data->contact_terms : '' }}</textarea>
                                    @error('contact_terms')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="contract_duration">Contract Duration</label>
                                            <input type="text" class="form-control" id="contract_duration" name="contract_duration" value="{{$data? $data->contract_duration:''}}" required>
                                            @error('contract_duration')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="power_term">Power Term (€/kW)</label>
                                            <input type="number"  class="form-control" id="power_term" name="power_term" value="{{$data? $data->power_term:''}}" required>
                                            @error('power_term')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="peak">Peak Hours</label>
                                            <input type="text" class="form-control" id="peak" name="peak" value="{{$data? $data->peak:''}}" required>
                                            @error('peak')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="off_peak">Off-Peak Hours</label>
                                            <input type="text" class="form-control" id="off_peak" name="off_peak" value="{{$data? $data->off_peak:''}}" required>
                                            @error('off_peak')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="energy_term_by_time">Energy Term By Time</label>
                                            <input type="text" class="form-control" id="energy_term_by_time" name="energy_term_by_time" value="{{$data? $data->energy_term_by_time:''}}" required>
                                            @error('energy_term_by_time')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="variable_term_by_tariff">Variable Term By Tariff</label>
                                            <input type="text" class="form-control" id="variable_term_by_tariff" name="variable_term_by_tariff" value="{{$data? $data->variable_term_by_tariff:''}}" required>
                                            @error('variable_term_by_tariff')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary">Save</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
