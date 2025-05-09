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
  public function electricityCreate(){

        return view('admin.create_elec_agreement');
    }
  public function gasCreate(){

        return view('admin.create_gas_agreement');
    }
  public function bothCreate(){

        return view('admin.create_both_agreement');
    }


   public function electricityStore(Request $request)
{
    $validated = $request->validate([
        'product_name' => 'required|string',
        'provider_name' => 'required|string',
        'light_category' => 'required|string',
        'fixed_rate' => 'required|numeric',
        'customer_type' => 'required|string',
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
        'agreement_type' => 'electricity',
    ]));

    return back()->with('success', 'Agreement created successfully.');
}
   public function gasStore(Request $request)
{
    $validated = $request->validate([
        'product_name' => 'required|string',
        'provider_name' => 'required|string',
        'light_category' => 'required|string',
        'fixed_rate' => 'required|numeric',
        'customer_type' => 'required|string',
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
        'agreement_type' => 'gas',
    ]));

    return back()->with('success', 'Agreement created successfully.');
}
   public function bothStore(Request $request)
{
    $validated = $request->validate([
        'product_name' => 'required|string',
        'provider_name' => 'required|string',
        'light_category' => 'required|string',
        'fixed_rate' => 'required|numeric',
        'customer_type' => 'required|string',
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
    ]));

    return back()->with('success', 'Agreement created successfully.');
}

}
