<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

class IsCustomer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */

    protected $auth;
    protected $route;

    public function __construct(Guard $auth, Route $route) {
        $this->auth = $auth;
        $this->route = $route;
    }
    public function handle(Request $request, Closure $next)
    {
        if($this->auth->user()->role_id != 4) {
            return new Response('<div style="margin-top: 130px; width: 100%;"><center><img src="https://forum.hestiacp.com/uploads/default/original/1X/8592157f8ed594f456bb3eefe8660e1e06ec51fc.png"
            alt="login form"/></center></div>', 401);
        }
        return $next($request);
    }
}
