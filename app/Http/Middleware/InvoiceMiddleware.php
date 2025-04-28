<?php

namespace App\Http\Middleware;

use App\Models\Plan;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
class InvoiceMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if(!auth('sanctum')->user()->plan_name){
            return response()->json(['message' => 'You have not purchased any plan.'], 403);
        }
       $starter= Plan::where('name','starter')->first();
       $pro= Plan::where('name','pro')->first();
       $enterprise= Plan::where('name','enterprise')->first();

        if(auth('sanctum')->user()->plan_name == 'free' && auth('sanctum')->user()->invoices()->count() > $starter->invoices){
            return response()->json(['message' => 'You have reached the limit  for the Starter plan.'], 403);
        }
        if(auth('sanctum')->user()->plan_name == 'pro' && auth('sanctum')->user()->invoices()->count() > $pro->invoices){
            return response()->json(['message' => 'You have reached the limit  for the Pro plan.'], 403);
        }
        if(auth('sanctum')->user()->plan_name == 'enterprise' && auth('sanctum')->user()->invoices()->count() > $enterprise->invoices){
            return response()->json(['message' => 'You have reached the limit  for the Enterprise plan.'], 403);
        }

        return $next($request);
    }
}
