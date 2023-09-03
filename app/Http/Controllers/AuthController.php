<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login()
    {
        return view('frontend.auth.login');
    }
    public function authenticate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        if ($validator->passes()) {
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->get('remember'))) {
                if (session()->has('url.intended')) {
                    return redirect(session()->get('url.intended'));
                }
            } else {
                return redirect()->route('login')->withInput($request->only('email'))->with('error', 'Invalid email or password');
            }
        } else {
            return redirect()->route('login')->withErrors($validator)->withInput($request->only('email'));
        }

        return redirect()->route('costumer.index');
    }
    public function register()
    {
        return view('frontend.auth.register');
    }
    public function registerProses(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
        ]);

        if ($validator->passes()) {
            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->role = '2';
            $user->save();

            session()->flash('success', 'Register Berhasil');
            return response([
                'status' => true,
                'message' => 'Register Berhasil',
            ]);
        } else {
            return response([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }
}