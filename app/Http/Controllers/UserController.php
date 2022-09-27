<?php

namespace App\Http\Controllers;

// use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\LoginUserRequest;
// use App\Http\Requests\StoreUserRequest;
// use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{


    // With StoreUserRequest

    // public function register(StoreUserRequest $request)
    // {
    //     $user = new User;
    //     $user->name = $request->input('name');
    //     $user->email = $request->input('email');
    //     $user->password = Hash::make($request->input('password'));
    //     $user->save();
    //     return $user;
    // }

    public function register(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
        ]);

        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
        ]);

        $token = $user->createToken('usertoken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }

    public function login(LoginUserRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            // return ["errors" => "Email or password not matched", "message" => "Email or password not matched."];

            return response([
                'message' => "Email or password not matched."
            ], 401);
        }

        $token = $user->createToken('usertoken')->plainTextToken;
        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }

    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'Logged out'
        ];
    }
}
