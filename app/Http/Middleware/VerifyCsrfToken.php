<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * Indicates whether the XSRF-TOKEN cookie should be set on the response.
     *
     * @var bool
     */
    protected $addHttpCookie = true;

    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
    ];

    //add an array of Routes to skip CSRF check
    private $openRoutes = ['login-apple-callback','stripe-webhooks','apple-webhooks'];

    //modify this function
    public function handle($request, Closure $next)
    {
            //add this condition 
        foreach($this->openRoutes as $route) {

            if ($request->is($route)) {
                return $next($request);
            }
        }
        
        return parent::handle($request, $next);
    }

}
