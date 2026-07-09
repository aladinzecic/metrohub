<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showRegister(){
        return view('auth.register');
    }

    public function register(Request $request){
        $request->validate([
            'name'=>'required|string|max:255',
            'email'=>'required|email|unique:users,email',
            'password'=>'required|min:6',
            'role'=>'required|in:driver,mechanic,dispatcher,controller,passenger',
            'phone'    => 'nullable|string',

        ]);

        User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>$request->password,
            'role'=>$request->role,
            'phone'=>$request->phone,
            'status'=>$request->role==='passenger'?'active':"pending"

        ]);
return redirect('/login');
    }

    public function showLogIn(){
        return view('auth.login');
    }

    public function logIn(Request $request){
    $request->validate([
        'email'    => 'required|email',
        'password' => 'required',
    ]);

    if(Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->remember)){
        $user = Auth::user();

        if($user->status === 'pending'){
            Auth::logout();
            return back()->withErrors(['email' => 'Your account is pending approval.']);
        }

        return match($user->role){
            'driver'     => redirect('/driver/dashboard'),
            'dispatcher' => redirect('/dispatcher/fleet'),
            'mechanic'   => redirect('/mechanic/workorders'),
            'controller' => redirect('/controller/tickets'),
            'passenger'  => redirect('/tickets'),
            default      => redirect('/'),
        };
    }

    return back()->withErrors(['email' => 'Invalid email or password.']);
}

        public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
