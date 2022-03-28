<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class Authenticate extends Middleware
{

    /**
     * @param Request $request
     * @param Closure $next
     * @return Application|ResponseFactory|Response|mixed
     */
    public function handle($request, Closure $next, ...$guards)
    {
        if (auth()->user()) {
            return $next($request);
        }

        return response('Unauthorized', 403);
    }


}
