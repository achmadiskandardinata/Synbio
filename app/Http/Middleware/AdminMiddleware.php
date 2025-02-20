<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(!Auth::guard("admin")->check()) {
            toastr()
            ->positionClass('toast-top-center')
            ->error('Silahkan harus login terlebih dahulu!');
            return redirect()->route('admin.login');
        }
        return $next($request);
    }
}
