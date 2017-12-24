<?php

namespace App\Http\Middleware;

use Closure;

class ValidUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (! $request->user()->confirmed) {
            return redirect(route('all.threads.index'))
                ->with('flash', 'You must confirm your email before creating threads');
        }

        return $next($request);
    }
}
