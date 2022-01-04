<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;
use App\mail\InvitationMail;
use Auth;

class MailController extends Controller
{
    public function sent_registration_email(Request $request) {
        $is_admin = Auth::user()->is_admin;
        if(!$is_admin){
            return response()->json([
                'response' => 'you are not authorized'
            ]);
        }
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
