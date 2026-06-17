<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Rakibhstu\Banglanumber\NumberToBangla;
use App\Models\Karigor;

// Ensure the helpers are imported correctly
require_once app_path('helpers.php');

class GoldController extends Controller
{
    public function convertGram(Request $request)
    {
        $vori = $request->input('vori', 0);
        $ana = $request->input('ana', 0);
        $roti = $request->input('roti', 0);
        $point = $request->input('point', 0);

        // Prepare the gold array
        $gold = [
            'vori' => $vori,
            'ana' => $ana,
            'roti' => $roti,
            'point' => $point
        ];

        // Call the helper function
        $grams = convertToGram($gold);

        return response()->json(['grams' => $grams]);
    }

    public function convertNumberToBangla(Request $request)
    {
        $numto = new NumberToBangla();
        $value = $request->input('value');
        $banglaWord = $numto->bnWord($value);
        return response()->json($banglaWord);
    }

    public function karigor_stock(Request $request)
    {
        $karigor_stock = Karigor::where('karigor_id', $request->id)->where('category_id', $request->cat_id)->latest()->first();
        return response()->json($karigor_stock);
    }
}