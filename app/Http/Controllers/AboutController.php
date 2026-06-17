<?php

namespace App\Http\Controllers;

use App\Models\About;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AboutController extends Controller
{
    public function tech_web_add_about()
    {
        return view('admin.about.about',[
            'abouts'=>About::get(),
            'about_data'=>DB::table('abouts')->latest()->first(),
        ]);

    }

    public function tech_web_store_about(Request $request)
    {
        About::save_service($request);
        return back()->with('message','About added successfully');
    }

    public function tech_web_edit_about($id)
    {
        return view('admin.about.edit_about',[
            'about'=>About::find($id),
        ]);
    }

    public function tech_web_update_about(Request $request)
    {
        About::update_service($request);
        return redirect()->route('add.about')->with('message','About update successfully');
    }
}
