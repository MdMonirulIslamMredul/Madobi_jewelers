<?php

namespace App\Http\Controllers\Trash;

use App\Models\Module;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ModuleTrashController extends Controller
{
    public function trash()
    {
        //authorize this user to access/give access to admin dashboard
        // Gate::authorize('delete-module');

        $modules = Module::onlyTrashed()->latest('id')
        ->select(['id', 'module_name', 'module_slug', 'updated_at'])
        ->paginate(20);

        return view('admin.module.trash', compact('modules'));
    }

    public function restore($module_slug)
    {
        //authorize this user to access/give access to admin dashboard
        // Gate::authorize('delete-module');

        $module = Module::onlyTrashed()->where('module_slug', $module_slug)->first();
        $module->restore();

        return redirect()->route('module.index')->with('info', 'Module Restored Successfully 🙂');
    }

    public function forceDelete($module_slug)
    {
        //authorize this user to access/give access to admin dashboard
        // Gate::authorize('delete-module');

        $module = Module::onlyTrashed()->where('module_slug', $module_slug)->first();
        $module->forceDelete();

        return redirect()->back()->with('error', 'Module Deleted Permanently');
    }
}
