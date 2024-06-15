<?php

/*
 * Ar adresēm saistītu darbību pārvaldība
 * Funkcijas ietver:
 * - visu adrešu iegūšana: izmanto Address modeli, lai iegūtu visas adreses no datubāzes
 * - lietotāja adrešu iegūšana: pārbauda lietotāja autentifikāciju un iegūst lietotāja adresi, ja tāda pastāv
 * - adrešu uzstādīšana: validē lietotāja ievadītos datus un izveido jaunu adresi, ja tāda nepastāv
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddressRequest;
use App\Models\Address;
use App\Models\City;
use App\Models\Country;

class AddressController extends Controller
{
    public function index()
    {
        return Address::all();
    }

    public function getAddress()
    {
        $user = auth()->user();

        if (!$user->address_id) {
            return response()->json(['message' => 'No address found for this user.'], 204);
        }

        $address = Address::with(['city.country'])->find($user->address_id);

        if (!$address) {
            return response()->json(['message' => 'Address not found.'], 404);
        }

        return response()->json($address, 200);
    }

    public function setAddress(AddressRequest $request)
    {
        $data = $request->validated();

        $country = Country::firstOrCreate(['name' => $data['country']]);

        $city = City::firstOrCreate(['name' => $data['city'], 'country_id' => $country->id]);

        $addressData = [
            'address_line1' => $data['address_line1'],
            'address_line2' => $data['address_line2'],
            'region' => $data['region'],
            'city_id' => $city->id,
            'postal_code' => $data['postal_code'],
        ];

        $address = Address::firstOrCreate($addressData);

        $user = auth()->user();
        $user->address_id = $address->id;
        $user->save();

        return response()->json($address, 201);
    }

    public function show(Address $address)
    {
        return $address;
    }
}
