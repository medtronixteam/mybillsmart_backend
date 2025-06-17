<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agreement;
use App\Models\Product;

class AgreementController extends Controller
{



  public function agreements(){
        $agreements = Product::where('agreement_type','electricity')->latest()->get();
        return view('admin.electricity_agreements', compact('agreements'));
    }
  public function gasAgreements(){
        $agreements = Product::where('agreement_type','gas')->latest()->get();
        return view('admin.gas_agreements', compact('agreements'));
    }
  public function combinedAgreements(){
        $agreements = Product::where('agreement_type','both')->latest()->get();
        return view('admin.combined_agreements', compact('agreements'));
    }






   public function electricityStore(Request $request)
{
    $validated = $request->validate([
        'product_name' => 'required|string',
        'provider_name' => 'required|string',
        'light_category' => 'required|string',
        'fixed_rate' => 'required|numeric',
        'customer_type' => 'required|string',
         'commision_type' => 'required|in:percentage,fixed',

        'p1' => 'nullable|numeric',
        'p2' => 'nullable|numeric',
        'p3' => 'nullable|numeric',
        'p4' => 'nullable|numeric',
        'p5' => 'nullable|numeric',
        'p6' => 'nullable|numeric',
        'discount_period_start' => 'nullable|date',
        'discount_period_end' => 'nullable|date',
        'meter_rental' => 'required|numeric',
        'sales_commission' => 'required|numeric',
        'points_per_deal' => 'required|numeric',
        'validity_period_from' => 'required|date',
        'validity_period_to' => 'required|date',
        'contact_terms' => 'required|string',
        'contract_duration' => 'required|string',
        'power_term' => 'required|numeric',
        'peak' => 'required|string',
        'off_peak' => 'required|string',
        'energy_term_by_time' => 'required|string',
        'variable_term_by_tariff' => 'required|string',
    ]);

    $agreement = Product::create(array_merge($validated, [
        'group_id' => auth()->id(),
        'addedby_id' => auth()->id(),
        'agreement_type' => 'electricity',
        'product_type' => 'global',
    ]));

    return back()->with('success', 'Agreement created successfully.');
}


 public function electricityCreate(){

        return view('admin.create_elec_agreement',['data'=>false]);
    }


      function electricityEdit($editId) {
        $elecdata=Product::find($editId);
        return view('admin.create_elec_agreement',['data'=>$elecdata]);
    }
 public function electricityDelete($deleteId)
    {
    $peoducts = Product::findOrFail($deleteId);
    $peoducts->delete();
    return redirect()->back();
    }


      function electricityUpdate(Request $request){

        $valid=$request->validate([
            'product_name' => 'required|string',
        'provider_name' => 'required|string',
        'light_category' => 'required|string',
        'fixed_rate' => 'required|numeric',
        'customer_type' => 'required|string',
         'commision_type' => 'required|in:percentage,fixed',
        'p1' => 'nullable|numeric',
        'p2' => 'nullable|numeric',
        'p3' => 'nullable|numeric',
        'p4' => 'nullable|numeric',
        'p5' => 'nullable|numeric',
        'p6' => 'nullable|numeric',
        'discount_period_start' => 'nullable|date',
        'discount_period_end' => 'nullable|date',
        'meter_rental' => 'required|numeric',
        'sales_commission' => 'required|numeric',
        'points_per_deal' => 'required|numeric',
        'validity_period_from' => 'required|date',
        'validity_period_to' => 'required|date',
        'contact_terms' => 'required|string',
        'contract_duration' => 'required|string',
        'power_term' => 'required|numeric',
        'peak' => 'required|string',
        'off_peak' => 'required|string',
        'energy_term_by_time' => 'required|string',
        'variable_term_by_tariff' => 'required|string',
        ]);
        Product::find($request->electricity_id)->update([
            'product_name' => $request->product_name,
            'provider_name' => $request->provider_name,
            'light_category' => $request->light_category,
            'fixed_rate' => $request->fixed_rate,
            'customer_type' => $request->customer_type,
            'commision_type' => $request->commision_type,
            'p1' => $request->p1,
            'p2' => $request->p2,
            'p3' => $request->p3,
            'p4' => $request->p4,
            'p5' => $request->p5,
            'p6' => $request->p6,
            'discount_period_start' => $request->discount_period_start,
            'discount_period_end' => $request->discount_period_end,
            'meter_rental' => $request->meter_rental,
            'sales_commission' => $request->sales_commission,
            'points_per_deal' => $request->points_per_deal,
            'validity_period_from' => $request->validity_period_from,
            'validity_period_to' => $request->validity_period_to,
            'contact_terms' => $request->contact_terms,
            'contract_duration' => $request->contract_duration,
            'power_term' => $request->power_term,
            'peak' => $request->peak,
            'off_peak' => $request->off_peak,
            'energy_term_by_time' => $request->energy_term_by_time,
            'variable_term_by_tariff' => $request->variable_term_by_tariff,

        ]);

        return back()->with('success', 'Agreement updated successfully.');
    }

    public function electricityView($viewId){
        $elecdata=Product::find($viewId);
        return view('admin.view_elec_agreement',compact('elecdata'));
    }

     public function gasCreate(){

        return view('admin.create_gas_agreement',['data'=>false]);
    }
       public function gasStore(Request $request)
{
    $validated = $request->validate([
        'product_name' => 'required|string',
        'provider_name' => 'required|string',
        'light_category' => 'required|string',
        'fixed_rate' => 'required|numeric',
        'customer_type' => 'required|string',
         'commision_type' => 'required|in:percentage,fixed',
        'rl1' => 'nullable|numeric',
        'rl2' => 'nullable|numeric',
        'rl3' => 'nullable|numeric',
        'discount_period_start' => 'nullable|date',
        'discount_period_end' => 'nullable|date',
        'meter_rental' => 'required|numeric',
        'sales_commission' => 'required|numeric',
        'points_per_deal' => 'required|numeric',
        'validity_period_from' => 'required|date',
        'validity_period_to' => 'required|date',
        'contact_terms' => 'required|string',
        'contract_duration' => 'required|string',
        'power_term' => 'required|numeric',
        'peak' => 'required|string',
        'off_peak' => 'required|string',
        'energy_term_by_time' => 'required|string',
        'variable_term_by_tariff' => 'required|string',
    ]);

    $agreement = Product::create(array_merge($validated, [
        'group_id' => auth()->id(),
        'addedby_id' => auth()->id(),
        'agreement_type' => 'gas',
        'product_type' => 'global',
    ]));

    return back()->with('success', 'Agreement created successfully.');
}

 public function gasDelete($deleteId)
    {
    $peoducts = Product::findOrFail($deleteId);
    $peoducts->delete();
    return redirect()->back();
    }

      function gasEdit($editId) {
        $gasdata=Product::find($editId);
        return view('admin.create_gas_agreement',['data'=>$gasdata]);
    }


       function gasUpdate(Request $request){

        $valid=$request->validate([
          'product_name' => 'required|string',
        'provider_name' => 'required|string',
        'light_category' => 'required|string',
        'fixed_rate' => 'required|numeric',
        'customer_type' => 'required|string',
         'commision_type' => 'required|in:percentage,fixed',
        'rl1' => 'nullable|numeric',
        'rl2' => 'nullable|numeric',
        'rl3' => 'nullable|numeric',
        'discount_period_start' => 'nullable|date',
        'discount_period_end' => 'nullable|date',
        'meter_rental' => 'required|numeric',
        'sales_commission' => 'required|numeric',
        'points_per_deal' => 'required|numeric',
        'validity_period_from' => 'required|date',
        'validity_period_to' => 'required|date',
        'contact_terms' => 'required|string',
        'contract_duration' => 'required|string',
        'power_term' => 'required|numeric',
        'peak' => 'required|string',
        'off_peak' => 'required|string',
        'energy_term_by_time' => 'required|string',
        'variable_term_by_tariff' => 'required|string',
        ]);
        Product::find($request->gas_id)->update([
            'product_name' => $request->product_name,
            'provider_name' => $request->provider_name,
            'light_category' => $request->light_category,
            'fixed_rate' => $request->fixed_rate,
            'customer_type' => $request->customer_type,
            'commision_type' => $request->commision_type,
            'rl1' => $request->rl1,
            'rl2' => $request->rl2,
            'rl3' => $request->rl3,
            'discount_period_start' => $request->discount_period_start,
            'discount_period_end' => $request->discount_period_end,
            'meter_rental' => $request->meter_rental,
            'sales_commission' => $request->sales_commission,
            'points_per_deal' => $request->points_per_deal,
            'validity_period_from' => $request->validity_period_from,
            'validity_period_to' => $request->validity_period_to,
            'contact_terms' => $request->contact_terms,
            'contract_duration' => $request->contract_duration,
            'power_term' => $request->power_term,
            'peak' => $request->peak,
            'off_peak' => $request->off_peak,
            'energy_term_by_time' => $request->energy_term_by_time,
            'variable_term_by_tariff' => $request->variable_term_by_tariff,

        ]);

        return back()->with('success', 'Agreement updated successfully.');
    }
     public function gasView($viewId){
        $gasdata=Product::find($viewId);
        return view('admin.view_gas_agreement',compact('gasdata'));
    }
     public function combinedCreate(){

        return view('admin.create_both_agreement',['data'=>false]);
    }
     public function combinedStore(Request $request)
{
    $validated = $request->validate([
        'product_name' => 'required|string',
        'provider_name' => 'required|string',
        'light_category' => 'required|string',
        'fixed_rate' => 'required|numeric',
        'customer_type' => 'required|string',
        'commision_type' => 'required|in:percentage,fixed',
        'rl1' => 'nullable|numeric',
        'rl2' => 'nullable|numeric',
        'rl3' => 'nullable|numeric',
        'p1' => 'nullable|numeric',
        'p2' => 'nullable|numeric',
        'p3' => 'nullable|numeric',
        'p4' => 'nullable|numeric',
        'p5' => 'nullable|numeric',
        'p6' => 'nullable|numeric',
        'discount_period_start' => 'nullable|date',
        'discount_period_end' => 'nullable|date',
        'meter_rental' => 'required|numeric',
        'sales_commission' => 'required|numeric',
        'points_per_deal' => 'required|numeric',
        'validity_period_from' => 'required|date',
        'validity_period_to' => 'required|date',
        'contact_terms' => 'required|string',
        'contract_duration' => 'required|string',
        'power_term' => 'required|numeric',
        'peak' => 'required|string',
        'off_peak' => 'required|string',
        'energy_term_by_time' => 'required|string',
        'variable_term_by_tariff' => 'required|string',
    ]);

    $agreement = Product::create(array_merge($validated, [
        'group_id' => auth()->id(),
        'addedby_id' => auth()->id(),
        'agreement_type' => 'both',
        'product_type' => 'global',
    ]));

    return back()->with('success', 'Agreement created successfully.');
}

public function combinedDelete($deleteId)
    {
    $peoducts = Product::findOrFail($deleteId);
    $peoducts->delete();
    return redirect()->back();
    }

      function combinedEdit($editId) {
        $bothdata=Product::find($editId);
        return view('admin.create_both_agreement',['data'=>$bothdata]);
    }

        function combinedUpdate(Request $request){

        $valid=$request->validate([
          'product_name' => 'required|string',
        'provider_name' => 'required|string',
        'light_category' => 'required|string',
        'fixed_rate' => 'required|numeric',
        'customer_type' => 'required|string',
        'commision_type' => 'required|in:percentage,fixed',
        'rl1' => 'nullable|numeric',
        'rl2' => 'nullable|numeric',
        'rl3' => 'nullable|numeric',
        'p1' => 'nullable|numeric',
        'p2' => 'nullable|numeric',
        'p3' => 'nullable|numeric',
        'p4' => 'nullable|numeric',
        'p5' => 'nullable|numeric',
        'p6' => 'nullable|numeric',
        'discount_period_start' => 'nullable|date',
        'discount_period_end' => 'nullable|date',
        'meter_rental' => 'required|numeric',
        'sales_commission' => 'required|numeric',
        'points_per_deal' => 'required|numeric',
        'validity_period_from' => 'required|date',
        'validity_period_to' => 'required|date',
        'contact_terms' => 'required|string',
        'contract_duration' => 'required|string',
        'power_term' => 'required|numeric',
        'peak' => 'required|string',
        'off_peak' => 'required|string',
        'energy_term_by_time' => 'required|string',
        'variable_term_by_tariff' => 'required|string',
        ]);
        Product::find($request->both_id)->update([
            'product_name' => $request->product_name,
            'provider_name' => $request->provider_name,
            'light_category' => $request->light_category,
            'fixed_rate' => $request->fixed_rate,
            'customer_type' => $request->customer_type,
            'commision_type' => $request->commision_type,
            'rl1' => $request->rl1,
            'rl2' => $request->rl2,
            'rl3' => $request->rl3,
            'p1' => $request->p1,
            'p2' => $request->p2,
            'p3' => $request->p3,
            'p4' => $request->p4,
            'p5' => $request->p5,
            'p6' => $request->p6,
            'discount_period_start' => $request->discount_period_start,
            'discount_period_end' => $request->discount_period_end,
            'meter_rental' => $request->meter_rental,
            'sales_commission' => $request->sales_commission,
            'points_per_deal' => $request->points_per_deal,
            'validity_period_from' => $request->validity_period_from,
            'validity_period_to' => $request->validity_period_to,
            'contact_terms' => $request->contact_terms,
            'contract_duration' => $request->contract_duration,
            'power_term' => $request->power_term,
            'peak' => $request->peak,
            'off_peak' => $request->off_peak,
            'energy_term_by_time' => $request->energy_term_by_time,
            'variable_term_by_tariff' => $request->variable_term_by_tariff,

        ]);

        return back()->with('success', 'Agreement updated successfully.');
    }

    public function combinedView($viewId){
        $bothdata=Product::find($viewId);
        return view('admin.view_both_agreement',compact('bothdata'));
    }
}
