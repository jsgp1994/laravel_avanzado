<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\User;

class AuthController extends Controller
{
    public function generateToken(Request $request)
    {
        $state = 500;
        $token = '';

        $validator = Validator::make($request->all(), $this->rules());

        if ($validator->fails()) {
            //pass validator errors as errors object for ajax response
            return response()->json(['errors'=>$validator->errors()]);
        }

        $user = User::where('email', $request->email)->first();
        if($user && Hash::check($request->password, $user->password)) {
            $token = $user->createToken($request->device_name)->plainTextToken;
        }

        return response()->json([
            'state' => $state,
            'token' => $token
        ]);
    }

    public function rules(){
        return [
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
        ];
    }
}
