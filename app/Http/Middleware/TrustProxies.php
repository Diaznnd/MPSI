<?php

// app/Http/Middleware/TrustProxies.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class TrustProxies
{
    protected $proxies;
    protected $headers = Request::HEADER_X_FORWARDED_FOR;  // Use a different header constant if needed

    public function handle(Request $request, Closure $next)
    {
        return $next($request);
    }
}