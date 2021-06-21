<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Checkstatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->user()->status == '200') {
            return response()->json('Your account is logged in');
        }
        return response()->json('Your account is not logged in');
    }
}
