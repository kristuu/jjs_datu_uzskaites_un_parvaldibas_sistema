<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegionRequest;
use App\Http\Traits\PaginationTrait;
use App\Models\City;
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


        return $this->getAll(City::class, $this->globalFilterFields, $relationships, $hiddenFields);
    }

    public function getPaginatedRegions(Request $request)
    {
        return $this->getPaginated($request,
            City::class,
            [],
            $request->perPage,
            $this->globalFilterFields);
    }

    public function storeRegion(RegionRequest $request)
    {
        return $this->store($request, City::class);
    }

    public function findRegionById(string $id)
    {
        return $this->findById(City::class, $id, $this->relationships);
    }

    public function updateRegion(RegionRequest $request, string $id)
    {
        return $this->update($request, $id, City::class);
    }

    public function destroyRegion(string $id)
    {
        return $this->destroy($id, City::class);
    }

    private function getResponseWithMessage(string $message, int $status)
    {
        return response()->json(['status' => $status, 'message' => $message], $status);
    }
}
