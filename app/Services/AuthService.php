<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function login(array $data)
    {
        $user = User::where('email', $data['email'])->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            return null;
        }

        $user->load('roles.permissions');
        $token = $user->createToken('API Token')->plainTextToken;

        return [
            'data' => [
                'user'  => $user,
                'token' => $token,
            ]
        ];
    }
}
