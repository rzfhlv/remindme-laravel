<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\AuthResource;
use App\Http\Resources\AuthRefreshResource;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:users',
                'password' => 'required|string|min:8',
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'ok' => false,
                    'err' => self::ERR_INVALID_REQUEST,
                    'msg' => [$validator->errors()],
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
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
        } catch (\Throwable $th) {
            return response()->json([
                'ok' => false,
                'err' => self::ERR_INTERNAL_SERVER,
                'msg' => self::MSG_INTERNAL_SERVER,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        
    }

    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required',
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'ok' => false,
                    'err' => self::ERR_INVALID_REQUEST,
                    'msg' => [$validator->errors()],
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }
    
            $user = User::where('email', $request->email)->first();
    
            if (! $user || ! Hash::check($request->password, $user->password)) {
                return response()->json([
                    'ok' => false,
                    'err' => self::ERR_INVALID_CRED,
                    'msg' => self::MSG_INVALID_CRED,
                ], Response::HTTP_UNAUTHORIZED);
            }
    
            $now = Carbon::now();
            $token = $user->createToken('access_token', ['access-api'], $now->addSeconds(config('sanctum.access_exp')))->plainTextToken;
            $refresh = $user->createToken('refresh_token', ['issue-access-token'], $now->addDays(config('sanctum.refresh_exp')))->plainTextToken;
            $result = collect(["user" => $user, "token" => $token, "refresh" => $refresh]);
    
            return new AuthResource($result);
        } catch (\Throwable $th) {
            return response()->json([
                'ok' => false,
                'err' => self::ERR_INTERNAL_SERVER,
                'msg' => self::MSG_INTERNAL_SERVER,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        
    }

    public function refresh(Request $request)
    {
        try {
            $token = $request->user()->createToken('access_token', ['access-api'], Carbon::now()->addSeconds(config('sanctum.access_exp')))->plainTextToken;
            $result = collect(["token" => $token]);

            return new AuthRefreshResource($result);
        } catch (\Throwable $th) {
            return response()->json([
                'ok' => false,
                'err' => self::ERR_INTERNAL_SERVER,
                'msg' => self::MSG_INTERNAL_SERVER,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->tokens()->delete();
            
            return response()->json([
                'ok' => true,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'ok' => false,
                'err' => self::ERR_INTERNAL_SERVER,
                'msg' => self::MSG_INTERNAL_SERVER,
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        
    }
}
