<?php

namespace App\Services\Auth;

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
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    /**
     * @param array $data
     * @return array
     * @throws AuthenticationException
     */
    public function login(array $data): array
    {
        $user = User::where('email', $data['email'])->first();
        if (Hash::check($data['password'], $user->password)) {
            $user->tokens()->delete();
            return [
                'id' => $user->id,
                'token' => $user->createToken(time())->plainTextToken
            ];
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
