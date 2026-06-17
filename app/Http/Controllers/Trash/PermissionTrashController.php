<?php

namespace App\Http\Controllers\Trash;

use App\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class PermissionTrashController extends Controller
{
    public function trash()
    {
        //authorize this user to access/give access to admin dashboard
        Gate::authorize('delete-permission');

        $permissions = Permission::onlyTrashed()->with(['module:id,module_name,module_slug'])->latest('id')
        ->select(['id', 'module_id','permission_name', 'permission_slug', 'updated_at'])->paginate(20);

        return view('admin.permission.trash', compact('permissions'));
    }

    public function restore($permission_slug)
    {
        //authorize this user to access/give access to admin dashboard
        Gate::authorize('delete-permission');

        $permission = Permission::onlyTrashed()->where('permission_slug', $permission_slug)->first();
        $permission->restore();

        return redirect()->route('permission.index')->with('info', 'Permission Restored Successfully 🙂');
    }

    public function forceDelete($permission_slug)
    {
        //authorize this user to access/give access to admin dashboard
        Gate::authorize('delete-permission');

        $permission = Permission::onlyTrashed()->where('permission_slug', $permission_slug)->first();
        $permission->forceDelete();

        return redirect()->back()->with('error', 'Permission Deleted Permanently');
    }
}
