<?php

namespace App\Modules\Auth\Controllers;

use App\Modules\Auth\Models\User;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use App\Modules\Auth\Requests\LoginRequest;
use App\Modules\Auth\Requests\UpdateRequest;
use App\Modules\Auth\Resources\UserResource;
use App\Modules\Auth\Requests\RegisterRequest;
use App\Modules\Auth\Requests\ResetPasswordRequest;

class AuthController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
        ]);
        if (!$user) {
            return $this->sendError(__('api.failed to register'));
        }

        $token = $user->createToken('user', ['user'], now()->addMinutes(config('sanctum.expiration')))->plainTextToken;
        $user['token'] = $token;

        return $this->sendResponse(UserResource::make($user), __('api.register successfully, please verify your phone number'));
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::where('email', $request->email)->first();

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
            return $this->sendResponse(UserResource::make($user), __('api.login successfully'));
        }

        return $this->sendError(__('api.Incorrect Email or password'));
    }

    public function logout(): JsonResponse
    {
        if (Auth::check()) {
            Auth::user()?->tokens()->delete();
            return $this->sendResponse([], __('api.Logged out successfully'));
        }
        return $this->sendError(__('api.User not found'));
    }

    public function profile(): JsonResponse
    {
        $user = Auth::user();
        if (!$user) {
            return $this->sendError(__('api.User not found'));
        }
        return $this->sendResponse(UserResource::make($user), __('api.User data'));
    }

    public function updatePassword(ResetPasswordRequest $request): JsonResponse
    {
        $user = Auth::guard('sanctum')?->user();
        if (!$user) {
            return $this->sendError(__('api.User not found'));
        }
        if (!Hash::check($request->old_password, $user->password)) {
            return $this->sendError(__('api.Old password is incorrect'));
        }
        $user->password = Hash::make($request->new_password);
        $user->save();
        return $this->sendResponse([], __('api.Password updated successfully'));
    }

    public function updateProfile(UpdateRequest $request)
    {
        $user = Auth::guard('sanctum')?->user();
        if (!$user) {
            return $this->sendError(__('api.User not found'));
        }
        $user->update([
            'first_name'  => $request->first_name,
            'last_name'   => $request->last_name,
            'email'       => $request->email,
        ]);
        return $this->sendResponse(UserResource::make($user), __('api.Profile updated successfully'));
    }

    // public function moveImage($image_name, $objectId)
    // {
    //     $temp_path = file_exists(public_path(DIRECTORY_SEPARATOR . 'temp' . DIRECTORY_SEPARATOR . $image_name));
    //     $storedImagePath = file_exists(public_path($image_name));
    //     if ($storedImagePath && !$temp_path) {
    //         return $image_name;
    //     }
    //     if (!$temp_path) {
    //         return null;
    //     }
    //     $imagePath = public_path('temp/' . $image_name);
    //     $uploadsDir = public_path('uploads/profile/' . $objectId);
    //     if (!File::exists($uploadsDir)) {
    //         File::makeDirectory($uploadsDir, 0755, true);
    //     }
    //     $newImagePath = $uploadsDir . '/' . $image_name;
    //     File::move($imagePath, $newImagePath);
    //     $storedImagePath = 'uploads/profile/' . $objectId . '/' . $image_name;
    //     return $storedImagePath;
    // }

}
