<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use PDF;
use App\Models\Offer;

class PdfController extends Controller
{
    public function generatePdf()
    {

        $invoice_id=1;
        //return view('templates.offers');
        // Step 3: Load Blade view with data
       $offers=Offer::where('invoice_id',$invoice_id)->get();
        $pdf = PDF::loadView('templates.offers', ['offers' => $offers]);

        // Step 4: Save PDF to storage
        $path = storage_path('app/public/offers/');
        $fileName = 'invoice.pdf';
        $pdf->save($path . $fileName);

        // Optional: Return downloadable PDF
        return $pdf->download($fileName);
    }
}
