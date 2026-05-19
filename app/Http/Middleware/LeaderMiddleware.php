<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LeaderMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->check() || ! auth()->user()->isLeader()) {
            return redirect()->route('home')->with('errorMessage', 'Доступ разрешён только ведущему.');
        }

        return $next($request);
    }
}
