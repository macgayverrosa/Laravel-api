<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Jobs\SendWelcomeEmailJob;

class ApiController extends Controller
{
    // Register API
    public function register(Request $request){

        try {
            $request->validate([
                'name' => 'required|string',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:6|confirmed',
            ]);

            $data = $request->all();
            $data['password'] = bcrypt($data['password']);
            $user = User::create($data);

            // Dispatch welcome email job
            dispatch(new SendWelcomeEmailJob($user));

            return response()->json([
                'status' => 200,
                'success' => true,
                'message' => 'User registered successfully',
                'user' => $user
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Login API
    public function login(Request $request){
        try{
            $request->validate([
                'email' => 'required|email',
                'password' => 'required|min:6',
            ]);

            $credentials = $request->only('email', 'password');
            if (auth()->attempt($credentials)) {
                $user = auth()->user();
                $token = $user->createToken('authToken')->plainTextToken;

                return response()->json([
                    'status' => 200,
                    'success' => true,
                    'message' => 'User logged in successfully',
                    'user' => $user,
                    'access_token' => $token,
                ]);
            }

            return response()->json([
                'status' => 401,
                'success' => false,
                'message' => 'Invalid credentials'
            ], 401);
        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Profile API
    public function profile(Request $request){
        try{
            $user = auth()->user();
            return response()->json([
                'status' => 200,
                'success' => true,
                'message' => 'User profile',
                'user' => $user
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Logout API
    public function logout(){
        auth()->user()->tokens()->delete();
        return response()->json([
            'status' => 200,
            'success' => true,
            'message' => 'User logged out successfully'
        ]);
    }

    // User API
    public function user($id){
        $user = User::find($id);
        return response()->json([
            'status' => 200,
            'success' => true,
            'message' => 'User details',
            'user' => $user
        ]);
    }
}
