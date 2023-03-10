<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Repositories\AuthRepository;
use App\Traits\ResponseTrait;
use Exception;

class LoginController extends Controller
{
    use ResponseTrait;

    public function __construct(private AuthRepository $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    /**
     * @OA\Post(
     *     path="/api/login",
     *     tags={"Authentication"},
     *     summary="Login using authentication credentials",
     *     description="Login using authentication credentials of email and password",
     *     operationId="login",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="email",
     *                     description="User email address",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     description="user password",
     *                     type="string"
     *                 ),
     *                required={"email", "password"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input parameters"
     *     )
     * )
     */

    public function login(LoginRequest $request)
    {
        try {
            $data = $this->authRepository->login($request->all());
            return $this->responseSuccess(
                $data,
                'Logged in successfully.'
            );
        } catch (Exception $e) {
            return $this->responseError(
                null,
                'Something went wrong.',
                $e->getMessage(),
                $e->getCode(),
            );
        }
    }
}
