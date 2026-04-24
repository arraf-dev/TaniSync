<?php

namespace App\Http\Controllers;

use App\Models\Commodity;
use App\Models\DailyPrice;
use App\Models\Market;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PriceController extends Controller
{
    /**
     * Show the form for entering new prices.
     */
    public function create()
    {
        // Get all active markets and commodities to populate the dropdowns/inputs
        $markets = Market::where('is_active', true)->get();
        $commodities = Commodity::where('is_active', true)->with('category')->get();

        return view('prices.create', compact('markets', 'commodities'));
    }

    /**
     * Store the prices in the database.
     */
    public function store(Request $request)
    {
        // 1. Validation
        $validated = $request->validate([
            'id_pasar' => 'required|exists:pasar,id',
            'tanggal'  => 'required|date',
            'prices'   => 'required|array', // This is where the crop prices live
            'prices.*' => 'required|numeric|min:0', // Each price must be a number
        ]);

        // 2. Prepare the JSON data
        // We take the array of ['commodity_id' => 'price'] from the request
        $priceData = $request->input('prices');

        // 3. Create or Update the entry
        // updateOrCreate prevents duplicate entries if someone submits the same market/date twice
        $dailyPrice = DailyPrice::updateOrCreate(
            [
                'id_pasar' => $validated['id_pasar'],
                'tanggal'  => $validated['tanggal'],
            ],
            [
                'data_harga' => $priceData,
                'status'     => 'submitted', // Or 'draft' depending on your flow
                'created_by' => Auth::id() ?? 1, // Defaulting to 1 if no auth is set up yet
            ]
        );

        return redirect()->back()->with('success', 'Prices updated successfully for ' . $dailyPrice->tanggal->format('d M Y'));
    }
}