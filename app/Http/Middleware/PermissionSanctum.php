<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PermissionSanctum
{
    public function handle(Request $request, Closure $next, $permission)
    {
        $user = $request->user('sanctum');

        if (!$user) {
            return response()->json([
                'message' => 'No authenticated user.',
                'error' => 'Unauthenticated'
            ], 401);
        }

        try {
            if (!$user->hasPermissionTo($permission, 'sanctum')) {
                return response()->json([
                    'message' => 'This action is unauthorized.',
                    'error' => 'Insufficient permissions',
                    'required_permission' => $permission
                ], 403);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error checking permissions.',
                'error' => 'Permission check failed',
                'details' => $e->getMessage()
            ], 500);
        }

        return $next($request);
    }
}
