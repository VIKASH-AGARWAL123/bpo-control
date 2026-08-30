<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class ResourceController extends Controller
{
    private array $map = [
        'clients' => [\App\Models\Client::class, ['name', 'code', 'email', 'status']],
        'processes' => [\App\Models\Process::class, ['client_id', 'name', 'code', 'description', 'status']],
        'teams' => [\App\Models\Team::class, ['name', 'description', 'status']],
        'queues' => [\App\Models\Queue::class, ['team_id', 'name', 'status']],
    ];

    public function index(Request $request): JsonResponse
    {
        [$model] = $this->config($request);
        $q = $model::query()->where('organization_id', app('tenant_id'));

        if ($request->filled('search')) {
            $search = $request->string('search');
            $q->where('name', 'ilike', "%{$search}%");
        }

        return response()->json($q->latest()->paginate($request->integer('per_page', 20)));
    }

    public function store(Request $request): JsonResponse
    {
        [$model, $fields] = $this->config($request);
        $data = $request->validate(array_combine($fields, array_map(fn($field) => $field === 'client_id' || $field === 'team_id' ? ['nullable', 'integer'] : ['nullable', 'string', 'max:255'], $fields)));
        $data['organization_id'] = app('tenant_id');
        $item = $model::create($data);

        return response()->json($item, 201);
    }

    public function show(Request $request, int $id): JsonResponse
    {
        [$model] = $this->config($request);
        $item = $model::where('organization_id', app('tenant_id'))->findOrFail($id);
        return response()->json($item);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        [$model, $fields] = $this->config($request);
        $item = $model::where('organization_id', app('tenant_id'))->findOrFail($id);
        $data = $request->validate(array_combine($fields, array_map(fn($field) => $field === 'client_id' || $field === 'team_id' ? ['sometimes', 'nullable', 'integer'] : ['sometimes', 'nullable', 'string', 'max:255'], $fields)));
        $item->update($data);
        return response()->json($item->fresh());
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        [$model] = $this->config($request);
        $item = $model::where('organization_id', app('tenant_id'))->findOrFail($id);
        $item->delete();
        return response()->json(['message' => 'Deleted.']);
    }

    private function config(Request $request): array
    {
        $resource = $request->route('resource');
        abort_unless(isset($this->map[$resource]), 404);
        return $this->map[$resource];
    }
}
