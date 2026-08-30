<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetTenant
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth('api')->user();

        if (!$user?->organization_id) {
            return response()->json(['message' => 'No organization is associated with this account.'], 403);
        }

        app()->instance('tenant_id', (int) $user->organization_id);

        return $next($request);
    }
}
