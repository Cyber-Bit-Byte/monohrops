<?php

namespace App\Http\Middleware\Api;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class ForceJsonResponse
{
    public function handle(Request $request, Closure $next): JsonResponse|Response
    {
        if (App::environment(['local', 'staging', 'production'])) {
            $request->headers->set('Accept', 'application/json');
        }

        return $next($request)->header('Content-Type', 'application/json');
    }
}
