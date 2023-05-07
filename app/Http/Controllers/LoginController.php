<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login()
    {
        if(auth()->user()) return redirect('/dashboard');

        return view('auth.login');
    }

    public function loginCheck(Request $request){
        $this->validate($request,[
            'email'=>'required',
            'password'=>'required'
        ],
            [
                'email.required' => 'The email address field is required.',
                'password.required' => 'The password field is required.'
            ]);

        if(Auth::attempt(['email'=>$request->get('email'),'password'=>$request->get('password')]))
            return redirect('dashboard');

            return redirect('auth.login');

    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}
