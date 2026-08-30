<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\JsonResponse;

class SlaController extends Controller
{
    public function index(): JsonResponse
    {
        $tenant = app('tenant_id');
        $tasks = Task::where('organization_id', $tenant)->whereIn('sla_status', ['at_risk', 'breached'])->latest('due_at')->limit(100)->get();

        $counts = Task::where('organization_id', $tenant)
            ->selectRaw('sla_status, count(*) as count')
            ->groupBy('sla_status')->pluck('count', 'sla_status');

        return response()->json([
            'counts' => $counts,
            'tasks' => $tasks,
        ]);
    }
}
