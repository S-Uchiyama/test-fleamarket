<?php

namespace App\Actions\Fortify;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthenticateUser
{
    public function __invoke(Request $request): ?User
    {
        $loginRequest = new LoginRequest();
        $request->validate($loginRequest->rules(), $loginRequest->messages());

        $user = User::where('email', $request->input('email'))->first();

        if (!$user || !Hash::check($request->input('password'), $user->password)) {
            throw ValidationException::withMessages([
                'email' => 'ログイン情報が登録されていません',
            ]);
        }

        return $user;
    }
}
