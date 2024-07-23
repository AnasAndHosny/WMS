<?php

namespace App\Services;

use App\Models\DestructionCause;
use App\Http\Resources\DestructionCauseResource;

class DestructionCauseService
{
    public function index(): array
    {
        $cause = DestructionCauseResource::collection(DestructionCause::all());
        $message = __('messages.index_success', ['class' => __('destruction causes')]);
        $code = 200;
        return ['data' => $cause, 'message' => $message, 'code' => $code];
    }
}
