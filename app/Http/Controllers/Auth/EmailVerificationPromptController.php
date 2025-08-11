<?php

namespace App\Http\Controllers\Auth;

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
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('home', absolute: false));
        }
        
        // Send verification email if not sent recently
        if (!session('verification_email_sent')) {
            $request->user()->sendEmailVerificationNotification();
            session(['verification_email_sent' => true]);
        }
        
        return view('auth.verify-email');
    }
}
