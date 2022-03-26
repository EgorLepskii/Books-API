<?php

namespace App\Http\Middleware;

use App\Models\Genre;
use App\Models\User;
use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use function PHPUnit\Framework\assertInstanceOf;

class Admin
{
    private User $user;

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse) $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next): \Symfony\Component\HttpFoundation\Response
    {
        $this->user = auth()->user() ?? new User(['isAdmin' => false]);

        if ($this->user->isAdmin()) {
            return $next($request);
        }

        return response('', 403);
    }
}
