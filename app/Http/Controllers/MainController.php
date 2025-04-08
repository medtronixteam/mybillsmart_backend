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
    public function dashboard(){
        $totalUsers = User::count();
        $totalagent = User::where('role','agent')->count();
        $totalgroup = User::where('role','group_admin')->count();
        $user = Auth::user();
        if ($user->role == 'admin') {
            return view('admin.dashboard', compact('totalUsers','totalagent','totalgroup'));
        } else {
            return redirect()->route('login')->with('error', 'You do not have access to the dashboard.');
        }
    }
    public function userList(){
        $users = User::all();
        return view('admin.user_list', compact('users'));
    }

    public function disable($userId)
    {
    $user = User::findOrFail($userId);
    $user->status = 0;
    $user->save();
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
    $user->delete();
    return redirect()->back();
    }
    public function userView($viewId){
        $user = User::findOrFail($viewId);
        return view('admin.user_view', compact('user'));
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
        ]);
        if($request->role == 'group_admin'){
            ReferralPoints::updateOrCreate(
                ['group_id' => $user->id],
                [
                    'level_1_points' => $request->level_1_points,
                    'level_2_points' => $request->level_2_points,
                    'level_3_points' => $request->level_3_points,
                ]
            );
        }

        return back()->with('success', 'User has been created successfully!');
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
        $contracts = Contract::with('offer')->latest()->get();
        return view('admin.contracts_list', compact('contracts'));
    }
    public function invoiceList(){
        $invoiceList = Invoice::with('user')->latest()->get();
        return view('admin.invoice_list', compact('invoiceList'));
    }
    public function showOffer($id)
{
    $offer = Offer::with('contract')->findOrFail($id);
    return view('admin.offers_view', compact('offer'));
}
  }
