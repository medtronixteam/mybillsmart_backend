<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
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
        $user = Auth::user();
        if ($user->role == 'admin') {
            return view('admin.dashboard', compact('totalUsers'));
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
}
