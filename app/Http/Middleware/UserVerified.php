<?php

namespace App\Http\Middleware;

use App\Http\Responses\Response as JsonResponse;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (!$user->hasVerifiedEmail()) {
            $data = [
                'error' => 'Not Verified'
            ];
            return JsonResponse::Error($data, __('messages.notVerified'), 403);
        }
        
        return $next($request);
    }
}
