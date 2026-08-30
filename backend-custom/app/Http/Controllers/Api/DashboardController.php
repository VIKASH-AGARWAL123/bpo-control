<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function index(): JsonResponse
    {
        $tenant = app('tenant_id');
        $base = Task::where('organization_id', $tenant);

        $stats = [
            'total_tasks' => (clone $base)->count(),
            'completed' => (clone $base)->where('status', 'completed')->count(),
            'in_progress' => (clone $base)->where('status', 'in_progress')->count(),
            'pending' => (clone $base)->where('status', 'pending')->count(),
            'overdue' => (clone $base)->whereNotNull('due_at')->where('due_at', '<', now())->where('status', '!=', 'completed')->count(),
            'sla_at_risk' => (clone $base)->where('sla_status', 'at_risk')->count(),
            'sla_breached' => (clone $base)->where('sla_status', 'breached')->count(),
            'unassigned' => (clone $base)->whereNull('assignee_id')->count(),
            'average_tat_minutes' => round((float) ((clone $base)->whereNotNull('completed_at')->selectRaw("AVG(EXTRACT(EPOCH FROM (completed_at - created_at)) / 60) as avg_minutes")->value('avg_minutes') ?? 0), 1),
        ];

        return response()->json([
            'stats' => $stats,
            'recent_tasks' => Task::where('organization_id', $tenant)->latest()->limit(8)->get(),
            'status_breakdown' => Task::where('organization_id', $tenant)->selectRaw('status, count(*) as count')->groupBy('status')->pluck('count', 'status'),
            'sla_breakdown' => Task::where('organization_id', $tenant)->selectRaw('sla_status, count(*) as count')->groupBy('sla_status')->pluck('count', 'sla_status'),
        ]);
    }
}
