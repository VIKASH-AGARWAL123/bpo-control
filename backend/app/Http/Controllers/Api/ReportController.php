<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\JsonResponse;

class ReportController extends Controller
{
    public function summary(): JsonResponse
    {
        $tenant = app('tenant_id');
        $tasks = Task::where('organization_id', $tenant);
        $total = (clone $tasks)->count();
        $completed = (clone $tasks)->where('status', 'completed')->count();
        $breached = (clone $tasks)->where('sla_status', 'breached')->count();

        return response()->json([
            'total_tasks' => $total,
            'completed_tasks' => $completed,
            'completion_rate' => $total ? round(($completed / $total) * 100, 2) : 0,
            'sla_breach_rate' => $total ? round(($breached / $total) * 100, 2) : 0,
            'by_priority' => (clone $tasks)->selectRaw('priority, count(*) as count')->groupBy('priority')->pluck('count', 'priority'),
            'by_status' => (clone $tasks)->selectRaw('status, count(*) as count')->groupBy('status')->pluck('count', 'status'),
        ]);
    }
}
