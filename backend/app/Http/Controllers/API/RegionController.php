<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Region;
use App\Http\Requests\RegionRequest;
use Illuminate\Http\Request;
use App\Http\Traits\PaginationTrait;

class RegionController extends Controller
{
    use PaginationTrait;

    private array $globalFilterFields = ['name', 'country.name'];

    public function getRegionsColumnNames() {
        $model = new Region;
        return $this->sendResponse($this->getColumnNames($model));
    }

    public function getAllRegions()
    {
        $relationships = ['country'];
        $hiddenFields = ['country' => ['id']];


        return $this->getAll(Region::class, $this->globalFilterFields, $relationships, $hiddenFields);
    }

    public function getPaginatedRegions(Request $request)
    {
        return $this->getPaginated($request,
                          Region::class,
                                   [],
                                   $request->perPage,
                                   $this->globalFilterFields);
    }

    public function storeRegion(RegionRequest $request)
    {
        return $this->store($request, Region::class);
    }

    public function findRegionById(string $id)
    {
        return $this->findById(Region::class, $id);
    }

    public function updateRegion(RegionRequest $request, string $id)
    {
        return $this->update($request, $id, Region::class);
    }

    public function destroyRegion(string $id)
    {
        return $this->destroy($id, Region::class);
    }

    private function getResponseWithMessage(string $message, int $status)
    {
        return response()->json(['status' => $status, 'message' => $message], $status);
    }
}
