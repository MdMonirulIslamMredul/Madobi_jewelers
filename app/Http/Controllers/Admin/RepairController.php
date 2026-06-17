<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\ProductCategory;
use App\Models\Product;
use App\Models\Repair;
use App\Models\KarigorProduct;

require_once app_path('helpers.php');

class RepairController extends Controller
{
   public function repair_index()
   {
    $users= User::latest()->get();
    $categories = ProductCategory::select(['id', 'category_name'])->get();
    $repairs = Repair::latest()->get();
    
    return view('admin.repair.index',compact('users','categories','repairs'));
   }


   public function repair_store(Request $request)
   {

        // dd($request);
        if ($request->photo) {
            $imageName = 'repair_original'. time() . '.' . $request->photo->extension();
            $image = $request->photo->move(public_path('user/repair'), $imageName);
            $path1 = 'user/repair/'.$convertedimageName;
        } else {
            $path1 = 'cover/default-cover.jpg'; // default image
        }

        $request_gold = [
            'vori' => $request->bhori,
            'ana' => $request->ana,
            'roti' => $request->roti,
            'point' => $request->point,
        ];

        $request_added_gold = [
            'vori' => $request->added_bhori,
            'ana' => $request->added_ana,
            'roti' => $request->added_roti,
            'point' => $request->added_point,
        ];

        $total=calculateTotalGold($request_gold);

        $total_added=calculateTotalGold($request_added_gold);

        if($request->repair_id){
            if ($request->converted_photo) {
                $convertedimageName = 'repair_converted'. time() . '.' . $request->converted_photo->extension();
                $image = $request->converted_photo->move(public_path('user/repair'), $convertedimageName);
                $path = 'user/repair/'.$convertedimageName;
            } else {
                $path = 'cover/default-cover.jpg'; // default image
            }

            $repair = Repair::find($request->repair_id);
            $repair->karigor_id= $request->user_id ?? null ;
            $repair->status= $request->status ?? null ;
            $repair->added_status= $request->added_status ?? null ;
            $repair->point = $total['point'];
            $repair->added_bhori = $total_added['vori'];
            $repair->added_ana = $total_added['ana'];
            $repair->added_roti = $total_added['roti'];
            $repair->added_point = $total_added['point'];
            $repair->added_gram = $request->added_gram ?? 0 ;
            $repair->converted_photo = $path;
            $repair->save();

            $kp = KarigorProduct::where('repair_id',$repair->id)->latest()->first();

            if($kp){
                $karigor_product = $kp;
            }else{
                $karigor_product = new KarigorProduct;
            }
            $karigor_product->user_id = $request->user_id ?? null ;
            $karigor_product->repair_id = $repair->id ?? null ;
            $karigor_product->product_id = $repair->product_id ?? null ;
            $karigor_product->converted_product_id = $repair->product_id ?? null ;
            $karigor_product->converted_category_id = $repair->category_id ?? null ;
            $karigor_product->category_id = $repair->category_id ?? null ;
            $karigor_product->karat = $repair->karat ?? null ;
            $karigor_product->bhori = $repair->bhori ?? null ;
            $karigor_product->ana = $repair->ana ?? null ;
            $karigor_product->roti = $repair->roti ?? null ;
            $karigor_product->point = $repair->point ?? null ;
            $karigor_product->gram = $repair->gram ?? null ;
            $karigor_product->prev_photo = $repair->prev_photo ?? null ;
            $karigor_product->converted_photo = $repair->converted_photo ?? null ;
            $karigor_product->details = $repair->details ?? null ;
            $karigor_product->qty = $repair->qty ?? null ;
            $karigor_product->order_date = $repair->order_date ?? null ;
            $karigor_product->receive_date = $repair->receive_date ?? null ;
            $karigor_product->added_bhori = $total_added['vori'];
            $karigor_product->added_ana = $total_added['ana'];
            $karigor_product->added_roti = $total_added['roti'];
            $karigor_product->added_point = $total_added['point'];
            $karigor_product->added_gram = $request->added_gram ?? 0 ;
            $karigor_product->status = $request->status ?? null ;
            $karigor_product->save();

            return redirect()->route('karigor.index')->with('message', 'Product For Repair Karigor Successfully 🙂');

        }

        $repair = new Repair;
        $repair->user_id= $request->user_id ?? null ;
        $repair->category_id= $request->category_id ?? null ;
        $repair->product_id= $request->product_id ?? null ;
        $repair->karat= $request->karat ?? null ;
        $repair->qty = $request->qtr ?? null ;
        $repair->gram = $request->gram ?? 0 ;
        $repair->added_gram = $request->added_gram ?? 0 ;
        $repair->wage = $request->wage ?? null;
        $repair->bhori = $total['vori'];
        $repair->ana = $total['ana'];
        $repair->roti = $total['roti'];
        $repair->point = $total['point'];
        $repair->added_bhori = $total_added['vori'];
        $repair->added_ana = $total_added['ana'];
        $repair->added_roti = $total_added['roti'];
        $repair->added_point = $total_added['point'];
        $repair->total = $request->total ?? null ;
        $repair->paid = $request->paid ?? null ;
        $repair->due = $request->due ?? null ;
        $repair->due_payment_date = $request->due_payment_date ?? null ;
        $repair->order_date = $request->order_date ?? null ;
        $repair->receive_date = $request->receive_date ?? null ;
        $repair->details = $request->details ?? null ;
        $repair->prev_photo = $path1;
        $repair->save();

        return redirect()->route('repair.manage.edit', $repair->id)->with('message', 'Product For Repair Added Successfully 🙂');
    }

   public function repair_manage()
   {
    $users= User::latest()->get();
    $categories = ProductCategory::select(['id', 'category_name'])->get();
    $repairs = Repair::latest()->get();

    return view('admin.repair.manage',compact('users','categories','repairs'));
   }

   public function repair_manage_edit($id)
   {
    $repair = Repair::find($id);
    $users = User::whereHas('role', function($query) {
        $query->where('role_name', 'karigor');
    })->get();
    $categories = ProductCategory::select(['id', 'category_name'])->get();

    return view('admin.repair.manageEdit',compact('users','repair','categories'));
   }

   public function repair_karigor_store(Request $request)
   {
    dd($request);

   }
}
