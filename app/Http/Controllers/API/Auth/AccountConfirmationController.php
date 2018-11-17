<?php

namespace App\Http\Controllers\API\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Notifications\EmailConfirmationRequest;
use App\Helpers\ResponseHelper;
use Carbon\Carbon;
use Validator;
use App\User;

class AccountConfirmationController extends Controller
{
    /**
     * Confirm account of user with provided uuid.
     *
     * @param  string  $uuid
     * @return \Illuminate\Http\Response
     */
    public function confirmAccount($uuid) {
        // validates request data, returns errors if fails
        $validator = Validator::make(['confirmation_code' => $uuid], [
            'confirmation_code' => 'required|string|exists:users,confirmation_code'
        ]);
        
        if($validator->fails()) {
            return ResponseHelper::validationErrorResponse($validator->errors());
        }

        $user = User::where('confirmation_code', $uuid)->get()->first();

        if($user->confirmed_at != null) {
            return response()->json(['message' => 'Account already confirmed.'], 400);
        }

        $user->confirmed_at = Carbon::now();
        $user->save();

        return response()->json(null, 204);
    }

    /**
     * Resend the confirmation email to acoount with the provided email.
     *
     * @param  string  $email
     * @return \Illuminate\Http\Response
     */
    public function resendConfirmationEmail(string $email) {
        // validates request data, returns errors if fails
        $validator = Validator::make(['email' => $email], [
            'email' => 'required|string|email|exists:users,email'
        ]);
        
        if($validator->fails()) {
            return ResponseHelper::validationErrorResponse($validator->errors());
        }

        $user = User::where('email', $email)->get()->first();
        
        if($user->confirmed_at != null) {
            return response()->json(['message' => 'Account already confirmed.'], 400);
        }

        $user->notify(new EmailConfirmationRequest($user->confirmation_code));

        return response()->json(null, 204);
    }
}
