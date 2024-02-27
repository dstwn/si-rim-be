<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function logout(Request $request)
    {
        $request->user()->token()->delete();
        return response()->json([
            'message' => 'Logged out'
        ]);
    }

    public function profile()
    {
        return response()->json([
            'status' => true,
            'data' => [
                'user' => auth()->user(),
                'roles' => auth()->user()->getRoleNames()
            ]
        ]);
    }
}
