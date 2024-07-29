<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Ichtrojan\Otp\Otp;
use Illuminate\Http\Request;
use App\Http\Responses\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Notifications\EmailVerificationNotifiction;
use App\Http\Requests\Auth\EmailVerificationRequest;

class EmailVerificationController extends Controller
{
    private $otp;

    public function __construct()
    {
        $this->otp = new Otp;
    }

    public function sendEmailVerification(Request $request)
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            $message = __('Your email is already verified.');
            $code = 400;
            return Response::Success($user, $message, 400);
        }


        $user->notify(new EmailVerificationNotifiction());
        $message = __('Email verification code sent successfully.');
        $code = 200;
        return Response::Success($user, $message, $code);
    }

    public function emailVerification(EmailVerificationRequest $request): JsonResponse
    {
        $user = $request->user();
        $otp = $this->otp->validate($user->email, $request->otp);

        if (!$otp->status) {
            return Response::Error($user, __($otp->message), 400);
        }

        $user->markEmailAsVerified();

        $message = __('Your email verified successfully.');
        $code = 200;
        return Response::Success($user, $message, $code);
    }
}
