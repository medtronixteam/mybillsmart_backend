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

}
