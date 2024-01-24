<?php

namespace App\Http\Traits;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

trait PaginationTrait
{
    public function paginated(Request $request, string $model, array $relations = [], string $perPage = '10')
    {
        $perPage = $request->query('perPage', $perPage);

        $entities = $model::with($relations)->paginate($perPage);
        $totalEntities = $entities->total();

        if ($totalEntities > 0) {
            return response()->json([
                'message' => $entities,
            ]);
        } else {
            return response()->json([
                'message' => 'No entities found'
            ], 404);
        }
    }
}
