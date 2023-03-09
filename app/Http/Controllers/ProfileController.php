<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    use ResponseTrait;

    /**
     * @return JsonResponse
     */
    public function show(): JsonResponse
    {
        try {
            $user = new UserResource(auth()->user());
            return $this->responseSuccess(
                $user,
                'Profile fetched successfully.'
            );
        } catch (Exception $e) {
            return $this->responseError(
                null,
                'Something went wrong.',
                $e->getMessage()
            );
        }
    }

    /**
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        try {
            auth()->user()->token()->revoke();
            auth()->user()->token()->delete();

            return $this->responseSuccess(
                null,
                'User logged out successfully.'
            );
        } catch (Exception $e) {
            return $this->responseError(
                null,
                'Something went wrong.',
                $e->getMessage()
            );
        }
    }
}
