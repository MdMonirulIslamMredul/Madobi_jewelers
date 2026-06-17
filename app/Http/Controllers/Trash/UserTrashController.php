<?php

namespace App\Http\Controllers\Trash;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class UserTrashController extends Controller
{
    public function trash()
    {
        //authorize this user to access/give access to admin dashboard
        Gate::authorize('delete-user');

        $users = User::onlyTrashed()
        ->with(['role:id,role_name,role_slug', 'profile', 'adminProfileImage'])
        ->latest('id')
        ->select(['id', 'role_id', 'name', 'email', 'is_active', 'updated_at'])
        ->paginate(1000);

        return view('admin.user.trash', compact('users'));
    }

    public function restore($id)
    {
        //authorize this user to access/give access to admin dashboard
        Gate::authorize('delete-user');

        // dd($id);
        $user = User::onlyTrashed()->where('id', $id)->first();
        $user->restore();

        return redirect()->route('users.index')->with('info', 'User Restored Successfully 🙂');
    }

    public function forceDelete($id)
    {
        //authorize this user to access/give access to admin dashboard
        Gate::authorize('delete-user');

        // dd($id);
        $user = User::onlyTrashed()->where('id', $id)->first();
        $user->forceDelete();

        return redirect()->back()->with('error', 'User Deleted Permanently');
    }
}
