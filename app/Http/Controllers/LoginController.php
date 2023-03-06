<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\ResponseTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    use ResponseTrait;

    public function login(Request $request)
    {
        // validate the request parameters
        // $request->validate([
        //     'email' => 'required|email',
        //     'password' => 'required|min:6',
        // ]);
        // check whether the user exists or not
        $user = User::where('email', $request->email)->first();
        // return error if user is not found
        if (!$user) {
            return $this->responseError(
                null,
                'Something went wrong.',
                'User Not Found.'
            );
        }
        // check user password match or not
        if (!Hash::check($request->password, $user->password)) {
            return $this->responseError(
                null,
                'Something went wrong.',
                'Password does not match.'
            );
        }

        $createToken = $user->createToken('authToken');

        $data = [
            'user' => new UserResource($user),
            'access_token' => $createToken->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse($createToken->token->expires_at)->toDateTimeString(),
        ];

        // return success message
        return $this->responseSuccess(
            $data,
            'Logged in successfully.'
        );
    }
}
