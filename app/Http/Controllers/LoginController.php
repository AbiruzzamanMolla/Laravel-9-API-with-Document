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

    public function login(LoginRequest $request)
    {
        try {
            $data = $this->authRepository->login($request->all());
            return $this->responseSuccess(
                $data,
                'Product fetched successfully.'
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
