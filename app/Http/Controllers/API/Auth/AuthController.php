<?php

namespace App\Http\Controllers\API\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Helpers\ResponseHelper;
use Carbon\Carbon;
use Validator;
use App\User;

class AuthController extends Controller
{

    /**
     * Log in the user if credentials are valid.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request) {
        // validates request data, returns errors if fails
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);
        
        if($validator->fails()) {
            return ResponseHelper::validationErrorResponse($validator->errors());
        }

        // set session if credentials are valid
        if(Auth::attempt($request->only('email', 'password'))) { 
            $user = Auth::user();

            // fail if user account is not confirmed
            if($user->confirmed_at == null) {
                return response()->json(['message'=>'Account not confirmed.'], 401);
            }

            // Creates access token and send in response
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

    /**
     * Log out user of session, revokes access token.
     *
     * @return \Illuminate\Http\Response
     */
    public function logout() {
        $user = Auth::user();
        $token = $user->token();
        $token->revoke();

        return response(['success' => true], 200);
    }

    /**
     * Get the logged user.
     *
     * @return \Illuminate\Http\Response
     */
    public function loggedUser(Request $request) {
        $user = Auth::user();

        return response()->json($user, 200);
    }

    /**
     * Checks if user is authenticated.
     *
     * @return \Illuminate\Http\Response
     */
    public function check(Request $request) {
        return response()->json([
            'authenticated' =>  Auth::check()
        ], 200);
    }
}
