<?php

namespace App\Http\Controllers;

use App\Models\Badge;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function get(Request $request)
    {
        return $request->user();
    }

    public function register(Request $request)
    {
        $assignBadge = Badge::where('achievements_required', 0)->first();
        
        Log::info($assignBadge->id);
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'email' => ['required','string','email','unique:users'],
            'password' => ['required','string','min:8'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        $user->badge_id = $assignBadge->id;
        $user->save();
        
        return response()->json($user, 201);
    }

    public function login(Request $request)
    {
        // Validate the request data
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Attempt to authenticate the user
        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
            $tokenResult = $user->createToken('authToken');

            return response()->json([
                'user' => $user,
                'token' => $tokenResult->plainTextToken,
            ]);
        }
    }
}
