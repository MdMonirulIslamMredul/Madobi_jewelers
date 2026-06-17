<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ProductCategory;
use App\Http\Controllers\Controller;

class ProductCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = ProductCategory::latest('id')->select(['id', 'category_name', 'category_slug', 'updated_at'])->paginate(1000);

        return view('admin.product_category.product_category', compact('categories'));
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

        ProductCategory::create([
            'category_name' => $request->category_name,
            'category_slug' => Str::slug($request->category_name),
        ]);

        return redirect()->back()->with('message', 'Category Created Successfully 🙂');
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
    public function edit($category_slug)
    {
        $category = ProductCategory::where('category_slug', $category_slug)->first();

        return view('admin.product_category.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $category_slug)
    {
        // dd($request->all());
        $category = ProductCategory::where('category_slug', $category_slug)->first();

        $category->update([
            'category_name' => $request->category_name,
            'category_slug' => Str::slug($request->category_name),
        ]);

        return redirect()->route('productcategory.index')->with('message', 'Category Updated Successfully 🙂');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($category_slug)
    {
        $category = ProductCategory::where('category_slug', $category_slug)->first();
        $category->delete();

        return redirect()->back()->with('error', 'Category Deleted Successfully');
    }
}
