<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerifyEmailController extends Controller
{
    /**
     * Mark the user's email address as verified.
     */
    public function __invoke(Request $request, $id, $hash): RedirectResponse
    {
        // Find the user
        $user = User::findOrFail($id);
        
        // Check if the hash matches
        if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            return redirect(route('login'))->withErrors(['email' => 'Link verifikasi tidak valid.']);
        }
        
        // Check if already verified
        if ($user->hasVerifiedEmail()) {
            // If user is logged in, redirect to home
            if (Auth::check()) {
                return redirect(route('home'))->with('status', 'Email Anda sudah terverifikasi sebelumnya.');
            }
            // If not logged in, redirect to login
            return redirect(route('login'))->with('status', 'Email sudah terverifikasi. Silakan login.');
        }
        
        // Mark as verified
        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }
        
        // If user is logged in, redirect to home
        if (Auth::check() && Auth::id() == $user->id) {
            return redirect(route('home'))->with('verified', '1');
        }
        
        // If not logged in, redirect to login with success message
        return redirect(route('login'))->with('status', 'Email berhasil diverifikasi! Silakan login untuk melanjutkan.');
    }
}
