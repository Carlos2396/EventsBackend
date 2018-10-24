<?php

namespace App\Http\Controllers\API\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Helpers\ResponseHelper;
use App\User;
use Carbon\Carbon;
use Validator;

class AuthController extends Controller
{
    public function login(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);
        
        if($validator->fails()) {
            return ResponseHelper::validationErrorResponse($validator->errors());
        }

        if(Auth::attempt($request->only('email', 'password'))) { 
            $user = Auth::user();
            $token =  $user->createToken('MyApp');
            $tokenStr = $token->accessToken; 
            $expiration = $token->token->expires_at->diffInSeconds(Carbon::now());

            return response()->json([
                'user' => $user,
                'token' => $tokenStr,
                'expiration_time' => $expiration
            ], 200); 
        } 
        else{ 
            return response()->json(['message'=>'Bad credentials'], 401); 
        } 
    }

    public function logout() {
        $user = Auth::user();
        $token = $user->token();
        $token->revoke();

        return response(['success' => true], 200);
    }

    public function loggedUser(Request $request) {
        $user = Auth::user();
        return response()->json($user, 200);
    }

    public function check(Request $request) {
        return response()->json([
            'authenticated' =>  Auth::check()
        ], 200);
    }
}
