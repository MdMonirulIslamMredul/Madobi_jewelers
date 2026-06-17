<?php

namespace App\Http\Controllers\Admin;

use App\Models\Module;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\ModuleStoreUpdateRequest;

class ModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //authorize this user to access/give access to admin dashboard
        Gate::authorize('index-module');

        $modules = Module::latest('id')->select(['id', 'module_name', 'module_slug', 'updated_at'])
        ->paginate(30);

        return view('admin.module.index', compact('modules'));
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
    public function store(ModuleStoreUpdateRequest $request)
    {
        //authorize this user to access/give access to admin dashboard
        Gate::authorize('create-module');

        // dd($request->all());
        Module::create([
            'module_name' => $request->module_name,
            'module_slug' => Str::slug($request->module_name),
        ]);
        return redirect()->back()->with('message', 'Module Created Successfully 🙂');
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
    public function edit($module_slug)
    {
        //authorize this user to access/give access to admin dashboard
        Gate::authorize('edit-module');

        // dd($module_slug);
        $module = Module::where('module_slug', $module_slug)->first();

        return view('admin.module.edit', compact('module'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ModuleStoreUpdateRequest $request, $module_slug)
    {
        //authorize this user to access/give access to admin dashboard
        Gate::authorize('edit-module');

        // dd($request->all(), $module_slug);
        $module = Module::where('module_slug', $module_slug)->first();
        $module->update([
            'module_name' => $request->module_name,
            'module_slug' => Str::slug($request->module_name),
        ]);

        return redirect()->route('module.index')->with('message', 'Module Updated Successfully 🙂');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($module_slug)
    {
        //authorize this user to access/give access to admin dashboard
        Gate::authorize('delete-module');

        // dd($module_slug);
        $module = Module::where('module_slug', $module_slug)->first();
        $module->delete();

        return redirect()->back()->with('warning', 'Module Deleted Successfully');
    }
}
