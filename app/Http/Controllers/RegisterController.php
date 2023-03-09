<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Repositories\AuthRepository;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    use ResponseTrait;

    public function __construct(private AuthRepository $authRepository)
    {
        $this->authRepository = $authRepository;
    }


    /**
     * @OA\Post(
     *     path="/api/register",
     *     tags={"Authentication"},
     *     summary="Register using data",
     *     description="New User Registration",
     *     operationId="register",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="name",
     *                     description="User name",
     *                     type="string",
     *                     example="Abiruzzaman Molla",
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     description="User email address",
     *                     type="string",
     *                     example="example@example.com",
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     description="user password",
     *                     type="string",
     *                     example="password",
     *                 ),
     *                 @OA\Property(
     *                     property="password_confirmation",
     *                     description="user password confirmation",
     *                     type="string",
     *                     example="password",
     *                 ),
     *                required={"name","email", "password", "password_confirmation"}
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
    public function register(RegisterRequest $request)
    {
        try {
            $data = $this->authRepository->register($request->all());
            return $this->responseSuccess(
                $data,
                'User Created successfully.'
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
