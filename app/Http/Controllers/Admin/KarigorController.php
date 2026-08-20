<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Shop;
use App\Models\Warehouse;
use App\Models\Stock;
use App\Models\Sell;
use App\Models\ProductCategory;
use App\Models\User;
use App\Models\KarigorProduct;
use App\Models\Karigor;
use App\Models\Repair;

require_once app_path('helpers.php');

class KarigorController extends Controller
{
    public function karigor_index()
    {
        $users = User::where('role_id', 5)
            ->orWhereHas('role', function($query) {
                $query->where('role_name', 'karigor');
            })
            ->latest()
            ->get();

        return view('admin.karigor.karigor', compact('users'));
    }

    public function karigor_create()
    {
        return view('admin.karigor.karigorCreate');
    }

    public function karigor_store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'phone'     => 'nullable|string|max:20',
            'email'     => 'nullable|email|max:255',
            'address'   => 'nullable|string',
            'password'  => 'nullable|string|min:6',
            'image'     => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:2048',
        ]);

        $role = \App\Models\Role::where('role_name', 'karigor')->first();
        $role_id = $role ? $role->id : 5;

        if ($request->hasFile('image')) {
            $imageName = 'user_' . time() . '.' . $request->image->extension();
            $request->image->move(public_path('user'), $imageName);
        } else {
            $imageName = 'default_user.jpg';
        }

        $user = new User();
        $user->role_id   = $role_id;
        $user->name      = $request->name;
        $user->last_name = $request->last_name;
        $user->phone     = $request->phone;
        $user->email     = $request->email ?: ($request->phone ? $request->phone . '@karigor.com' : 'karigor_' . time() . '@madobi.com');
        $user->address   = $request->address;
        $user->password  = \Illuminate\Support\Facades\Hash::make($request->password ?: '12345678');
        $user->image     = $imageName;
        $user->is_active = true;
        $user->save();

        $karigor = new Karigor();
        $karigor->karigor_id = $user->id;
        $karigor->save();

        return redirect()->route('karigor.index')->with('message', 'নতুন কারিগর সফলভাবে তৈরি করা হয়েছে 🙂');
    }

    public function karigor_edit($id)
    {
        $user = User::find($id);
        if (!$user) {
            $karigor = Karigor::find($id);
            if ($karigor && $karigor->user) {
                $user = $karigor->user;
            }
        }

        if (!$user) {
            return redirect()->route('karigor.index')->with('error', 'কারিগর পাওয়া যায়নি!');
        }

        $karigor = Karigor::where('karigor_id', $user->id)->first();
        if (!$karigor) {
            $karigor = new Karigor();
            $karigor->karigor_id = $user->id;
            $karigor->save();
        }

        return view('admin.karigor.karigorEdit', compact('karigor', 'user'));
    }

    public function karigor_update(Request $request)
    {
        $request->validate([
            'k_id'      => 'required',
            'name'      => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'phone'     => 'nullable|string|max:20',
            'email'     => 'nullable|email|max:255',
            'address'   => 'nullable|string',
            'password'  => 'nullable|string|min:6',
            'image'     => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:2048',
        ]);

        $karigor = Karigor::with('user')->find($request->k_id);
        if ($karigor && $karigor->user) {
            $user = $karigor->user;
        } else {
            $user = User::findOrFail($request->k_id);
        }

        $user->name      = $request->name;
        $user->last_name = $request->last_name;
        $user->phone     = $request->phone;
        if ($request->filled('email')) {
            $user->email = $request->email;
        }
        $user->address   = $request->address;

        if ($request->filled('password')) {
            $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
        }

        if ($request->hasFile('image')) {
            $imageName = 'user_' . time() . '.' . $request->image->extension();
            $request->image->move(public_path('user'), $imageName);
            $user->image = $imageName;
        }

        $user->save();

        return redirect()->route('karigor.index')->with('message', 'কারিগর তথ্য সফলভাবে আপডেট করা হয়েছে 🙂');
    }

    public function karigor_delete(Request $request)
    {
        $id = $request->user_id ?? $request->karigor_id;
        $user = User::find($id);
        if (!$user) {
            $karigor = Karigor::find($id);
            if ($karigor && $karigor->user) {
                $user = $karigor->user;
            }
        }

        if ($user) {
            Karigor::where('karigor_id', $user->id)->delete();
            $user->delete();
        }

        return redirect()->route('karigor.index')->with('message', 'কারিগর সফলভাবে মুছে ফেলা হয়েছে 🗑️');
    }

    public function karigor_stock(Request $request)
    {
        $users = User::whereHas('role', function($query) {
            $query->where('role_name', 'karigor');
        })->orWhere('role_id', 5)->latest()->get();

        $categories = ProductCategory::latest()->get();

        $categoryId = $request->get('category_id', 'all');
        $karat = $request->get('karat', 'all');
        $jobStatus = $request->get('job_status', 'all');
        $karigorId = $request->get('karigor_id', 'all');

        $query = \App\Models\Purchase::with([
            'invoice',
            'productCategory',
            'product',
            'user',
            'activeKarigorJob.karigor',
            'karigorJobs.karigor',
            'locationHistories.transferredBy',
            'locationHistories.karigor'
        ])
        ->whereIn('location', ['is_karigor', 'in_progress']);

        if ($categoryId !== 'all') {
            $query->where('category_id', $categoryId);
        }

        if ($karat !== 'all') {
            $query->where('karat', $karat);
        }

        if ($jobStatus !== 'all') {
            if ($jobStatus === 'unassigned') {
                $query->whereDoesntHave('activeKarigorJob', function($q) {
                    $q->whereIn('status', ['in_progress', 'completed']);
                });
            } else {
                $query->whereHas('activeKarigorJob', function($q) use ($jobStatus) {
                    $q->where('status', $jobStatus);
                });
            }
        }

        if ($karigorId !== 'all') {
            $query->whereHas('activeKarigorJob', function($q) use ($karigorId) {
                $q->where('karigor_id', $karigorId);
            });
        }

        $purchases = $query->latest()->get();

        return view('admin.karigor.karigorStock', compact(
            'users',
            'categories',
            'purchases',
            'categoryId',
            'karat',
            'jobStatus',
            'karigorId'
        ));
    }

   public function karigor_product()
   {
    $users = User::whereHas('role', function($query) {
        $query->where('role_name', 'karigor');
    })->get();
    
    $karigor_products = KarigorProduct::whereNull('repair_id')->latest()->get();
    
    // $products = Product::latest()->get();
    $converted_categories = ProductCategory::latest()->get();

    $categories = DB::table('product_categories')
    ->join('warehouses', 'product_categories.id', '=', 'warehouses.category_id')
    ->select('product_categories.*')
    ->distinct()
    ->get();
    
    return view('admin.karigor.k_product',compact('users','karigor_products','categories','converted_categories'));
   }

   public function karigor_product_store(Request $request)
   {
        $request_gold = [
            'vori' => $request->bhori ?? 0,
            'ana' => $request->ana ?? 0,
            'roti' => $request->roti ?? 0,
            'point' => $request->point ?? 0,
        ];
        $calculate_request_gold = calculateTotalGold($request_gold);
       // Store Karigor product details
       $karigor_products = new KarigorProduct;
       $karigor_products->user_id = $request->user_id;
       $karigor_products->category_id = $request->category_id;
       $karigor_products->product_id = $request->product_id;
       $karigor_products->bhori = $calculate_request_gold['vori'];
       $karigor_products->ana = $calculate_request_gold['ana'];
       $karigor_products->roti = $calculate_request_gold['roti'];
       $karigor_products->point = $calculate_request_gold['point'];
       $karigor_products->gram = $request->gram;
       $karigor_products->converted_category_id = $request->converted_category_id;
       $karigor_products->converted_product_id = $request->converted_product_id;
       $karigor_products->order_date = $request->order_date;
       $karigor_products->receive_date = $request->receive_date;
       $karigor_products->details = $request->details;
       $karigor_products->status = 'on-process';
       $karigor_products->save();
   
       
   
       // Adjust Karigor's stock
       $karigor_stocks = Karigor::where('karigor_id', $karigor_products->user_id)->get();
       $stockToUpdate = $karigor_stocks->firstWhere('category_id', $request->category_id);
   
       if ($stockToUpdate) {
           $stockToUpdate_gold = [
               'vori' => $stockToUpdate->bhori ?? 0,
               'ana' => $stockToUpdate->ana ?? 0,
               'roti' => $stockToUpdate->roti ?? 0,
               'point' => $stockToUpdate->point ?? 0,
           ];
           $total = addGold($stockToUpdate_gold, $calculate_request_gold);
   
           $stockToUpdate->bhori = $total['vori'];
           $stockToUpdate->ana = $total['ana'];
           $stockToUpdate->roti = $total['roti'];
           $stockToUpdate->point = $total['point'];
           $stockToUpdate->gram = ($stockToUpdate->gram ?? 0) + $request->gram;
           $stockToUpdate->save();
       } else {
           $stockToUpdate = $karigor_stocks->firstWhere('category_id', null);
   
           if ($stockToUpdate) {
               $stockToUpdate->category_id = $request->category_id;
               $stockToUpdate->bhori = $calculate_request_gold['vori'];
               $stockToUpdate->ana = $calculate_request_gold['ana'];
               $stockToUpdate->roti = $calculate_request_gold['roti'];
               $stockToUpdate->point = $calculate_request_gold['point'];
               $stockToUpdate->gram = $request->gram;
               $stockToUpdate->save();
           } else {
               Log::error('No available record to update for karigor_id: ' . $karigor_products->user_id);
           }
       }
   
       // Fetch the warehouse and main stock
       $main_stock_warehouse = Stock::where('category_id', $request->category_id)
                                    ->where('product_id', $request->product_id)
                                    ->latest()
                                    ->first();
       $warehouse = Warehouse::where('category_id', $request->category_id)
                             ->where('product_id', $request->product_id)
                             ->latest()
                             ->first();
   
       if (!$main_stock_warehouse) {
           $main_stock_warehouse = new Stock;
           $main_stock_warehouse->product_id = $request->product_id;
           $main_stock_warehouse->category_id = $request->category_id;
           $main_stock_warehouse->bhori = 0;
           $main_stock_warehouse->ana = 0;
           $main_stock_warehouse->roti = 0;
           $main_stock_warehouse->point = 0;
           $main_stock_warehouse->qty = 0;
           $main_stock_warehouse->gram = 0;
           $main_stock_warehouse->save();
       }
   
       if (!$warehouse) {
           $warehouse = new Warehouse;
           $warehouse->stock_id = $main_stock_warehouse->id;
           $warehouse->product_id = $request->product_id;
           $warehouse->category_id = $request->category_id;
           $warehouse->bhori = 0;
           $warehouse->ana = 0;
           $warehouse->roti = 0;
           $warehouse->point = 0;
           $warehouse->save();
       }
   
       $current_main_warehouse_stock = [
           'vori' => $main_stock_warehouse->bhori ?? 0,
           'ana' => $main_stock_warehouse->ana ?? 0,
           'roti' => $main_stock_warehouse->roti ?? 0,
           'point' => $main_stock_warehouse->point ?? 0,
       ];
       $current_warehouse_stock = [
           'vori' => $warehouse->bhori ?? 0,
           'ana' => $warehouse->ana ?? 0,
           'roti' => $warehouse->roti ?? 0,
           'point' => $warehouse->point ?? 0,
       ];
   
       $updated_main_warehouse_stock = subGold($current_main_warehouse_stock, $calculate_request_gold);
       $updated_warehouse_stock = subGold($current_warehouse_stock, $calculate_request_gold);
   
       // Update warehouse and main stock
       $main_stock_warehouse->bhori = $updated_main_warehouse_stock['vori'];
       $main_stock_warehouse->ana = $updated_main_warehouse_stock['ana'];
       $main_stock_warehouse->roti = $updated_main_warehouse_stock['roti'];
       $main_stock_warehouse->point = $updated_main_warehouse_stock['point'];
       $main_stock_warehouse->qty -= $karigor_products->qty;
       $main_stock_warehouse->gram -= $karigor_products->gram;
       $main_stock_warehouse->save();
   
       $warehouse->bhori = $updated_warehouse_stock['vori'];
       $warehouse->ana = $updated_warehouse_stock['ana'];
       $warehouse->roti = $updated_warehouse_stock['roti'];
       $warehouse->point = $updated_warehouse_stock['point'];
       $warehouse->save();
   
       return redirect()->route('karigor.product')->with('message', 'Product Assigned to Karigor Successfully');
   }
   

  
   public function karigor_product_status_update(Request $request)
   {
    // dd($request);

       if ($request->repair_id) {
           $repair = Repair::find($request->repair_id);
           $repair->status = $request->status;
           $repair->save();
   
           $karigor = Karigor::where('karigor_id', $repair->karigor_id)->latest()->first();
   
           $k_stock = [
               'vori' => $karigor->bhori ?? 0,
               'ana' => $karigor->ana ?? 0,
               'roti' => $karigor->roti ?? 0,
               'point' => $karigor->point ?? 0,
           ];
   
           $sub_stock = [
               'vori' => $repair->added_bhori ?? 0,
               'ana' => $repair->added_ana ?? 0,
               'roti' => $repair->added_roti ?? 0,
               'point' => $repair->added_point ?? 0,
           ];
   
           if ($request->status == 'received') {
               $remaining_k_stock = subGold($k_stock, $sub_stock);
               $karigor->gram = $karigor->gram - $repair->gram;
           } else if ($request->status == 'on-process') {
               $remaining_k_stock = addGold($k_stock, $sub_stock);
               $karigor->gram = $karigor->gram + $repair->gram;
           }
   
           $karigor->bhori = $remaining_k_stock['vori'];
           $karigor->ana = $remaining_k_stock['ana'];
           $karigor->roti = $remaining_k_stock['roti'];
           $karigor->point = $remaining_k_stock['point'];
   
           $karigor->save();
   
           $kar_pro = KarigorProduct::where('repair_id', $request->repair_id)->latest()->first();
           $kar_pro->status = $request->status;
           $kar_pro->save();
   
           return redirect()->back()->with('message', 'Status Changed Successfully');
       }
   
       $karigor_products = KarigorProduct::find($request->kp_id);
       $previous_status = $karigor_products->status;
       $karigor_products->status = $request->status;
       $karigor_products->save();
   
       $sub_stock = [
           'vori' => $karigor_products->bhori ?? 0,
           'ana' => $karigor_products->ana ?? 0,
           'roti' => $karigor_products->roti ?? 0,
           'point' => $karigor_products->point ?? 0,
       ];
   
       // Fetch the shop stock
       $shop = Shop::where('category_id', $karigor_products->converted_category_id)
                   ->where('product_id', $karigor_products->converted_product_id)
                   ->where('status', 'from_karigor')
                   ->latest()
                   ->first();
       $main_stock_shop = Stock::where('category_id', $karigor_products->converted_category_id)
                               ->where('product_id', $karigor_products->converted_product_id)
                               ->latest()
                               ->first();
   
       // Ensure shop and main_stock_shop exist
       if (!$main_stock_shop) {
           $main_stock_shop = new Stock;
           $main_stock_shop->product_id = $karigor_products->converted_product_id;
           $main_stock_shop->category_id = $karigor_products->converted_category_id;
           $main_stock_shop->bhori = 0;
           $main_stock_shop->ana = 0;
           $main_stock_shop->roti = 0;
           $main_stock_shop->point = 0;
           $main_stock_shop->qty = 0;
           $main_stock_shop->gram = 0;
           $main_stock_shop->location = 'is_shop';
           $main_stock_shop->save();
       }
   
       if (!$shop) {
           $shop = new Shop;
           $shop->stock_id = $main_stock_shop->id;
           $shop->product_id = $karigor_products->converted_product_id;
           $shop->category_id = $karigor_products->converted_category_id;
           $shop->bhori = 0;
           $shop->ana = 0;
           $shop->roti = 0;
           $shop->point = 0;
           $shop->status = 'from_karigor';
           $shop->qty = 0;
           $shop->gram = 0;
           $shop->save();
       }
   
       // Fetch Karigor stock
       $karigor_stock = Karigor::where('karigor_id', $karigor_products->user_id)
                               ->where('category_id', $karigor_products->category_id)
                               ->latest()
                               ->first();
   
       if ($karigor_products->status == 'received' && $previous_status != 'received') {
           // Transition from 'on-process' to 'received'
           $karigor_stock_gold = [
               'vori' => $karigor_stock->bhori ?? 0,
               'ana' => $karigor_stock->ana ?? 0,
               'roti' => $karigor_stock->roti ?? 0,
               'point' => $karigor_stock->point ?? 0,
           ];
           $updated_karigor_stock = subGold($karigor_stock_gold, $sub_stock);
   
           // Update Karigor stock
           $karigor_stock->bhori = $updated_karigor_stock['vori'];
           $karigor_stock->ana = $updated_karigor_stock['ana'];
           $karigor_stock->roti = $updated_karigor_stock['roti'];
           $karigor_stock->point = $updated_karigor_stock['point'];
           $karigor_stock->gram -= $karigor_products->gram;
           $karigor_stock->save();
   
           // Update shop and main stock
           $shop_gold = [
               'vori' => $shop->bhori ?? 0,
               'ana' => $shop->ana ?? 0,
               'roti' => $shop->roti ?? 0,
               'point' => $shop->point ?? 0,
           ];
           $main_stock_shop_gold = [
               'vori' => $main_stock_shop->bhori ?? 0,
               'ana' => $main_stock_shop->ana ?? 0,
               'roti' => $main_stock_shop->roti ?? 0,
               'point' => $main_stock_shop->point ?? 0,
           ];
   
           $updated_shop_stock = addGold($shop_gold, $sub_stock);
           $updated_main_shop_stock = addGold($main_stock_shop_gold, $sub_stock);
   
           $shop->bhori = $updated_shop_stock['vori'];
           $shop->ana = $updated_shop_stock['ana'];
           $shop->roti = $updated_shop_stock['roti'];
           $shop->point = $updated_shop_stock['point'];
           $shop->qty += $karigor_products->qty;
           $shop->gram += $karigor_products->gram;
           $shop->save();
   
           $main_stock_shop->bhori = $updated_main_shop_stock['vori'];
           $main_stock_shop->ana = $updated_main_shop_stock['ana'];
           $main_stock_shop->roti = $updated_main_shop_stock['roti'];
           $main_stock_shop->point = $updated_main_shop_stock['point'];
           $main_stock_shop->qty += $karigor_products->qty;
           $main_stock_shop->gram += $karigor_products->gram;
           $main_stock_shop->location = 'is_shop';
           $main_stock_shop->save();
   
       } elseif ($karigor_products->status == 'on-process' && $previous_status == 'received') {
           // Transition from 'received' to 'on-process'
           $karigor_stock_gold = [
               'vori' => $karigor_stock->bhori ?? 0,
               'ana' => $karigor_stock->ana ?? 0,
               'roti' => $karigor_stock->roti ?? 0,
               'point' => $karigor_stock->point ?? 0,
           ];
           $updated_karigor_stock = addGold($karigor_stock_gold, $sub_stock);
   
           // Update Karigor stock
           $karigor_stock->bhori = $updated_karigor_stock['vori'];
           $karigor_stock->ana = $updated_karigor_stock['ana'];
           $karigor_stock->roti = $updated_karigor_stock['roti'];
           $karigor_stock->point = $updated_karigor_stock['point'];
           $karigor_stock->gram += $karigor_products->gram;
           $karigor_stock->save();
   
           // Update shop and main stock
           $shop_gold = [
               'vori' => $shop->bhori ?? 0,
               'ana' => $shop->ana ?? 0,
               'roti' => $shop->roti ?? 0,
               'point' => $shop->point ?? 0,
           ];
           $main_stock_shop_gold = [
               'vori' => $main_stock_shop->bhori ?? 0,
               'ana' => $main_stock_shop->ana ?? 0,
               'roti' => $main_stock_shop->roti ?? 0,
               'point' => $main_stock_shop->point ?? 0,
           ];
   
           $updated_shop_stock = subGold($shop_gold, $sub_stock);
           $updated_main_shop_stock = subGold($main_stock_shop_gold, $sub_stock);
   
           $shop->bhori = $updated_shop_stock['vori'];
           $shop->ana = $updated_shop_stock['ana'];
           $shop->roti = $updated_shop_stock['roti'];
           $shop->point = $updated_shop_stock['point'];
           $shop->qty -= $karigor_products->qty;
           $shop->gram -= $karigor_products->gram;
           $shop->save();
   
           $main_stock_shop->bhori = $updated_main_shop_stock['vori'];
           $main_stock_shop->ana = $updated_main_shop_stock['ana'];
           $main_stock_shop->roti = $updated_main_shop_stock['roti'];
           $main_stock_shop->point = $updated_main_shop_stock['point'];
           $main_stock_shop->qty -= $karigor_products->qty;
           $main_stock_shop->gram -= $karigor_products->gram;
           $main_stock_shop->save();
       }
   
       return redirect()->route('karigor.product')->with('message', 'Status Changed Successfully');
   }
   


   public function karigor_product_edit($id)
   {
    $karigors = User::latest()->get();
    $karigor_product = KarigorProduct::find($id);
    $products = Product::latest()->get();
    $categories = ProductCategory::latest()->get();

    $warehouse_category = DB::table('product_categories')
    ->join('warehouses', 'product_categories.id', '=', 'warehouses.category_id')
    ->select('product_categories.*')
    ->distinct()
    ->get();
    return view('admin.karigor.karigorProductEdit',compact('warehouse_category','karigors','karigor_product','products','categories'));
   }

   public function karigor_product_update(Request $request){
    if($request->karigor_type == "new_karigor")
    {
        $karigor = new Karigor;
        $karigor->name = $request->name;
        $karigor->phone = $request->phone;
        $karigor->address = $request->address;
        $karigor->details = $request->details;
        $karigor->save();
        $k_id = $karigor->id;
    }elseif($request->karigor_type == "old_karigor"){
        $k_id = $request->karigor_id;
    }
    $karigor_products = KarigorProduct::find($request->id);
    $karigor_products->karigor_id = $k_id;
    $karigor_products->category_id = $request->category;
    $karigor_products->product_id = $request->product;
    $karigor_products->karat = $request->karat;
    $karigor_products->bhori = $request->bhori;
    $karigor_products->ana = $request->ana;
    $karigor_products->roti = $request->roti;

    $karigor_products->converted_category_id = $request->converted_category_id;
    $karigor_products->converted_product_id = $request->converted_product_id;
    $karigor_products->order_date = $request->order_date;
    $karigor_products->receive_date = $request->receive_date;
    $karigor_products->qty = $request->qty;
    $karigor_products->status = $request->status;
    $karigor_products->save();


    return redirect()->route('karigor.product')->with('message', 'Karigor Product Updated Successfully');
   }
}
