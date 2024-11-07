<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request){

        $fields = $request->validate([
            "firstname" => 'required|max:255',
            "lastname" => 'required|max:255',
            "username" => 'required|max:255',
            "email" => 'required|email|unique:users',
            "password" => 'required|confirmed'
        ]);

        $user = User::create($fields);
        $user->sendEmailVerificationNotification();

        $token = $user->createToken($request->username);

        return [
            "user" => $user,
            "token" => $token->plainTextToken
        ];
    }

    public function login(Request $request){
        
        $request->validate([
            "email" => 'required|email|exists:users',
            "password" => 'required'
        ]);

        $user = User::where('email' , $request->email)->first();

        if(!$user || !Hash::check($request->password , $user->password)){
            return ['message' => 'The Provide Credentials are incorrect.'];
        }

        $token = $user->createToken($user->username);

        return [
            "user" => $user,
            "token" => $token->plainTextToken
        ];
    }
    public function logout(Request $request){
        $request->user()->tokens()->delete();

        return ['message' => 'Logged Out Successfully.'];
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? response()->json(['message' => __($status)], 200)
            : response()->json(['message' => __($status)], 400);
    }


    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->password = Hash::make($password);
                $user->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? response()->json(['message' => 'Password reset successfully.'], 200)
            : response()->json(['message' => __($status)], 400);
    }



    public function verify(Request $request, $id, $hash)
    {
        
 // findOrfail
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        if (! hash_equals(sha1($user->getEmailForVerification()), $hash)) {
            return response()->json(['message' => 'Invalid hash.'], 403);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email is already verified.'], 200);
        }

        $user->markEmailAsVerified();

        event(new Verified($user));

        return response()->json(['message' => 'Email verified successfully.'], 200);
    }

    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email already verified.'], 400);
        }

        $request->user()->sendEmailVerificationNotification();

        return response()->json(['message' => 'Verification email sent.']);
    }

}
