<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;
use App\mail\InvitationMail;

class MailController extends Controller
{
    public function sent_registration_email(Request $request) {
        $validated = $request->validate([
            'email' => 'required|email'
        ]);

        $email = $request->input('email');

        $details = [
            'title' => 'Sign Up Invitation',
            'body' => 'www.domain.com/signup?='.$email.''
        ];

        Mail::to($email)->send(new InvitationMail($details));

        echo "Email Sent.";
     }
}
