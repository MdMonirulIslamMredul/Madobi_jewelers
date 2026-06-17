<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\Models\User;
use App\Models\Karigor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //authorize this user to access/give access to admin dashboard
        Gate::authorize('index-user');

        $users = User::with(['role:id,role_name,role_slug', 'profile', 'adminProfileImage'])
        ->get();

        $roles = Role::where('is_deletable', 1)->select(['id', 'role_name'])->get();

        return view('admin.user.index', compact('users', 'roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //authorize this user to access/give access to admin dashboard
        Gate::authorize('create-user');
        if ($request->image) {
            $profile = 'profile'. time() . '.' . $request->image->extension();
            $image = $request->image->move(public_path('user'), $profile);
        } else {
            $profile = 'default_profile.png'; // default image
            
        }

        // dd($request);
        $user = User::create([
            'role_id' => $request->role_id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'image' => $profile,
            'password' => Hash::make($request->password)
        ]);
         // check if karigor
         if($request->role_id == 5){
            $karigor = new Karigor;
            $karigor->karigor_id = $user->id;
            $karigor->save();
        } 

        return redirect()->back()->with('message', 'User Created Successfully 🙂');

    }

    public function new_user(Request $request)
    {
        // dd($request->all());
        //authorize this user to access/give access to admin dashboard
        Gate::authorize('create-user');

            if ($request->profile) {
                $profile = 'profile'. time() . '.' . $request->profile->extension();
                $image = $request->profile->move(public_path('user'), $profile);
            } else {
                $profile = 'default_profile.png'; // default image
                
            }
            
            if ($request->photo1) {
                $imageName1 = 'photo1'. time() . '.' . $request->photo1->extension();
                $image1 = $request->photo1->move(public_path('user'), $imageName1);
            } else {
                $imageName1 = 'default-cover.jpg'; // default image
            }

            $user = new User;
            $user->role_id = $request->role_id;
            $user->name = $request->name;
            $user->address = $request->address;
            $user->phone = $request->phone;
            $user->image = $profile;
            $user->photo1 = $imageName1;
            $user->save();

            // check if karigor
            if($request->role_id == 5){
                $karigor = new Karigor;
                $karigor->karigor_id = $user->id;
                $karigor->save();
            } 

        return response()->json(['user_id' => $user->id], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //authorize this user to access/give access to admin dashboard
        Gate::authorize('edit-user');

        $user = User::with(['role:id,role_name'])->where('id', $id)->first();
        // $roles = Role::select(['id', 'role_name'])->get();

        return view('admin.user.view', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //authorize this user to access/give access to admin dashboard
        Gate::authorize('edit-user');

        $user = User::where('id', $id)->first();
        $roles = Role::select(['id', 'role_name'])->get();

        return view('admin.user.edit', compact('user', 'roles'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdateRequest $request, $id)
    {
        //authorize this user to access/give access to admin dashboard
        Gate::authorize('edit-user');

        // dd($request->all(), $id);
        $user = User::where('id', $id)->first();

        $user->update([
            'role_id' => $request->role_id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'is_active' => $request->filled('is_active'),
        ]);
         // check if karigor
         if($request->role_id == 5){
            $kar = Karigor::where('karigor_id',$id)->latest()->first();
            if($kar){
                $karigor = $kar;
            }else{
                $karigor = new Karigor;
            }
            $karigor->karigor_id = $user->id;
            $karigor->save();
        } 

        return redirect()->route('users.index')->with('message', 'User Updated Successfully 🙂');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //authorize this user to access/give access to admin dashboard
        Gate::authorize('delete-user');

        // dd($id);
        $user = User::where('id', $id)->first();
        $karigor = Karigor::where('karigor_id',$id)->latest()->first();
        if ($user->email != 'admin@admin.com') {
            $user->delete();
            $karigor->delete();
            return redirect()->back()->with('warning', 'User Deleted Successfully');
        } else {
            return redirect()->back()->with('error', 'Admin Cannot be Deleted 😡!!');
        }

    }
}
