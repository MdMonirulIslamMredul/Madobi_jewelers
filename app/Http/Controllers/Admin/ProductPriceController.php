<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductPrice;
use App\Models\ProductPriceHistory;
use Illuminate\Http\Request;

class ProductPriceController extends Controller
{
    /**
     * Display a listing of the product prices.
     */
    public function index()
    {
        $prices = ProductPrice::withCount('histories')->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.product_price.index', compact('prices'));
    }

    /**
     * Display price history records with optional filters.
     */
    public function history(Request $request)
    {
        $query = ProductPriceHistory::with(['productPrice', 'changer'])->latest();

        if ($request->filled('product_price_id')) {
            $query->where('product_price_id', $request->product_price_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('product_name', 'LIKE', "%{$search}%");
        }

        if ($request->filled('from_date')) {
            $query->whereDate('effective_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('effective_date', '<=', $request->to_date);
        }

        $histories = $query->paginate(20)->appends($request->all());
        $products = ProductPrice::orderBy('product_name', 'asc')->get();

        return view('admin.product_price.history', compact('histories', 'products'));
    }

    /**
     * Return JSON history for AJAX modal.
     */
    public function getHistoryJson($id)
    {
        $productPrice = ProductPrice::findOrFail($id);
        $histories = ProductPriceHistory::with('changer')
            ->where('product_price_id', $id)
            ->latest()
            ->get()
            ->map(function ($h) {
                return [
                    'id'                     => $h->id,
                    'product_name'           => $h->product_name,
                    'buying_price'           => number_format($h->buying_price, 2),
                    'buying_price_per_gram'  => number_format($h->buying_price_per_gram, 2),
                    'selling_price'          => number_format($h->selling_price, 2),
                    'selling_price_per_gram' => number_format($h->selling_price_per_gram, 2),
                    'change_type'            => $h->change_type,
                    'changed_by'             => $h->changer->name ?? 'Admin',
                    'effective_date'         => $h->effective_date ? $h->effective_date->format('d M, Y h:i A') : $h->created_at->format('d M, Y h:i A'),
                    'note'                   => $h->note ?: '-',
                ];
            });

        return response()->json([
            'status'  => 'success',
            'product' => [
                'id'           => $productPrice->id,
                'product_name' => $productPrice->product_name,
            ],
            'data'    => $histories,
        ]);
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
            'product_name'           => 'required|string|max:255',
            'buying_price'           => 'required|numeric|min:0',
            'buying_price_per_gram'  => 'nullable|numeric|min:0',
            'selling_price'          => 'required|numeric|min:0',
            'selling_price_per_gram' => 'nullable|numeric|min:0',
            'price'                  => 'nullable|numeric|min:0',
            'note'                   => 'nullable|string|max:500',
        ]);

        // Auto calculate per gram prices if not provided (1 vori = 11.664 gm)
        $validated['buying_price_per_gram'] = !empty($request->buying_price_per_gram) 
            ? $request->buying_price_per_gram 
            : round($validated['buying_price'] / 11.664, 2);

        $validated['selling_price_per_gram'] = !empty($request->selling_price_per_gram) 
            ? $request->selling_price_per_gram 
            : round($validated['selling_price'] / 11.664, 2);

        if (empty($validated['price'])) {
            $validated['price'] = $validated['selling_price'];
        }

        $note = $validated['note'] ?? null;
        unset($validated['note']);

        $productPrice = ProductPrice::create($validated);

        // Store price history record
        ProductPriceHistory::create([
            'product_price_id'       => $productPrice->id,
            'product_name'           => $productPrice->product_name,
            'buying_price'           => $productPrice->buying_price,
            'buying_price_per_gram'  => $productPrice->buying_price_per_gram,
            'selling_price'          => $productPrice->selling_price,
            'selling_price_per_gram' => $productPrice->selling_price_per_gram,
            'price'                  => $productPrice->price,
            'change_type'            => 'create',
            'changed_by'             => auth()->id(),
            'effective_date'         => now(),
            'note'                   => $note ?: 'নতুন প্রোডাক্ট রেট নির্ধারণ',
        ]);

        return redirect()->route('product-price.index')
            ->with('success', 'প্রোডাক্ট প্রাইস সফলভাবে সংরক্ষণ করা হয়েছে এবং হিস্ট্রিতে যুক্ত হয়েছে।');
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
            'product_name'           => 'required|string|max:255',
            'buying_price'           => 'required|numeric|min:0',
            'buying_price_per_gram'  => 'nullable|numeric|min:0',
            'selling_price'          => 'required|numeric|min:0',
            'selling_price_per_gram' => 'nullable|numeric|min:0',
            'price'                  => 'nullable|numeric|min:0',
            'note'                   => 'nullable|string|max:500',
        ]);

        // Auto calculate per gram prices if not provided (1 vori = 11.664 gm)
        $validated['buying_price_per_gram'] = !empty($request->buying_price_per_gram) 
            ? $request->buying_price_per_gram 
            : round($validated['buying_price'] / 11.664, 2);

        $validated['selling_price_per_gram'] = !empty($request->selling_price_per_gram) 
            ? $request->selling_price_per_gram 
            : round($validated['selling_price'] / 11.664, 2);

        if (empty($validated['price'])) {
            $validated['price'] = $validated['selling_price'];
        }

        $note = $validated['note'] ?? null;
        unset($validated['note']);

        $productPrice->update($validated);

        // Store price history record
        ProductPriceHistory::create([
            'product_price_id'       => $productPrice->id,
            'product_name'           => $productPrice->product_name,
            'buying_price'           => $productPrice->buying_price,
            'buying_price_per_gram'  => $productPrice->buying_price_per_gram,
            'selling_price'          => $productPrice->selling_price,
            'selling_price_per_gram' => $productPrice->selling_price_per_gram,
            'price'                  => $productPrice->price,
            'change_type'            => 'update',
            'changed_by'             => auth()->id(),
            'effective_date'         => now(),
            'note'                   => $note ?: 'রেট আপডেট / হালনাগাদ',
        ]);

        return redirect()->route('product-price.index')
            ->with('success', 'প্রোডাক্ট প্রাইস সফলভাবে আপডেট করা হয়েছে এবং হিস্ট্রিতে যুক্ত হয়েছে।');
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

