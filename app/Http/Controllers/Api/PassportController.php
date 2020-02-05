<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\User;
use Auth;

class PassportController extends Controller
{
    public $user;

    /**
     * API Register
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validate = Validator::make($request->all(),[
            'first_name' => 'required',
            'last_name' => 'required',
            'username' => 'required|unique:users',
            'email' => 'required|unique:users',
            'password' => 'required'
        ]);

        if($validate->fails()) return response()->json([
            'status' => 'Ok',
            'result' => ['error' => $validate->errors()]
        ], 401);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        $token = $user->createToken(env('APP_KEY'))->accessToken;

        return response()->json([
            'status' => 'Ok',
            'result' => ['token' => $token]
        ], 200);
    }

    /**
     * API new token request.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required'
        ]);

        if($validate->fails()) return response()->json([
            'status' => 'Ok',
            'result' => ['error' => $validate->errors()]
        ], 401);

        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials))
        {
            $token = auth()->user()->createToken('TutsForWeb')->accessToken;

            return response()->json([
                'status' => 'Ok',
                'result' => ['token' => $token]
            ], 200);
        } else {
            return response()->json(['error' => 'UnAuthorised'], 401);
        }

    }

}
