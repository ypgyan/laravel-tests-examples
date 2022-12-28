<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\SigninRequest;
use App\Http\Requests\Auth\SignupRequest;
use App\Http\Resources\Auth\LoginResource;
use App\Services\Auth\AuthService;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * @param AuthService $authService
     */
    public function __construct(private AuthService $authService)
    {
    }

    /**
     * @param SignupRequest $request
     * @return JsonResponse
     */
    public function signup(SignupRequest $request): JsonResponse
    {
        $user = $this->authService->register($request->validated());
        return response()->json(new LoginResource($user), 201);
    }

    /**
     * @param SigninRequest $request
     * @return JsonResponse
     * @throws AuthenticationException
     */
    public function signin(SigninRequest $request): JsonResponse
    {
        return response()->json($this->authService->login($request->validated()));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function signout(Request $request): JsonResponse
    {
        $this->authService->logout($request->user());
        return response()->json();
    }
}
