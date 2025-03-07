<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Offer;
use Illuminate\Http\Request;
use Validator;

class OfferController extends Controller
{



    public function list(){

        $offers= Offer::latest()->get();
        $response=['status'=>"success",'code'=>200,'data'=>$offers];
        return response($response,$response['code']);
     }
}
