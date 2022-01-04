<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserDetail;
use Carbon\Carbon;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(User $user){
        $this->user = $user;
    }

    public function verify_email(Request $request){
        $code = $request->input('code');
        $user_id = $request->input('user_id');
        $user = $this->user->find($user_id);
        if(isset($user->verification->code) && $code == $user->verification->code){
            $user->email_verified_at = Carbon::now();
            $user->save();
            return response()->json([
                'response' => 'verify successfully',
            ]);
        }
        else{
            return response()->json([
                'response' => 'Wrong Code, verification failed',
            ]);
        }
    }

    public function update_profile(Request $request){
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
    
        $avatar = time().'.'.$request->avatar->extension();

        $request->avatar->move(public_path('images'), $avatar);

        $user_details = [
            'avatar' => $avatar,
            'username' => $request->input('username')
        ];
        $user_id = $request->input('user_id');

        if($user_id){
            $user = $this->user->find($user_id);
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->is_admin = $request->input('is_admin');
            $user->save();
            $user->user_details()->save(new UserDetail($user_details));
        } 
    
        return response()->json([
            "response" => "profile updated successfully"
        ]); 
    }
}
