<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    //
    public function index()
    {
        return view('auth.login');
    }

    public function register()
    {
        return view('auth.register');
    }

    public function dashboard()
    {
        if(Auth::check()){
            
            return view('home');
        }
        return redirect('login')->withErrors(['login_gagal' => 'You dont have access!']);
    }


    public function proses_login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $validator = Validator::make($credentials, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        if (Auth::attempt($credentials)) {
            return redirect()->intended('dashboard')->with('success', 'Successfully login');
        }

        return redirect('login')->withErrors(['login_gagal' => 'Username or password are wrong!']);
    }

    public function proses_register(Request $request){

        $credentials = $request->all();
        $validator = Validator::make($credentials, [
            'fullname'=>'required',
            'email'=>'required|email',
            'password'=>'required'
        ]);

    
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $request['level'] = 'admin';
        User::create($request->all());

        return redirect()->intended('dashboard')->with('success', 'Successfully register');

     }

     public function logout(){
        Session::flush();
        Auth::logout();

        return redirect('login')->with('success','Successfully Logout');
      }
}
