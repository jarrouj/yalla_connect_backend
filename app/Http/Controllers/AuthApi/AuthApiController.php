<?php

namespace App\Http\Controllers\AuthApi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;


class AuthApiController extends Controller
{

    //Register function
    public function register(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|digits:8|unique:users,phone',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        $verificationCode = rand(1000, 9999);

        $user = User::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'verification_code' => $verificationCode,
        ]);

        // Send email (customize this with your own view/template)
        Mail::raw("Your verification code is: $verificationCode", function ($message) use ($user) {
            $message->to($user->email)
                ->subject('Email Verification Code');
        });
        $token = $user->createToken('auth_token')->plainTextToken;


        return response()->json([
            'message' => 'Registered successfully.',
            'token' => $token,
            'user' => [
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'phone' => '+961' . $user->phone,
                'email' => $user->email,
                'email_verified' => false,
                'phone_verified' => true,
            ]
        ]);
    }

    //Verify email function
    public function verifyEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'code' => 'required|digits:4',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user->verification_code !== $request->code) {
            return response()->json(['message' => 'Invalid verification code'], 400);
        }

        $user->email_verified_at = now();
        $user->verification_code = null;
        $user->save();

        return response()->json(['message' => 'Email verified successfully']);
    }

    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string', // phone or email
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->login)
            ->orWhere('phone', $request->login)
            ->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'The provided credentials are incorrect.'
            ], 401);
        }

        if (is_null($user->email_verified_at)) {
            return response()->json([
                'message' => 'Email not verified.'
            ], 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful.',
            'token' => $token,
            'user' => [
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'phone' => '+961' . $user->phone,
            ]
        ]);
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'login' => 'required|string'
        ]);

        $user = User::where('email', $request->login)
            ->orWhere('phone', $request->login)
            ->first();

        if (! $user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        $resetCode = rand(1000, 9999);

        $user->reset_code = $resetCode;
        $user->save();

        // Send via email
        if ($user->email) {
            Mail::raw("Your password reset code is: $resetCode", function ($message) use ($user) {
                $message->to($user->email)
                    ->subject('Password Reset Code');
            });
        }

        // (Optional) Send via SMS if using Twilio or similar

        return response()->json([
            'message' => 'Reset code sent successfully.'
        ]);
    }
}
