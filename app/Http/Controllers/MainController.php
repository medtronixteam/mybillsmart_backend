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
}
