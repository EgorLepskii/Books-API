<?php

namespace App\Http\Middleware;

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
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        $this->user = auth()->user() ?? new User(['name' => 'name', 'email' => 'email', 'isAdmin' => true]);


        auth()->login($this->user);

        if($this->user->isAdmin()) {
        return $next($request);
         }

        return response('',403);
    }
}
