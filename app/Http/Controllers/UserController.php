<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'regex:/^[a-zA-Z0-9_]+$/', Rule::unique('users', 'name')],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'password' => ['required', 'min:8'],
            'confirm_password' => ['required', 'same:password']
        ], [
            'name.required' => 'The username is required.',
            'name.regex' => 'The username can only contain letters, numbers, and underscores.',
            'name.unique' => 'This username is already taken.',
            'email.required' => 'The email is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already registered.',
            'password.required' => 'A password is required.',
            'password.min' => 'The password must be at least 8 characters.',
            'confirm_password.same' => 'The passwords do not match.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $incomingFields = $validator->validated();

        $incomingFields['name'] = strip_tags($incomingFields['name']);
        $incomingFields['password'] = bcrypt($incomingFields['password']);

        $user = User::create($incomingFields);
        Auth::login($user);

        return response()->json([
            'message' => 'Registration successful'
        ], 200);
    }

    public function login(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => ['required'],
            'password' => ['required']
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
        $incomingFields = $validator->validated();
        $credentials = ['email' => $incomingFields['email'], 'password' => $incomingFields['password']];

        if (auth()->attempt($credentials)) {
            return response()->json([
                'message' => 'Login successful'
            ], 200);
        }
    
        return response()->json([
            'message' => 'Login failed',
            'errors' => $validator->errors()
        ], 401);
    }
}
