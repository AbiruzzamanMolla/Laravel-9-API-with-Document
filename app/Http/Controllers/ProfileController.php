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
     * @OA\Get(
     *     path="/api/profile",
     *     tags={"Authentication"},
     *     summary="Get user profile information",
     *     description="Get logged in user profile information",
     *     operationId="show",
     *     security={{ "bearer":{} }},
     *     @OA\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
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
     * @OA\Post(
     *     path="/api/logout",
     *     tags={"Authentication"},
     *     summary="User logout",
     *     description="Logout",
     *     operationId="logout",
     *     security={{ "bearer":{} }},
     *     @OA\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
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
