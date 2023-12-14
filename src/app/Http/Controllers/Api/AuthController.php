<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\AuthResource;
use App\Http\Resources\AuthRefreshResource;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'ok' => false,
                'err' => 'ERR_INVALID_REQ',
                'msg' => [$validator->errors()],
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $now = Carbon::now();
        $token = $user->createToken('access_token', ['access-api'], $now->addSeconds(config('sanctum.access_exp')))->plainTextToken;
        $refresh = $user->createToken('refresh_token', ['issue-access-token'], $now->addDays(config('sanctum.refresh_exp')))->plainTextToken;
        $result = collect(["user" => $user, "token" => $token, "refresh" => $refresh]);

        return new AuthResource($result);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'ok' => false,
                'err' => 'ERR_INVALID_REQ',
                'msg' => [$validator->errors()],
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'ok' => false,
                'err' => 'ERR_INVALID_CRED',
                'message' => 'incorrect username or password',
            ], 401);
        }

        $now = Carbon::now();
        $token = $user->createToken('access_token', ['access-api'], $now->addSeconds(config('sanctum.access_exp')))->plainTextToken;
        $refresh = $user->createToken('refresh_token', ['issue-access-token'], $now->addDays(config('sanctum.refresh_exp')))->plainTextToken;
        $result = collect(["user" => $user, "token" => $token, "refresh" => $refresh]);

        return new AuthResource($result);
    }

    public function refresh(Request $request)
    {
        $token = $request->user()->createToken('access_token', ['access-api'], Carbon::now()->addSeconds(config('sanctum.access_exp')))->plainTextToken;
        $result = collect(["token" => $token]);
        return new AuthRefreshResource($result);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json([
            'ok' => true,
            'msg' => 'logout success',
        ]);
    }
}
