<?php

namespace Slowlyo\OwlMenuSearch\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Slowlyo\OwlAdmin\Admin;
use Slowlyo\OwlMenuSearch\OwlMenuSearchServiceProvider;

class OwlMenuSearchMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        Admin::prependNav(app(OwlMenuSearchServiceProvider::class)->searchBtn());

        return $next($request);
    }
}
