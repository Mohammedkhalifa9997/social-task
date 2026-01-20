<?php

namespace App\Http\Controllers\Front\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     */
    public function __invoke(Request $request): RedirectResponse|View
    {
        if (!$request->user()) {
            return redirect()->route('login')
                ->with('Error', 'Please login to verify your email');
        }

        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('home', absolute: false))
                ->with('Success', 'Email verified successfully');
        }

        return view('auth.verify-email');
    }
}
