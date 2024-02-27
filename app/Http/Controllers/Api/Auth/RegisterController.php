<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function register(RegisterRequest $request)
    {
        // Validate request
        $validated = $request->validated();

        // Create user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'phone' => $request->phone,
        ]);

        // Assign role
        $user->assignRole('Member');

        // Create token
        $token = $user->createToken('appToken')->plainTextToken;

        return response()->json([
            'status' => true,
            'data' => [
                'user' => $user->with('roles'),
                'token' => $token,
                'token_type' => 'Bearer'
            ]
        ]);
    }
}
