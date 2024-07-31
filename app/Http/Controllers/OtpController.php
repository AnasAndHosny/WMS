<?php

namespace App\Http\Controllers;

use App\Services\Otp;
use App\Http\Responses\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Otp\CheckOtpRequest;

class OtpController extends Controller
{
    private $otp;

    public function __construct()
    {
        $this->otp = new Otp;
    }

    public function check(CheckOtpRequest $request):JsonResponse
    {
        $otp = $this->otp->check($request->email, $request->otp);

        $data = [
            'email' => $request->email
        ];

        $code = $otp->status ? 200 : 400;
        $message = __($otp->message);
        return Response::Success($data, $message, $code);
    }
}
