<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Automation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AutomationController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Automation::where('organization_id', app('tenant_id'))->latest()->paginate(20));
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'trigger' => ['required', 'string', 'max:100'],
            'conditions' => ['nullable', 'array'],
            'actions' => ['required', 'array'],
            'enabled' => ['boolean'],
        ]);
        $data['organization_id'] = app('tenant_id');
        $data['created_by'] = auth('api')->id();
        $automation = Automation::create($data);
        return response()->json($automation, 201);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json(Automation::where('organization_id', app('tenant_id'))->findOrFail($id));
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $automation = Automation::where('organization_id', app('tenant_id'))->findOrFail($id);
        $data = $request->validate([
            'name' => ['sometimes', 'string', 'max:150'],
            'trigger' => ['sometimes', 'string', 'max:100'],
            'conditions' => ['sometimes', 'array'],
            'actions' => ['sometimes', 'array'],
            'enabled' => ['sometimes', 'boolean'],
        ]);
        $automation->update($data);
        return response()->json($automation->fresh());
    }

    public function destroy(int $id): JsonResponse
    {
        $automation = Automation::where('organization_id', app('tenant_id'))->findOrFail($id);
        $automation->delete();
        return response()->json(['message' => 'Automation deleted.']);
    }
}
