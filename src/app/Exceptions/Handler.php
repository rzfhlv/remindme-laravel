<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Exception;
use Illuminate\Http\Request;

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
        // $this->reportable(function (Throwable $e) {
        //     //
        // });
        $this->renderable(function (Exception $e, Request $request) {
            if ($e instanceof \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException || $e instanceof \Illuminate\Auth\AuthenticationException) {
                return response()->json([
                    'ok' => false,
                    'err' => 'ERR_INVALID_CRED',
                    'message' => 'unauthorized',
                ], 401);
            }
        });
    }
}
