<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class About extends Model
{
    use HasFactory;
    public static $data,$image,$imageName,$directory,$imageUrl;

    public static function save_service($request)
    {
        if($request->id){
            self::$data = About::find($request->id);
                if(self::$data->mv_photo){
                    if(file_exists(self::$data->mv_photo)){
                        unlink(self::$data->mv_photo);
                        self::$data->mv_photo = self::saveAboutmv_photo($request);
                    }
                }
                else{
                    self::$data->mv_photo = self::saveAboutmv_photo($request);
                }

                if(self::$data->about_photo){
                    if(file_exists(self::$data->about_photo)){
                        unlink(self::$data->about_photo);
                        self::$data->about_photo = self::saveAboutabout_photo($request);
                    }
                }
                else{
                    self::$data->about_photo = self::saveAboutabout_photo($request);
                }



                if(self::$data->banner_image){
                    if(file_exists(self::$data->banner_image)){
                        unlink(self::$data->banner_image);
                        self::$data->banner_image = self::saveBannerImage($request);
                    }
                }
                else{
                    self::$data->banner_image = self::saveBannerImage($request);
                }
        }
        else{
            self::$data = new About();
            self::$data->mv_photo = self::saveAboutmv_photo($request);
            self::$data->about_photo = self::saveAboutabout_photo($request);
            self::$data->banner_image = self::saveBannerImage($request);
        }
        self::$data->title = $request->title;
        self::$data->mission_title = $request->mission_title;
        self::$data->vision_title = $request->vision_title;
        self::$data->mission_details = $request->mission_details;
        self::$data->vision_details = $request->vision_details;
        self::$data->description = $request->description;
        self::$data->save();
    }
    public static function update_service($request)
    {
        self::$data = About::find($request->id);
        self::$data->title = $request->title;
        self::$data->mission_title = $request->mission_title;
        self::$data->vision_title = $request->vision_title;
        self::$data->mission_details = $request->mission_details;
        self::$data->vision_details = $request->vision_details;
        self::$data->description = $request->description;
        if($request->file('mv_photo')){
            if(self::$data->mv_photo){
                if(file_exists(self::$data->mv_photo)){
                    unlink(self::$data->mv_photo);
                    self::$data->mv_photo = self::saveAboutmv_photo($request);
                }
            }
            else{
                self::$data->mv_photo = self::saveAboutmv_photo($request);
            }
        }
        if($request->file('about_photo')){
            if(self::$data->about_photo){
                if(file_exists(self::$data->about_photo)){
                    unlink(self::$data->about_photo);
                    self::$data->about_photo = self::saveAboutabout_photo($request);
                }
            }
            else{
                self::$data->about_photo = self::saveAboutabout_photo($request);
            }
        }

        if($request->file('banner_image')){
            if(self::$data->banner_image){
                if(file_exists(self::$data->banner_image)){
                    unlink(self::$data->banner_image);
                    self::$data->banner_image = self::saveBannerImage($request);
                }
            }
            else{
                self::$data->banner_image = self::saveBannerImage($request);
            }
        }

        self::$data->save();
    }

    private static function saveAboutmv_photo($request){
        self::$image = $request->file('mv_photo');
        self::$imageName = 'about_mv_photo-'.rand().'.'. self::$image->Extension();
        self::$directory = 'About/';
        self::$imageUrl = self::$directory.self::$imageName;
        self::$image->move(self::$directory,self::$imageName);
        return self::$imageUrl;
    }

    private static function saveAboutabout_photo($request){
        self::$image = $request->file('about_photo');
        self::$imageName = 'about_about_photo-'.rand().'.'. self::$image->Extension();
        self::$directory = 'About/';
        self::$imageUrl = self::$directory.self::$imageName;
        self::$image->move(self::$directory,self::$imageName);
        return self::$imageUrl;
    }
    private static function saveBannerImage($request){
        self::$image = $request->file('banner_image');
        self::$imageName = 'about_banner_image-'.rand().'.'. self::$image->Extension();
        self::$directory = 'About/';
        self::$imageUrl = self::$directory.self::$imageName;
        self::$image->move(self::$directory,self::$imageName);
        return self::$imageUrl;
    }
}
