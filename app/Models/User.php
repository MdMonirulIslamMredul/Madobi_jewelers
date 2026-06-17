<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;
    public static $data,$image, $imageName, $imageDirectory, $imageUrl;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'role_id', 'department_id', 'name', 'phone', 'email', 'password', 'is_active'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function update_profile($request)
    {
        // dd($request->all());

        self::$data = User::find($request->id);
//        dd(Hash::check($request->old_password, self::$data->password));


//        if(Hash::check($request->old_password, self::$data->password)){
            self::$data->name= $request->name;
            self::$data->email= $request->email;
            self::$data->is_active = $request->filled('is_active')??null;
            if ($request->new_password){

                self::$data->password= Hash::make($request->new_password);
            }

            if ($request->file('image')) {
                if (self::$data->image) {
                    if (file_exists(self::$data->image)) {
                        unlink(self::$data->image);
                        self::$data->image = self::saveImage($request);
                    }
                } else {
                    self::$data->image = self::saveImage($request);
                }
            }
            self::$data->save();

    }
    public static function saveImage($request)
    {
        if ($request->file('image')) {
            self::$image = $request->file('image');
            self::$imageName = 'user-' . rand() . '.' . self::$image->Extension();
            self::$imageDirectory = 'user/';
            self::$imageUrl = self::$imageDirectory . self::$imageName;
            self::$image->move(self::$imageDirectory,self::$imageName);
            return self::$imageUrl;
        }
    }

    //Relationship with Profile
    public function profile() {
        return $this->hasOne(Profile::class);
    }

    //Relationship with Profile
    public function adminProfileImage() {
        return $this->hasOne(AdminProfileImage::class);
    }

    //Relationship with career
    public function career() {
        return $this->hasOne(Career::class);
    }

    //Relationship with Role
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }


    //Relationship with Permission
    //True or false
    public function hasPermission($permission_slug)
    {
        return $this->role->permissions()->where('permission_slug', $permission_slug)
        ->first() ? true : false;
    }

    public function bondhok()
    {
        return $this->hasMany(Bondhok::class,'user_id', 'id');
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class,'user_id','id');
    }
    public function sales()
    {
        return $this->hasMany(Sell::class,'user_id','id');
    }

    public function karigorproduct()
    {
        return $this->hasMany(KarigorProduct::class,'user_id','id');
    }
    public function karigor()
    {
        return $this->hasMany(Karigor::class,'karigor_id','id');
    }

    public function repair()
    {
        return $this->hasMany(Repair::class,'user_id','id');
    }

}
