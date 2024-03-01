<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CountryRequest;
use App\Models\Country;
use App\Models\Region;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Traits\PaginationTrait;
use Spatie\Permission\Models\Role;

class CountryController extends Controller
{
    use PaginationTrait;

    private array $globalFilterFields = ['name'];

    public function getCountriesColumnNames() {
        $model = new Country;
        return $this->sendResponse($this->getColumnNames($model));
    }

    public function getAllCountries()
    {
        return $this->getAll(Country::class, $this->globalFilterFields);
    }

    public function getPaginatedCountries(Request $request)
    {
        return $this->getPaginated($request,
                          Country::class,
                                   [],
                                   $request->perPage,
                                   $this->globalFilterFields);
    }

    public function storeCountry(CountryRequest $request)
    {
        return $this->store($request, Country::class);
    }

    public function findCountryById(string $id)
    {
        return $this->findById(Country::class, $id);
    }

    public function updateCountry(CountryRequest $request, string $id)
    {
        return $this->update($request, $id, Country::class);
    }

    public function destroyCountry(string $id)
    {
        return $this->destroy($id, Country::class);
    }

    private function getResponseWithMessage(string $message, int $status)
    {
        return response()->json(['status' => $status, 'message' => $message], $status);
    }
}
