<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LocationRequest;
use App\Http\Traits\PaginationTrait;
use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    use PaginationTrait;

    private array $globalFilterFields = ['name, horse_capacity'];

    public function getAllLocations()
    {
        return $this->getAll(Location::class, $this->globalFilterFields);
    }

    public function getPaginatedLocations(Request $request)
    {
        return $this->getPaginated($request, Location::class, [], $request->perPage, $this->globalFilterFields);
    }

    public function storeLocation(LocationRequest $request)
    {
        return $this->store($request, Location::class);
    }

    public function findLocationById(int $id)
    {
        return $this->findById(Location::class, $id);
    }

    public function updateLocation(LocationRequest $request, string $id)
    {
        return $this->update($request, $id, Location::class);
    }

    public function destroyLocation(string $id)
    {
        $this->destroy($id, Location::class);
    }
}
