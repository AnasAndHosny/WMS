<?php

namespace App\Services;

use Carbon\Carbon;
use Ichtrojan\Otp\Otp as OtpPack;
use Ichtrojan\Otp\Models\Otp as Model;

class Otp extends OtpPack
{
    /**
     * @param string $identifier
     * @param string $token
     * @return mixed
     */
    public function check(string $identifier, string $token): object
    {
        $otp = Model::where('identifier', $identifier)->where('token', $token)->first();

        if ($otp instanceof Model) {
            if ($otp->valid) {
                $now = Carbon::now();
                $validity = $otp->created_at->addMinutes($otp->validity);

                if (strtotime($validity) < strtotime($now)) {
                    return (object)[
                        'status' => false,
                        'message' => 'OTP Expired'
                    ];
                }

                if ($otp->created_at == $otp->updated_at) {
                    $createdAt = Carbon::parse($otp->created_at);
                    $now = Carbon::now();

                    $validity = round($createdAt->diffInMinutes($now->addMinutes(30)));

                    $otp->update(['validity' => $validity ?? $otp->validity]);
                }

                return (object)[
                    'status' => true,
                    'message' => 'OTP is valid'
                ];
            }

            return (object)[
                'status' => false,
                'message' => 'OTP is not valid'
            ];
        } else {
            return (object)[
                'status' => false,
                'message' => 'OTP does not exist'
            ];
        }
    }
}
