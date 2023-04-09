<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthenticationController extends Controller
{
    public function index() {
        $user = User::all();

        return response()->json([
            "message" => "successfully fetched user",
            "data" => $user
        ], Response::HTTP_OK);
    }

    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            "name" => "required|string",
            "email" => "required|string|email:rfc,dns|unique:users,email",
            "password" => "required|string|min:8"
        ]);

        if($validator->fails()) {
            return response()->json([
                "message" => "failed creating new user",
                "errors" => $validator->errors()
            ], Response::HTTP_NOT_ACCEPTABLE);
        }

        $validated = $validator->validated();
        $validated["password"] = bcrypt($validated["password"]);

        try {
            $createdUser = User::create($validated);
        } catch (\Exception $e) {
            return response()->json([
                "message" => "Failed creating new user",
                "error" => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        // $user = User::firstWhere('email', $request->email);
        // $token = $user->createToken('sanctum_token')->plainTextToken;

        // return response()->json([
        //     "message" => "successfully create new user and loged in.",
        //     "token" => $token
        // ], Response::HTTP_OK);

        return response()->json([
            "message" => "successfully created new user",
            "data" => $createdUser
        ], Response::HTTP_CREATED);
    }

    public function login(Request $request) {
        $request->validate([
            'email' => "required|string",
            'password' => 'required|string'
        ]);

        $user = User::firstWhere('email', $request->email);

        if(!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                "message" => "Bad credentials!"
            ], Response::HTTP_NOT_FOUND);
        }

        $token = $user->createToken('sanctum_token')->plainTextToken;

        return response()->json([
            "message" => "successfully loged in",
            "token" => $token
        ], Response::HTTP_OK);
    }

    public function getUser() {
        return response()->json([
            "user" => auth()->user()
        ], Response::HTTP_OK);
    }

    public function logout() {
        auth()->user()->tokens()->delete();

        return response()->json([
            "message" => "successfully loged out."
        ], Response::HTTP_OK);
    }
}
