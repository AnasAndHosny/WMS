<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Responses\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgetPasswordRequest;
use App\Notifications\ResetPasswordVerificationNotification;

class ForgetPasswordController extends Controller
{
    public function forgetPassword(ForgetPasswordRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        $user->notify(new ResetPasswordVerificationNotification());

        $data = [
            'email' => $request->email
        ];
        $message = __('The resetting password code has been sent successfully to your email.');
        $code = 200;
        return Response::Success($data, $message, $code);
    }
}
