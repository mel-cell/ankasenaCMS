<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function show()
    {
        if (auth()->check()) {
            return redirect(auth()->user()->isAdmin() ? '/ankasena/panel' : '/vote');
        }

        return view('pages.login');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (! Auth::attempt($data, $request->boolean('ingat'))) {
            throw ValidationException::withMessages(['email' => 'Email atau kata sandi salah.']);
        }

        $request->session()->regenerate();

        $user = auth()->user();

        return redirect($user->isAdmin() ? '/ankasena/panel' : '/vote');
    }
}
