<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // Signup function
    /**
     * Handle user signup.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function signup(Request $request)
    {
        // Validate the incoming request data
        $validateUser = Validator::make(
            $request->all(),
            [
                'name' => 'required', // Name is required
                'email' => 'required|email|unique:users,email', // Email is required, must be a valid email, and unique in the users table
                'password' => 'required', // Password is required
            ]
        );

        // Check if validation fails
        if ($validateUser->fails()) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Validation Error', // Return validation error
                    'errors' => $validateUser->errors()->all() // Include validation errors in the response
                ], 401
            );
        }

        // Create a new user in the database
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password), // Hash the password before storing it
        ]);

        // Return success response with the created user
        return response()->json(
            [
                'status' => true,
                'message' => 'User Created Successfully',
                'user' => $user,
            ], 200
        );
    }

    // Login function
    /**
     * Handle user login.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        // Validate the incoming request data
        $validateUser = Validator::make(
            $request->all(),
            [
                'email' => 'required|email', // Email is required and must be a valid email
                'password' => 'required', // Password is required
            ]
        );

        // Check if validation fails
        if ($validateUser->fails()) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Authentication Failed', // Return authentication failure message
                    'errors' => $validateUser->errors()->all() // Include validation errors in the response
                ], 404
            );
        }

        // Attempt to log the user in with the provided email and password
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $authUser = Auth::user(); // Get the authenticated user
            return response()->json(
                [
                    'status' => true,
                    'message' => 'User Logged in Successfully',
                    'token' => $authUser->createToken('API Token')->plainTextToken, // Generate and return an API token
                    'token_type' => 'bearer'
                ], 200
            );
        } else {
            // Return an error response if the email and password do not match
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Email and Password do not match.', // Error message
                ], 401
            );
        }
    }

    // Logout function
    /**
     * Handle user logout.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $user = $request->user(); // Get the authenticated user
        $user->tokens()->delete(); // Revoke all tokens for the user

        // Return success response
        return response()->json(
            [
                'status' => true,
                'user' => $user,
                'message' => 'You Logged out Successfully',
            ], 200
        );
    }
}
