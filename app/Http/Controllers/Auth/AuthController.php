<?php

namespace App\Http\Controllers\Auth;

use Throwable;
use App\Services\UserService;
use App\Http\Responses\Response;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UserSigninRequest;
use App\Http\Requests\Auth\UserSignupRequest;
use App\Http\Requests\Auth\UpdateUserProfileRequest;

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
        } catch (Throwable $th) {
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
        } catch (Throwable $th) {
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
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);
        }
    }

    public function showProfile(): JsonResponse
    {
        $data = [];
        try {
            $data = $this->userService->showProfile();
            return Response::Success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);
        }
    }

    public function updateProfile(UpdateUserProfileRequest $request): JsonResponse
    {
        $data = [];
        try {
            $data = $this->userService->updateProfile($request);
            return Response::Success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);
        }
    }

    public function backAdmin(): JsonResponse
    {
        $data = [];
        try {
            $data = $this->userService->backAdmin();
            return Response::Success($data['data'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);
        }
    }
}
