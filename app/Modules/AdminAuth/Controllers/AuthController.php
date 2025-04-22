<?php

namespace App\Modules\AdminAuth\Controllers;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Modules\AdminAuth\Models\Admin;
use App\Modules\AdminAuth\Requests\LoginRequest;
use App\Modules\AdminAuth\Resources\AdminResource;

class AuthController extends Controller
{

    public function login(LoginRequest $request): JsonResponse
    {
        $user = Admin::where('email', $request->email)->first();

        if (!$user) {
            return $this->sendError(__('api.Incorrect Email Or Password'));
        }

        if ($user->is_active  == 0) {
            return $this->sendError(__('api.Your account is not active'));
        }

        if (Hash::check($request->password, $user->password)) {
            if (env('APP_ENV') !== 'local') {
                $user->tokens()->delete();
            }
            $token = $user->createToken('user', ['user'], now()->addMinutes(config('sanctum.expiration')))->plainTextToken;
            $user['token'] = $token;
            return $this->sendResponse(AdminResource::make($user), __('api.login successfully'));
        }

        return $this->sendError(__('api.Incorrect Email or password'));
    }

}
