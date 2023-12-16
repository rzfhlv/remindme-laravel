<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Reminder;
use Illuminate\Http\Response as HttpResponse;

class EnsureUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $userId = $request->user()->id;
        $id = $request->id;
        
        $reminder = Reminder::where('created_by', $userId)->where('id', $id)->first();
        if (is_null($reminder)) {
            return response()->json([
                'ok' => false,
                'err' => 'ERR_FORBIDDEN_ACCESS',
                'msg' => 'access disallow', 
            ], HttpResponse::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
