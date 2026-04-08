<?php

namespace Isotope\Metronic\Http\Middlewares;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthorizationMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $actionName = $request->route()->getActionName();
        $method     = $request->route()->methods()[0];
        $action     = substr($actionName, strpos($actionName, '@') + 1);
        $namespace  = substr($actionName, 0, strrpos($actionName, '\\'));
        $controller = substr($actionName, strrpos($actionName, '\\') + 1, -(strlen($action) + 1));

        if ($request->user()->isSuperAdmin() || $request->user()->hasActionPermission($namespace, $controller, $method, $action)) {
            return $next($request);
        }

        if(in_array('web', $request->route()->gatherMiddleware())) {
            return response(view('isotope::401'), 401);
        } else {
            return response()->json([
                'msg' => 'authorize'
            ], 401);
        }
    }
}
