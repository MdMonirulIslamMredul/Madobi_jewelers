<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ProductCategory;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::latest('id')->select(['id', 'category_id', 'product_name', 'product_slug', 'is_active', 'updated_at'])->paginate(1000);

        $categories = ProductCategory::select(['id', 'category_name'])->get();
        return view('admin.product.product', compact('products', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        Product::create([
            'category_id' => $request->category_id,
            'product_name' => $request->product_name,
            'product_slug' => Str::slug($request->product_name),
        ]);

        return redirect()->back()->with('message', 'Product Created Successfully 🙂');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($product_slug)
    {
        $product = Product::where('product_slug', $product_slug)->first();
        $categories = ProductCategory::select(['id', 'category_name'])->get();
        return view('admin.product.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $product_slug)
    {
        // dd($request->all());
        $product = Product::where('product_slug', $product_slug)->first();

        $product->update([
            'product_name' => $request->product_name,
            'product_slug' => Str::slug($request->product_name),
            'is_active' => $request->filled('is_active'),
        ]);

        return redirect()->route('product.index')->with('message', 'Product Updated Successfully 🙂');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($product_slug)
    {
        // dd($product_slug);
        $product = Product::where('product_slug', $product_slug)->first();
        $product->delete();

        return redirect()->back()->with('warning', 'Product Deleted Successfully');
    }
}
