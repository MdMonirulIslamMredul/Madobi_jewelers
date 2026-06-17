<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdminProfileImage extends Model
{
    use HasFactory;

    //Relationship with user
    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public static $data,$image,$imageName,$directory,$imageUrl;

    public static function save_profileImage($request)
    {
        self::$data = new AdminProfileImage();
        self::$data->user_id = Auth::user()->id??null;
        self::$data->admin_profile_image = self::saveProfileImage($request);
        self::$data->save();
    }
    public static function update_profileImage($request)
    {
        self::$data = AdminProfileImage::find($request->id);
        self::$data->user_id = Auth::user()->id??null;
        if($request->file('admin_profile_image')){
            if(self::$data->admin_profile_image){
                if(file_exists(self::$data->admin_profile_image)){
                    unlink(self::$data->admin_profile_image);
                    self::$data->admin_profile_image = self::saveProfileImage($request);
                }
            }
            else{
                self::$data->admin_profile_image = self::saveProfileImage($request);
            }
        }
        self::$data->save();
    }

    private static function saveProfileImage($request){
        self::$image = $request->file('admin_profile_image');
        self::$imageName = 'profile_admin_profile_image-'.rand().'.'. self::$image->Extension();
        self::$directory = 'profile/';
        self::$imageUrl = self::$directory.self::$imageName;
        self::$image->move(self::$directory,self::$imageName);
        return self::$imageUrl;
    }
}
