<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\UserSigninRequest;
use App\Http\Requests\Auth\UserSignupRequest;
use App\Http\Responses\Response;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Throwable;

class AuthController extends Controller
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function register(UserSignupRequest $request): JsonResponse
    {
        $data = [];
        try {
            $data = $this->userService->register($request->validated());
            return Response::Success($data['user'], $data['message'], $data['code']);
        }catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);
        }
    }

    public function login(UserSigninRequest $request): JsonResponse
    {
        $data = [];
        try {
            $data = $this->userService->login($request);
            return Response::Success($data['user'], $data['message'], $data['code']);
        }catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);
        }
    }

    public function logout(): JsonResponse
    {
        $data = [];
        try {
            $data = $this->userService->logout();
            return Response::Success($data['user'], $data['message'], $data['code']);
        }catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);
        }
    }
}
