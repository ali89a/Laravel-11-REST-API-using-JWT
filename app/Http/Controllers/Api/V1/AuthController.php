<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Validator;

class AuthController extends Controller
{
    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(UserRequest $request)
    {
        try {
            $data = $request->only(['name', 'email']);
            $data['password'] = bcrypt($request->password);
            $user = User::create($data);
            return response()->successResponse(new UserResource($user), 'Registration successful', 201);
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            return response()->errorResponse();
        }
    }


    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        try {
            if (!$token = auth()->attempt($request->validated())) {
                return response()->errorResponse('Invalid email or password', 401);
            }
            $data = $this->respondWithToken($token);
            return response()->successResponse($data, 'Login successful');
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            return response()->errorResponse();
        }

//        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        try {

            $data = auth()->user();
            return response()->successResponse(new UserResource($data), 'Profile retrieved successful');
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            return response()->errorResponse();
        }
        return response()->json();
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        try {
            auth()->logout();
            return response()->successResponse([], 'Logout successful', 200);
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            return response()->errorResponse();
        }
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        try {
            $data = $this->respondWithToken(auth()->refresh());
            return response()->successResponse($data, 'Token Refresh Successful');
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            return response()->errorResponse();
        }
    }

    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token): array
    {
        $data = [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ];
        return $data;
    }

}
