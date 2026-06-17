<?php

namespace App\Http\Controllers;

use App\Models\Logo;
use App\Models\User;
use App\Models\FooterDetail;
use App\Models\WebsiteLinks;
use Illuminate\Http\Request;
use App\Models\AdminProfileImage;
use Illuminate\Support\Facades\Auth;

class GeneralController extends Controller
{
    public function tech_web_general_settings()
    {
        return view('admin.general.general',[
            'logo'=>Logo::latest()->first(),
            'links'=>WebsiteLinks::latest()->first(),
            'footer'=>FooterDetail::latest()->first(),
        ]);
    }

    public function tech_web_profile_settings()
    {
//        return User::where('id',Auth::user()->id)->first();
        return view('admin.profile.profile',[
            'user'=>User::where('id',Auth::user()->id)->first(),
        ]);
    }

    public function tech_web_profileimage_settings()
    {
        $adminProfileImage = AdminProfileImage::where('user_id', Auth::user()->id)->first();
        return view('admin.profile.profile_image', compact( 'adminProfileImage'));

    }

    public function tech_web_store_profile(Request $request)
    {
        // dd($request->all());
        AdminProfileImage::save_profileImage($request);
        return back()->with('message','Profile Image Update Successfully');
    }

    public function tech_web_update_profile(Request $request)
    {
        AdminProfileImage::update_profileImage($request);
        return back()->with('message','Profile Image Update Successfully');
    }
}
