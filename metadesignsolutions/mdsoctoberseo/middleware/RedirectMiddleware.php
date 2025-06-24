<?php namespace Metadesignsolutions\Mdsoctoberseo\Middleware;

use Closure;
use Metadesignsolutions\Mdsoctoberseo\Models\Redirect;

class RedirectMiddleware
{
    public function handle($request, Closure $next)
    {
        $currentUrl = '/' . trim($request->path(), '/');

        $redirect = Redirect::where('from_url', $currentUrl)->first();

        if ($redirect && $redirect->is_active) {
            return redirect($redirect->to_url, $redirect->status_code);
        }

        return $next($request);
    }
}