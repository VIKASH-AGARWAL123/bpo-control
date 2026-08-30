<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class WorkloadController extends Controller
{
    public function index(): JsonResponse
    {
        $tenant = app('tenant_id');
        $users = User::where('organization_id', $tenant)->where('is_active', true)->get(['id', 'name', 'email', 'role']);

        $workload = $users->map(function ($user) use ($tenant) {
            $active = Task::where('organization_id', $tenant)->where('assignee_id', $user->id)->whereIn('status', ['pending', 'in_progress'])->count();
            $completed = Task::where('organization_id', $tenant)->where('assignee_id', $user->id)->where('status', 'completed')->count();
            return [
                'user' => $user,
                'active_tasks' => $active,
                'completed_tasks' => $completed,
                'capacity' => min(100, $active * 5),
                'sla_risk' => Task::where('organization_id', $tenant)->where('assignee_id', $user->id)->where('sla_status', 'at_risk')->count(),
            ];
        });

        return response()->json(['workload' => $workload]);
    }
}
