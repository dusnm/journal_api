<?php

namespace App\Services;

use App\Models\User;

class EmailVerificationService
{
    /**
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     *
     * Verify the user's account
     */
    public function verify(string $email): bool
    {
        $user = User::query()->where('email', '=', $email)->firstOrFail();
        $user->verified = 1;

        return $user->save();
    }
}
