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
