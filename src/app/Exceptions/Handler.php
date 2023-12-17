<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->renderable(function (Exception $e, Request $request) {
            if ($e instanceof \Illuminate\Auth\AuthenticationException && $request->path() == 'api/session' && $request->method() == 'PUT') {
                return response()->json([
                    'ok' => false,
                    'err' => 'ERR_INVALID_REFRESH_TOKEN',
                    'msg' => 'invalid refresh token',
                ], Response::HTTP_FORBIDDEN);
            }
        });
    }
}
