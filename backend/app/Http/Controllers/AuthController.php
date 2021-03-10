<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'email' => 'required|email|unique:users',
            'password' => 'required|min:3',
        ]);
        if ($validate->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $v->errors(),
            ], 422);
        }
        $user = new User;
        $user->name = $request->username ?? $request->email;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();
        return response()->json(['status' => 'success', 'message' => 'Thank you for your registration! Please log in.'], 200);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if ($token = Auth::attempt($credentials)) {
            $user = Auth::user();
            $user->accessToken = $token;
            return response()->json(['user' => $user, 'status' => 'success'], 200)->header('Authorization', $token);
        }
        return response()->json(['error' => 'login_error'], 401);
    }

    public function logout()
    {
        Auth::logout();
        return response()->json([
            'status' => 'success',
            'msg' => 'Logged out Successfully.',
        ], 200);
    }

    public function user(Request $request)
    {
        $user = User::find(Auth::user()->id);
        return response()->json([
            'status' => 'success',
            'data' => $user,
        ]);
    }

    public function refresh()
    {
        if ($token = Auth::refresh()) {
            return response()
                ->json(['status' => 'success'], 200)
                ->header('Authorization', $token);
        }
        return response()->json(['error' => 'refresh_token_error'], 401);
    }
}
