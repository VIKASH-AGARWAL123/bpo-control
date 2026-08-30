<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TaskController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $q = Task::query()->where('organization_id', app('tenant_id'));

        foreach (['status', 'priority', 'sla_status', 'client_id', 'process_id', 'team_id', 'queue_id', 'assignee_id'] as $field) {
            if ($request->filled($field)) $q->where($field, $request->input($field));
        }

        if ($request->filled('search')) {
            $search = $request->string('search');
            $q->where(function ($query) use ($search) {
                $query->where('title', 'ilike', "%{$search}%")
                    ->orWhere('task_number', 'ilike', "%{$search}%")
                    ->orWhere('external_reference_id', 'ilike', "%{$search}%");
            });
        }

        return response()->json($q->latest()->paginate($request->integer('per_page', 20)));
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'client_id' => ['nullable', 'integer'],
            'process_id' => ['nullable', 'integer'],
            'team_id' => ['nullable', 'integer'],
            'queue_id' => ['nullable', 'integer'],
            'assignee_id' => ['nullable', 'integer'],
            'priority' => ['nullable', 'in:low,medium,high,urgent'],
            'status' => ['nullable', 'in:pending,in_progress,completed,blocked,cancelled'],
            'due_at' => ['nullable', 'date'],
            'external_reference_id' => ['nullable', 'string', 'max:255'],
            'custom_fields' => ['nullable', 'array'],
        ]);

        $data['organization_id'] = app('tenant_id');
        $data['task_number'] = 'TK-'.str()->upper(str()->random(8));
        $data['priority'] ??= 'medium';
        $data['status'] ??= 'pending';
        $data['sla_status'] = $data['status'] === 'completed' ? 'met' : 'on_track';

        $task = Task::create($data);

        return response()->json($task, 201);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json(Task::where('organization_id', app('tenant_id'))->findOrFail($id));
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $task = Task::where('organization_id', app('tenant_id'))->findOrFail($id);
        $data = $request->validate([
            'title' => ['sometimes', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string'],
            'client_id' => ['sometimes', 'nullable', 'integer'],
            'process_id' => ['sometimes', 'nullable', 'integer'],
            'team_id' => ['sometimes', 'nullable', 'integer'],
            'queue_id' => ['sometimes', 'nullable', 'integer'],
            'assignee_id' => ['sometimes', 'nullable', 'integer'],
            'priority' => ['sometimes', 'in:low,medium,high,urgent'],
            'status' => ['sometimes', 'in:pending,in_progress,completed,blocked,cancelled'],
            'due_at' => ['sometimes', 'nullable', 'date'],
            'external_reference_id' => ['sometimes', 'nullable', 'string', 'max:255'],
            'custom_fields' => ['sometimes', 'nullable', 'array'],
        ]);

        if (($data['status'] ?? null) === 'completed') {
            $data['completed_at'] = now();
            $data['sla_status'] = 'met';
        }

        $task->update($data);
        return response()->json($task->fresh());
    }

    public function destroy(int $id): JsonResponse
    {
        $task = Task::where('organization_id', app('tenant_id'))->findOrFail($id);
        $task->delete();
        return response()->json(['message' => 'Task deleted.']);
    }
}
