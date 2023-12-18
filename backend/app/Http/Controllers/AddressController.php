<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function index()
    {
        return Address::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'address_line1' => ['required'],
            'address_line2' => ['required'],
            'city' => ['required'],
            'region_id' => ['required', 'integer'],
            'postal_code' => ['required'],
        ]);

        return Address::create($data);
    }

    public function show(Address $address)
    {
        return $address;
    }

    public function update(Request $request, Address $address)
    {
        $data = $request->validate([
            'address_line1' => ['required'],
            'address_line2' => ['required'],
            'city' => ['required'],
            'region_id' => ['required', 'integer'],
            'postal_code' => ['required'],
        ]);

        $address->update($data);

        return $address;
    }

    public function destroy(Address $address)
    {
        $address->delete();

        return response()->json();
    }
}
