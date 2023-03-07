<?php

namespace App\Repositories;

use App\Http\Resources\UserResource;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\PersonalAccessTokenResult;

class AuthRepository
{

    /**
     * @param array $data
     *
     * @return array
     */
    public function login(array $data): array
    {
        $user = $this->getUserByEmail($data['email']);

        if (!$user) {
            throw new Exception('Sorry, user does not exist', 404);
        }
        // check user password match or not
        if (!$this->isValidPassword($user, $data)) {
            throw new Exception('Sorry, password does not matched.', 401);
        }

        $createToken = $this->createAuthToken($user);

        return $this->getAuthData($user, $createToken);
    }

    /**
     * @param string $email
     *
     * @return User|null
     */
    public function getUserByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    /**
     * @param User $user
     * @param array $data
     *
     * @return bool
     */
    public function isValidPassword(User $user, array $data): bool
    {
        return Hash::check($data['password'], $user->password);
    }

    /**
     * @param User $user
     *
     * @return PersonalAccessTokenResult
     */
    public function createAuthToken(User $user): PersonalAccessTokenResult
    {
        return $user->createToken('authToken');
    }

    /**
     * @param User $user
     * @param PersonalAccessTokenResult $createToken
     *
     * @return array
     */
    public function getAuthData(User $user, PersonalAccessTokenResult $createToken): array
    {
        return [
            'user' => new UserResource($user),
            'access_token' => $createToken->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse($createToken->token->expires_at)->toDateTimeString(),
        ];
    }
}
