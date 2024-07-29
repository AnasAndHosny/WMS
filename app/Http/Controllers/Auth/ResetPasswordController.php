<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Ichtrojan\Otp\Otp;
use App\Http\Responses\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ResetPasswordRequest;

class ResetPasswordController extends Controller
{
    private $otp;

    public function __construct()
    {
        $this->otp = new Otp;
    }

    public function passwordReset(ResetPasswordRequest $request)
    {
        $otp = $this->otp->validate($request->email, $request->otp);

        $data = [
            'email' => $request->email
        ];

        if (!$otp->status) {
            return Response::Error($data, __($otp->message), 400);
        }

        $user = User::where('email', $request->email)->first();
        $user->update(['password' => $request->password]);
        $user->tokens()->delete();

        $message = __('Your password has been reset successfully.');
        $code = 200;
        return Response::Success($data, $message, $code);
    }
}
