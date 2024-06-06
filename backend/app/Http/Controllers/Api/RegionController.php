<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegionRequest;
use App\Http\Traits\PaginationTrait;
use App\Models\Region;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    use PaginationTrait;

    private array $globalFilterFields = ['name', 'country.name'];
    private array $relationships = ['country'];

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
        return $this->findById(Region::class, $id, $this->relationships);
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
