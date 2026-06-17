<?php

namespace App\Http\Controllers;

use App\Models\ProfileImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileImageController extends Controller
{
    // public function tech_web_add_property()
    // {
    //     $profileImage = ProfileImage::where('profile_id', Auth::user()->profile->id)->first();
    //     return view('frontend.customer.customer_dashboard', compact( 'profileImage'));

    // }

    public function storeProfileImage(Request $request)
    {
        // dd($request->all());
        ProfileImage::save_profileImage($request);
        return back()->with('message','Profile Image Update Successfully');
    }

    // public function tech_web_edit_property($id)
    // {
    //     return view('admin.property.edit_property',[
    //         'property'=>ProfileImage::find($id),
    //     ]);
    // }

    public function updateProfileImage(Request $request)
    {
        // dd($request->all());
        ProfileImage::update_profileImage($request);
        return back()->with('message','Profile Image Update Successfully');
    }
}
