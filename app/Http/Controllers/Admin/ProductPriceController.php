<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductPrice;
use Illuminate\Http\Request;

class ProductPriceController extends Controller
{
    /**
     * Display a listing of the product prices.
     */
    public function index()
    {
        $prices = ProductPrice::orderBy('created_at', 'desc')->paginate(15);
        return view('admin.product_price.index', compact('prices'));
    }

    /**
     * Show the form for creating a new product price.
     */
    public function create()
    {
        return view('admin.product_price.create');
    }

    /**
     * Store a newly created product price in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'price'        => 'required|numeric|min:0',
        ]);

        ProductPrice::create($validated);
        return redirect()->route('product-price.index')
            ->with('success', 'প্রোডাক্ট প্রাইস সফলভাবে সংরক্ষণ করা হয়েছে।');
    }

    /**
     * Show the form for editing the specified product price.
     */
    public function edit(ProductPrice $productPrice)
    {
        return view('admin.product_price.edit', ['price' => $productPrice]);
    }

    /**
     * Update the specified product price in storage.
     */
    public function update(Request $request, ProductPrice $productPrice)
    {
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'price'        => 'required|numeric|min:0',
        ]);

        $productPrice->update($validated);
        return redirect()->route('product-price.index')
            ->with('success', 'প্রোডাক্ট প্রাইস সফলভাবে আপডেট করা হয়েছে।');
    }

    /**
     * Remove the specified product price from storage.
     */
    public function destroy(ProductPrice $productPrice)
    {
        $productPrice->delete();
        return redirect()->route('product-price.index')
            ->with('success', 'প্রোডাক্ট প্রাইস সফলভাবে মুছে ফেলা হয়েছে।');
    }
}
