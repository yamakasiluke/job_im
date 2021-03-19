<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WebsocketAuth extends Authenticate
{

    public function handle($request, Closure $next, ...$guards) {
        $request->headers->set('Authorization', 'Bearer ' . $request->get('token'));
        var_dump($request->get('token'));
        $this->authenticate($request, ['sanctum']);
        return $next($request);
    }
}
