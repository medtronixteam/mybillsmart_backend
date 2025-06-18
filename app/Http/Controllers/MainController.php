<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Contract;
use App\Models\ReferralPoints;
use App\Models\Invoice;
use App\Models\Offer;
use App\Models\Plan;
use App\Models\Product;
use App\Models\Subscription;
use Carbon\Carbon;
class MainController extends Controller
{
    public function login(){
        return view('login');
    }
    function auth(Request $request)
    {
        $valid = $request->validate([
            'email' => 'email|required',
            'password' => 'required',
        ]);
        if (Auth::attempt($valid)) {
            // flashy()->success('Login successfully...');
            return redirect()->route('dashboard');
        }
        return back()->with('error', 'Invalid email or Password');
    }
    public function chartData()
    {
        // Get your data (example: monthly sales)
        $monthlyData = DB::table('subscriptions')
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as count')
            )
            ->whereYear('created_at', date('Y')) // Current year
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('count', 'month')
            ->toArray();

        // Fill in missing months with 0
        $completeData = [];
        for ($i = 1; $i <= 12; $i++) {
            $completeData[$i] = $monthlyData[$i] ?? 0;
        }

        // Prepare the response
        $data = [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            'series' => [array_values($completeData)] // Just first 6 months if needed
        ];

        return $data;
    }
    public function dashboard(){
        $totalUsers = User::count();
        $totalagent = User::where('role','agent')->count();
        $totalgroup = User::where('role','group_admin')->count();
      $yearlyChartData=  $this->chartData();
        $salesNumbers=[
            [
                'Starter' => Subscription::where('plan_name','starter')->sum('amount'),
                'Pro' => Subscription::where('plan_name','pro')->sum('amount'),
                'Enterprise' => Subscription::where('plan_name','enterprise')->sum('amount')
            ],

            [
                'Starter' => Subscription::where('plan_name','starter')->count(),
                'Pro' => Subscription::where('plan_name','pro')->count(),
                'Enterprise' => Subscription::where('plan_name','enterprise')->count()
            ],
        ];
        // $topGroups = DB::table('invoices')
        //     ->select('group_id', DB::raw('COUNT(*) as invoice_count'))
        //     ->groupBy('group_id')
        //     ->orderByDesc('invoice_count')
        //     ->limit(10)
        //     ->get();
            $topGroups = Invoice::select('group_id')
            ->selectRaw('COUNT(*) as invoice_count')
            ->groupBy('group_id')
            ->orderByDesc('invoice_count')
            ->limit(10)
            ->get();
        $user = Auth::user();
        if ($user->role == 'admin') {
            return view('admin.dashboard', compact('totalUsers','totalagent','totalgroup','topGroups','salesNumbers','yearlyChartData'));
        } else {
            return redirect()->route('login')->with('error', 'You do not have access to the dashboard.');
        }
    }
    public function userList(){
        $users = User::where('role','admin')->latest()->get();
        return view('admin.user_list', compact('users'));
    }
    public function groupAdmin(){
        $users = User::where('role','group_admin')->latest()->get();
        return view('admin.group_admin', compact('users'));
    }
    public function allUsers(){
        $users = User::whereNot('role','group_admin')->whereNot('role','admin')->latest()->get();
        return view('admin.users', compact('users'));
    }

   public function disable($userId)
{
    $user = User::findOrFail($userId);

   $user->status = 0;
    $user->save();

    if ($user->role == 'group_admin') {
        User::where('group_id', $user->id)->update([
            'status' => 0,
        ]);
    }
    return redirect()->back();
}

   public function enable($userId)
{
    $user = User::findOrFail($userId);

    $user->status = 1;

    $user->save();
    return redirect()->back();
}


   public function delete($deleteId)
{
    $user = User::findOrFail($deleteId);


    if ($user->role === 'group_admin') {
        User::where('group_id', $user->id)->delete();
    }

    $user->delete();

    return redirect()->back();
}

    public function userView($viewId){


        $user = User::findOrFail($viewId);
        if ($user->role == 'group_admin') {  $subscription = Subscription::with('user')->where('user_id', $viewId)->latest()->get();
            $groupUsers= User::where('group_id', $user->id)->limit(20)->latest()->get();
            $groupInvoices= Invoice::where('group_id', $user->id)->limit(20)->latest()->get();
        }else{
            $groupUsers=$subscription= [];
            $groupInvoices= Invoice::where('agent_id', $user->id)->limit(20)->latest()->get();
        }

        return view('admin.user_view', compact('user','groupUsers','groupInvoices','subscription'));
    }
    public function profile(){
        return view('admin.profile');
    }
    public function resetPass(Request $request) {
        $validatedData = $request->validate([
            "new_password" => ['required', 'min:5'],
            "confirm_password" => ['required', 'same:new_password'],
        ]);

        if ($validatedData) {
            $user = User::find(Auth::user()->id);
            if ($user) {
                $user->update(['password' => Hash::make($validatedData["new_password"])]);
                // flashy()->info('Password has been Updated!', '#');
                return back()->with('success', 'Password has been updated successfully!');
            } else {
                // flashy()->error('Invalid User Id', '#');
                return back()->with('error', 'Invalid User Id');
            }
        }

        return back()->with('error', 'Password has not been Updated!');
    }
    public function resetName(Request $request) {
        $validatedData = $request->validate([
            "name" => ['required'],
            "email" => ['required', 'email',],
        ]);
        $user = User::find(Auth::user()->id);
        if ($user) {
            $user->update([
                'name' => $validatedData["name"],
                'email' => $validatedData["email"],
            ]);

            return back()->with('success', 'Name and Email have been updated successfully!');
        } else {
            return back()->with('error', 'Invalid User Id');
        }
    }
    public function usersdata() {
        return view('admin.user_store');
    }
    public function storeUsers(Request $request) {
        $validatedData = $request->validate([
            "name" => 'required',
            "email" => 'required|email|unique:users,email',
            "password" => 'required',
            "phone" => 'required',
            // "address" => 'required',
            "country" => 'required',
            "city" => 'required',
            "postal_code" => 'required',
            "status" => 'required',
            "role" => 'required',
            "dob" => 'nullable',
            "euro_per_points" => $request->role === 'group_admin' ? 'required' : 'nullable',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'address' => $request->address,
            'country' => $request->country,
            'city' => $request->city,
            'postal_code' => $request->postal_code,
            'role' => $request->role,
            'status' => $request->status,
            'euro_per_points' => $request->euro_per_points,
            'dob' => $request->dob,
            'plan_name' => 'free_trial',
        ]);
        if($request->role == 'group_admin'){
            ReferralPoints::updateOrCreate(
                ['group_id' => $user->id],
                [
                    'level_1_points' => 10,
                    'level_2_points' => 5,
                    'level_3_points' => 3,
                ]
            );
             Subscription::create([
                    'user_id' => $user->id,
                    'amount' => 0,
                    'payment_intent_id' => 1,
                    'start_date' => Carbon::now(),
                    'end_date' =>Carbon::now()->copy()->addDays(7),
                    'status' => 'active',
                    'type' => "plan",
                    'plan_name' => "free_trial",
                    'plan_duration' => "monthly",
                ]);
        }

         return response()->json(['success' => 'User has been created successfully']);
    }


    public function reset($passId)
    {
        return view('admin.resetPassword', compact('passId'));
    }

    public function changePass(Request $request) {
        $validatedData = $request->validate([
            "new_password" => ['required', 'min:5'],
            "confirm_password" => ['required', 'same:new_password'],
        ]);

        if ($validatedData) {
            $user = User::find($request->resetId);
            if ($user) {
                $user->update(['password' => Hash::make($validatedData["new_password"])]);
                return back()->with('success', 'Password has been updated successfully!');
            } else {
                return back()->with('error', 'Invalid User Id');
            }
        }

        return back()->with('error', 'Password has not been Updated!');
    }



    public function contractsList(){
        $contracts = Contract::with('offer')->latest()->paginate(10);
        return view('admin.contracts_list', compact('contracts'));
    }
    public function invoiceList(){
        $invoiceList = Invoice::with('user')->latest()->paginate(10);

        return view('admin.invoice_list', compact('invoiceList'));
    }
    public function showOffer($id)
{
    $offer = Offer::with('contract')->find($id);
    return view('admin.offers_view', compact('offer'));
}

public function showDetail($id)
{
    $invoice = Invoice::find($id);
    return view('admin.view_detail', compact('invoice'));
}
  }
