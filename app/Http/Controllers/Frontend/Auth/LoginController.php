<?php

namespace App\Http\Controllers\Frontend\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        $previousUrl = url()->previous();
        $loginUrl = route('user.login');
        $registerUrl = route('user.register');
        $submitUrl = route('user.login.submit');

        $prevHost = parse_url($previousUrl, PHP_URL_HOST);

        if (request()->headers->has('referer')
            && $previousUrl
            && $previousUrl !== $loginUrl
            && $previousUrl !== $registerUrl
            && $previousUrl !== $submitUrl
            && ($prevHost === null || $prevHost === request()->getHost())
        ) {
            session()->put('url.intended', $previousUrl);
        }

        return view('frontend.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('web')->attempt($request->only('email', 'password'), $request->filled('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('user.dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
