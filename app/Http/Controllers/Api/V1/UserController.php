<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function index()
    {
        try {
            $users = User::latest()->get();
            return response()->successResponse(UserResource::collection($users), 'User list Retrieved');

        } catch (Exception $exception) {
            Log::info($exception->getMessage());
            return response()->errorResponse();
        }
    }
}
