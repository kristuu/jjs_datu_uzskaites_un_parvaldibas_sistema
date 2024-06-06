<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ImageController extends Controller
{
    public function uploadToImgur(Request $request)
    {
        $request->validate([
            'image' => 'required|string', // String, because encoded as base64
        ]);

        $imageData = $request->input('image');

//        $response = Http::withHeaders([
//            'Authorization' => 'Client-ID ' . config('services.imgur.client_id'),
//        ])->asForm()->post('https://api.imgur.com/3/image', [
//            'image' => $imageData,
//        ]);

        // Temporary fix for local development without SSL
        $response = Http::withHeaders([
            'Authorization' => 'Client-ID ' . config('services.imgur.client_id'),
        ])->withOptions([
            'verify' => false,
        ])->post('https://api.imgur.com/3/image', [
            'image' => $imageData,
        ]);

        return response()->json($response->json());
    }
}
