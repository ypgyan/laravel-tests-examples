<?php

namespace App\Services\Auth;

use App\Http\Resources\Auth\LoginResource;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    /**
     * @param array $data
     * @return User
     */
    public function register(array $data): User
    {
        return User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    /**
     * @param array $data
     * @return LoginResource
     * @throws AuthenticationException
     */
    public function login(array $data): LoginResource
    {
        $user = User::where('email', $data['email'])->first();
        if (Hash::check($data['password'], $user->password)) {
            $user->tokens()->delete();
            return new LoginResource($user);
        }
        throw new AuthenticationException('Authentication failed');
    }

    /**
     * @param User $user
     * @return void
     */
    public function logout(User $user): void
    {
        $user->tokens()->delete();
    }
}
