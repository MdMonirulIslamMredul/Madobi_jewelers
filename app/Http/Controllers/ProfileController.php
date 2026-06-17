<?php

namespace App\Http\Controllers;

use App\Http\Requests\CareerStoreRequest;
use App\Http\Requests\CareerUpdateRequest;
use App\Models\User;
use App\Models\Career;
use App\Models\Profile;
use App\Models\ProfileImage;
use Illuminate\Http\Request;
use App\Models\BannerAndTitle;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\ProfilePasswordChangeRequest;

class ProfileController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function customerDashboard()
    {
        $user = Auth::user();
        $profile = Profile::where('user_id', Auth::id())->first();
        $career = Career::where('user_id', Auth::id())->first();
        $banner = BannerAndTitle::where('page','contacts')->latest()->first();
        if($profile) {
            $profileImage = ProfileImage::where('profile_id', Auth::user()->profile->id)->first();
            return view('frontend.customer.customer_dashboard', compact('user', 'banner', 'profile', 'profileImage', 'career'));
        } else {
            return view('frontend.customer.customer_dashboard', compact('user', 'banner', 'profile', 'career'));
        }
        
    }

    public function customerUpdate(Request $request)
    {
        dd($request->all());
        User::update_profile($request);
        return back()->with('message','Your Information updated successfully');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function customerInfoStore(Request $request)
    {
        //authorize this user to access/give access to admin dashboard
        // Gate::authorize('profile-update');
        // dd($request->all());
        $profile = $request->all();
        $profile['user_id'] = Auth::id();

        $existing_profile = Profile::where('user_id', Auth::id())->first();
        if ($existing_profile) {
            $existing_profile->update($profile);

        } else {
            $profile = Profile::create([
            'user_id' => Auth::id(),
            'nid_num' => $request->nid_num,
            'contact_number' => $request->contact_number,
            'address' => $request->address,
            'blood_group' => $request->blood_group,
            'blood_donner' => $request->blood_donner,
            'facebook' => $request->facebook,
            'twitter' => $request->twitter,
            'linkedIn' => $request->linkedIn,
            'Instagram' => $request->Instagram,
        ]);
        }
        return redirect()->back()->with('message', 'Profile Updated Successfully');

    }

    public function careerStore(CareerStoreRequest $request)
    {
        // dd($request->all());
        
        $career = $request->all();
        $career['user_id'] = Auth::id();

        $imageNameOne = time().'.'.$request->resume->extension();
        $request->resume->move(public_path('resume'), $imageNameOne);

        $imageNameTwo = time().'.'.$request->office_idcard->extension();
        $request->office_idcard->move(public_path('idcard'), $imageNameTwo);

        $existing_career = Career::where('user_id', Auth::id())->first();
        if ($existing_career) {
            $existing_career->update($career);

        } else {
            $career = Career::create([
            'user_id' => Auth::id(),
            'designation' => $request->designation,
            'university' => $request->university,
            'company_name' => $request->company_name,
            'career_summery' => $request->career_summery,
            'skills' => $request->skills,
            'experience' => $request->experience,
            'education' => $request->education,
            'resume' => $imageNameOne,
            'office_idcard' => $imageNameTwo,
        ]);
        }
        return redirect()->back()->with('message', 'Profile Updated Successfully');

    }
    public function careerUpdate(CareerUpdateRequest $request, $id)
    {
        // dd($request->all());

        $career = Career::where('user_id', Auth::id())->findOrFail($id);

        // Update image if a new one is provided
        if ($request->hasFile('resume') && $request->file('resume')->isValid()) {
            // Delete the old image
            if ($career->resume) {
                unlink('resume/' . $career->resume);
            }
            // Upload new image
            $imageNameOne = time().'.'.$request->resume->extension();
            $request->resume->move(public_path('resume'), $imageNameOne);
            $career->resume = $imageNameOne;
        }

        // Update image if a new one is provided
        if ($request->hasFile('office_idcard') && $request->file('office_idcard')->isValid()) {
            // Delete the old image
            if ($career->office_idcard) {
                unlink('idcard/' . $career->office_idcard);
            }
            // Upload new image
            $imageNameTwo = time().'.'.$request->office_idcard->extension();
            $request->office_idcard->move(public_path('idcard'), $imageNameTwo);
            $career->office_idcard = $imageNameTwo;
        }

        $career->update([
                'user_id' => Auth::id(),
                'designation' => $request->designation,
                'university' => $request->university,
                'company_name' => $request->company_name,
                'career_summery' => $request->career_summery,
                'skills' => $request->skills,
                'experience' => $request->experience,
                'education' => $request->education,
            ]);

        return redirect()->back()->with('message', 'Profile Updated Successfully');

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
    public function edit($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getUpdatePassword()
    {
        //authorize this user to access/give access to admin dashboard
        // Gate::authorize('password-update');
        // return view('admin.pages.profile.update_password');
    }

    public function updatePassword(ProfilePasswordChangeRequest $request)
    {
        //authorize this user to access/give access to admin dashboard
        // Gate::authorize('password-update');
        // dd($request->all());
        $user = Auth::user();
        $hashedPassword = $user->password;
        //existing password === request password
        if (Hash::check($request->old_password, $hashedPassword)) {
            //new password == old stored password
            if(!Hash::check($request->password, $hashedPassword)) {
                $user->update([
                    'password' => Hash::make($request->password),
                ]);

            Auth::logout();
            // Toastr::success("Password Updated Successfully 🙂");
                return redirect()->route('login')->with('message', 'Password Updated Successfully');
            } else {
                // Toastr::error('New Password cannot be the same as old pasword');
                // return redirect()->back();
                return redirect()->back()->with('error','New Password cannot be the same as old pasword');
            }
        } else {
            // Toastr::error("Credentials doesn't match");
            //     return redirect()->back();
            return redirect()->back()->with('error',"Credentials doesn't match");
        }
    }
}
