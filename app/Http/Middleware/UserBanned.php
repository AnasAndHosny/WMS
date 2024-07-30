<?php

namespace App\Http\Middleware;

use App\Http\Responses\Response as JsonResponse;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserBanned
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $user = $request->user();
        if ($user->isBanned()) {
            $user->tokens()->delete();
            $data = [
                'error' => 'Banned'
            ];
            return JsonResponse::Error($data, __('messages.banned'), 403);
        }
        
        return $next($request);
    }
}
