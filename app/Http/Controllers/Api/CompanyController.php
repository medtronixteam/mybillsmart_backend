<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\CompanyDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class CompanyController extends Controller
{
    public function companyDetails()
   {
       $companyDetails = CompanyDetail::where('user_id', auth('sanctum')->user()->id)->first();
       return response()->json([
           'message' => 'Company Details fetched successfully',
           'data' => $companyDetails,
           'status' => 'success',
       ], 200);
   }
   public function specificCompanyInfo($id)
   {
       $companyDetails = CompanyDetail::where('user_id', $id)->first();
       return response()->json([
           'message' => 'Company Details fetched successfully',
           'data' => $companyDetails,
           'status' => 'success',
       ], 200);
   }

   public function updateCompanyDetails(Request $request)
   {
    $validator = Validator::make($request->all(), [
        'company_name' => 'required|string|max:255',

    ]);
    if ($validator->fails()) {
        return response()->json(['message' => $validator->messages()->first(),'status'=>"error"], 500);
    }
        $company_logo = $request->file('company_logo') ? $request->file('company_logo')->store('company_logos', 'public') : null;

        CompanyDetail::updateOrCreate(
            ['user_id' => auth('sanctum')->user()->id],
            [
                'company_name' => $request->company_name,
                'company_address' => $request->company_address,
                'company_email' => $request->company_email,
                'company_city' => $request->company_city,
                'company_state' => $request->company_state,
                'company_zip' => $request->company_zip,
                'company_country' => $request->company_country,
                'company_logo' => $company_logo,
                'company_phone' => $request->company_phone,

            ]
        );
        return response()->json([
            'message' => 'Company Details Saved successfully',
            'status' => 'success',
        ], 200);
   }
}
