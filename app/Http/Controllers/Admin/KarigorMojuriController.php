<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KarigorMojuri;
use Illuminate\Http\Request;

class KarigorMojuriController extends Controller
{
    /**
     * Display a listing of the karigor mojuri rates.
     */
    public function index(Request $request)
    {
        $query = KarigorMojuri::query();

        if ($request->filled('category')) {
            $query->where('category_name', $request->category);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $mojuris = $query->orderBy('created_at', 'desc')->paginate(15);
        $totalCount = KarigorMojuri::count();

        return view('admin.karigor_mojuri.index', compact('mojuris', 'totalCount'));
    }

    /**
     * Show the form for creating a new karigor mojuri rate.
     */
    public function create()
    {
        $categories = ['Gold', 'Rupa', 'Diamond', 'Platinum'];
        $types = ['22k', '21k', '18k'];

        return view('admin.karigor_mojuri.create', compact('categories', 'types'));
    }

    /**
     * Store a newly created karigor mojuri rate in storage.
     */
    public function store(Request $request)
    {
        // Accept either category_name or catergory_name if sent by form
        $category = $request->input('category_name', $request->input('catergory_name'));

        $request->merge(['category_name' => $category]);

        $validated = $request->validate([
            'category_name' => 'required|string|in:Gold,Rupa,Diamond,Platinum,gold,rupa,diamond,platinum',
            'type'          => 'required|string|in:22k,21k,18k,22K,21K,18K',
            'per_vori_tk'   => 'required|numeric|min:0',
            'per_gram_tk'   => 'nullable|numeric|min:0',
        ], [
            'category_name.required' => 'ক্যাটাগরি নির্বাচন করুন।',
            'category_name.in'       => 'ক্যাটাগরি অবশ্যই Gold, Rupa, Diamond বা Platinum হতে হবে।',
            'type.required'          => 'টাইপ নির্বাচন করুন।',
            'type.in'                => 'টাইপ অবশ্যই 22k, 21k বা 18k হতে হবে।',
            'per_vori_tk.required'   => 'প্রতি ভরি মজুরি লিখুন।',
            'per_vori_tk.numeric'    => 'প্রতি ভরি মজুরি একটি সংখ্যা হতে হবে।',
        ]);

        // Auto calculate: per_vori_tk / 11.664 = per_gram_tk
        $perVori = (float)$validated['per_vori_tk'];
        $validated['per_gram_tk'] = round($perVori / 11.664, 2);

        KarigorMojuri::create($validated);

        return redirect()->route('karigor-mojuri.index')
            ->with('success', 'কারিগর মজুরি সফলভাবে সংরক্ষণ করা হয়েছে।');
    }

    /**
     * Show the form for editing the specified karigor mojuri rate.
     */
    public function edit($id)
    {
        $mojuri = KarigorMojuri::findOrFail($id);
        $categories = ['Gold', 'Rupa', 'Diamond', 'Platinum'];
        $types = ['22k', '21k', '18k'];

        return view('admin.karigor_mojuri.edit', compact('mojuri', 'categories', 'types'));
    }

    /**
     * Update the specified karigor mojuri rate in storage.
     */
    public function update(Request $request, $id)
    {
        $mojuri = KarigorMojuri::findOrFail($id);

        $category = $request->input('category_name', $request->input('catergory_name'));
        $request->merge(['category_name' => $category]);

        $validated = $request->validate([
            'category_name' => 'required|string|in:Gold,Rupa,Diamond,Platinum,gold,rupa,diamond,platinum',
            'type'          => 'required|string|in:22k,21k,18k,22K,21K,18K',
            'per_vori_tk'   => 'required|numeric|min:0',
            'per_gram_tk'   => 'nullable|numeric|min:0',
        ], [
            'category_name.required' => 'ক্যাটাগরি নির্বাচন করুন।',
            'type.required'          => 'টাইপ নির্বাচন করুন।',
            'per_vori_tk.required'   => 'প্রতি ভরি মজুরি লিখুন।',
        ]);

        // Auto calculate: per_vori_tk / 11.664 = per_gram_tk
        $perVori = (float)$validated['per_vori_tk'];
        $validated['per_gram_tk'] = round($perVori / 11.664, 2);

        $mojuri->update($validated);

        return redirect()->route('karigor-mojuri.index')
            ->with('success', 'কারিগর মজুরি সফলভাবে আপডেট করা হয়েছে।');
    }

    /**
     * Remove the specified karigor mojuri rate from storage.
     */
    public function destroy($id)
    {
        $mojuri = KarigorMojuri::findOrFail($id);
        $mojuri->delete();

        return redirect()->route('karigor-mojuri.index')
            ->with('success', 'কারিগর মজুরি সফলভাবে মুছে ফেলা হয়েছে।');
    }
}
