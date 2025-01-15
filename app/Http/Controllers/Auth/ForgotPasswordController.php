<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */
     /**
     * Show the form to request a password reset link.
     *
     * @return \Illuminate\View\View
     */
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');  // The Blade view for the forgot password form
    }

    /**
     * Handle the form submission for sending the reset link.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function submitForgotPasswordForm(Request $request)
    {
        // Validate the email input
        $request->validate(['email' => 'required|email']);

        // Attempt to send the password reset link to the provided email
        $status = Password::sendResetLink(
            $request->only('email')
        );
                //dd($status);

        // Check if the link was sent successfully and return the appropriate response
        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }

     use SendsPasswordResetEmails;
}
