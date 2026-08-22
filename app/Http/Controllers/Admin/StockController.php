<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Shop;
use App\Models\Warehouse;
use App\Models\Stock;
use App\Models\ProductCategory;

require_once app_path('helpers.php');

class StockController extends Controller
{
   public function stock_index()
   {
       $stocks = Stock::latest()->get();
       return view('admin.stock.mainStock', compact('stocks'));
   }


   public function shop_stock_index()
   {
    $shops = Shop::latest()->get();
    return view('admin.stock.shopStock',compact('shops'));
   }


   public function warehouse_stock_index()
   {
    $warehouses = Warehouse::latest()->get();
    return view('admin.stock.warehouseStock',compact('warehouses'));
   }

   public function hold_stock_index()
   {
       $purchases = \App\Models\Purchase::with(['invoice', 'productCategory', 'product', 'user'])
           ->where('location', 'is_hold')
           ->latest()
           ->get();
       return view('admin.stock.stock', compact('purchases'));
   }

   public function stock_create()
   {
    $categories=ProductCategory::get();
    
    return view('admin.stock.createStock',compact('categories'));
   }

   public function stock_store(Request $request)
   {
      // dd($request);
      $request_gold = [
         'vori' => $request->bhori ?? 0,
         'ana' => $request->ana ?? 0,
         'roti' => $request->roti ?? 0,
         'point' => $request->point ?? 0,
     ];
     $total = calculateTotalGold($request_gold);


     $main_stock = Stock::where('category_id', $request->category_id)
                        ->where('product_id', $request->product_id)
                        ->latest()
                        ->first();
      
      if($main_stock){
         $main_stock_gold = [
            'vori' => $main_stock->bhori ?? 0,
            'ana' => $main_stock->ana ?? 0,
            'roti' => $main_stock->roti ?? 0,
            'point' => $main_stock->point ?? 0,
        ];

        $main_stock_updated_gold = addGold($main_stock_gold,$total);

        $main_stock->bhori = $main_stock_updated_gold['vori'];
        $main_stock->ana = $main_stock_updated_gold['ana'];
        $main_stock->roti = $main_stock_updated_gold['roti'];
        $main_stock->point = $main_stock_updated_gold['point'];
        $main_stock->gram = $main_stock->gram ?? 0 + $request->gram ?? 0;
        $main_stock->qty = $main_stock->qty ?? 0 + $request->qtr ?? 0;
        $main_stock->karat = $request->karat ?? $main_stock->karat;
        $main_stock->unit_price = $request->unit_price ?? $main_stock->unit_price;
        $main_stock->location = $request->location;
        $main_stock->save();

        $stock_id = $main_stock->id;
      }else{
         $main_stock = new Stock;
         $main_stock->category_id = $request->category_id ;
         $main_stock->product_id = $request->product_id;
         $main_stock->bhori = $total['vori'];
         $main_stock->ana = $total['ana'];
         $main_stock->roti = $total['roti'];
         $main_stock->point = $total['point'];
         $main_stock->gram = $request->gram ?? 0;
         $main_stock->qty = $request->qtr ?? 0;
         $main_stock->karat = $request->karat ?? 0;
         $main_stock->unit_price = $request->unit_price ?? 0;
         $main_stock->location = $request->location;
         $main_stock->save();
         $stock_id = $main_stock->id;
      }
      
      if($request->location == "is_shop"){

         $prev_shop_stock = Shop::where('product_id',$request->product_id)->where('category_id',$request->category_id)->latest()->first();
         if($prev_shop_stock)
         {
             $prevGold = [
                 'vori' => $prev_shop_stock->bhori,
                 'ana' => $prev_shop_stock->ana,
                 'roti' => $prev_shop_stock->roti,
                 'point' => $prev_shop_stock->point,
             ];

             $updateQty = $prev_shop_stock->qty ?? 0 + $request->qtr ?? 0;
             $updateGram = $prev_shop_stock->gram ?? 0 + $request->gram ?? 0;

             $total_gold = addGold($prevGold,$total);

             $prev_shop_stock->stock_id = $stock_id;
             $prev_shop_stock->karat = $request->karat;
             $prev_shop_stock->ana = $total_gold['ana'];
             $prev_shop_stock->bhori = $total_gold['vori'];
             $prev_shop_stock->roti = $total_gold['roti'];
             $prev_shop_stock->point = $total_gold['point'];
             $prev_shop_stock->qty = $updateQty;
             $prev_shop_stock->gram = $updateGram;
             $prev_shop_stock->save();
         }else{
             $shop_table = new Shop;
             $shop_table->stock_id = $stock_id;
             $shop_table->product_id  = $request->product_id;
             $shop_table->category_id = $request->category_id;
             $shop_table->karat = $request->karat;
             $shop_table->ana = $total['ana'];
             $shop_table->bhori = $total['vori'];
             $shop_table->roti = $total['roti'];
             $shop_table->point = $total['point'];
             $shop_table->qty =  $request->qtr ?? 0;
             $shop_table->gram =  $request->gram;
             $shop_table->save();
         }
         

     }elseif($request->location == "is_warehouse")
     {
         $prev_ware_stock = Warehouse::where('product_id',$request->product_id)->where('category_id',$request->category_id)->latest()->first();
         if($prev_ware_stock)
         {
             $prevGold = [
                 'vori' => $prev_ware_stock->bhori,
                 'ana' => $prev_ware_stock->ana,
                 'roti' => $prev_ware_stock->roti,
                 'point' => $prev_ware_stock->point,
             ];

             $updateQty = $prev_ware_stock->qty ?? 0 + $request->qtr ?? 0;

             $updateGram = $prev_ware_stock->gram ?? 0 + $request->gram ?? 0;

             $total_gold = addGold($prevGold,$total); 

             $prev_ware_stock->stock_id = $stock_id;
             $prev_ware_stock->karat = $request->karat;
             $prev_ware_stock->ana = $total_gold['ana'];
             $prev_ware_stock->bhori = $total_gold['vori'];
             $prev_ware_stock->roti = $total_gold['roti'];
             $prev_ware_stock->point = $total_gold['point'];
             $prev_ware_stock->gram = $updateGram;
             $prev_ware_stock->qty = $updateQty;
             $prev_ware_stock->save();
         }else{

             $warehouse_table = new Warehouse;
             $warehouse_table->stock_id = $stock_id;
             $warehouse_table->product_id  = $request->product_id;
             $warehouse_table->category_id = $request->category_id;
             $warehouse_table->karat = $request->karat;
             $warehouse_table->ana = $total['ana'];
             $warehouse_table->bhori = $total['vori'];
             $warehouse_table->roti = $total['roti'];
             $warehouse_table->point = $total['point'];
             $warehouse_table->gram = $request->gram ?? 0;
             $warehouse_table->qty = $request->qtr ?? 0;
             $warehouse_table->save();
         }
      }

      return redirect()->route('stock.index')->with('message', 'Stock Created Successfully 🙂');
   }


   public function stock_edit($id)
   {
      $stock = Stock::find($id);
      $categories=ProductCategory::get();
    
      return view('admin.stock.editStock',compact('stock','categories'));
   }

   public function stock_update(Request $request)
   {
      $request_gold = [
         'vori' => $request->bhori ?? 0,
         'ana' => $request->ana ?? 0,
         'roti' => $request->roti ?? 0,
         'point' => $request->point ?? 0,
     ];
     $total = calculateTotalGold($request_gold);
 
     $main_stock = Stock::findOrFail($request->stock_id);
 
     // Store original quantities for adjustments
     $original_main_stock_gold = [
         'vori' => $main_stock->bhori ?? 0,
         'ana' => $main_stock->ana ?? 0,
         'roti' => $main_stock->roti ?? 0,
         'point' => $main_stock->point ?? 0,
     ];
 
     // Adjust main stock quantities
     $main_stock->bhori = $total['vori'];
     $main_stock->ana = $total['ana'];
     $main_stock->roti = $total['roti'];
     $main_stock->point = $total['point'];
     $main_stock->gram = $request->gram;
     $main_stock->qty = $request->qtr;
     $main_stock->karat = $request->karat;
     $main_stock->unit_price = $request->unit_price;
     $main_stock->location = $request->location;
     $main_stock->save();
 
     // Adjust previous shop stock if location is shop
     if ($request->location == "is_shop") {
         $prev_shop_stock = Shop::where('stock_id', $request->stock_id)
             ->latest()
             ->first();
 
         if ($prev_shop_stock) {
             $prevGold = [
                 'vori' => $prev_shop_stock->bhori,
                 'ana' => $prev_shop_stock->ana,
                 'roti' => $prev_shop_stock->roti,
                 'point' => $prev_shop_stock->point,
             ];
 
             // Adjust quantities by subtracting the original main stock quantities
             $prevGoldAdjusted = subGold($prevGold, $original_main_stock_gold);
 
             // Update with new quantities
             $prevGoldUpdated = addGold($prevGoldAdjusted, $total);
 
             $prev_shop_stock->stock_id = $main_stock->id;
             $prev_shop_stock->karat = $request->karat;
             $prev_shop_stock->ana = $prevGoldUpdated['ana'];
             $prev_shop_stock->bhori = $prevGoldUpdated['vori'];
             $prev_shop_stock->roti = $prevGoldUpdated['roti'];
             $prev_shop_stock->point = $prevGoldUpdated['point'];
             $prev_shop_stock->qty = $request->qtr;
             $prev_shop_stock->gram = $request->gram;
             $prev_shop_stock->save();
         } else {
             $shop_table = new Shop;
             $shop_table->stock_id = $main_stock->id;
             $shop_table->product_id = $request->product_id;
             $shop_table->category_id = $request->category_id;
             $shop_table->karat = $request->karat;
             $shop_table->ana = $total['ana'];
             $shop_table->bhori = $total['vori'];
             $shop_table->roti = $total['roti'];
             $shop_table->point = $total['point'];
             $shop_table->qty = $request->qtr;
             $shop_table->gram = $request->gram;
             $shop_table->save();
         }
     }
 
     // Adjust previous warehouse stock if location is warehouse
     if ($request->location == "is_warehouse") {
         $prev_ware_stock = Warehouse::where('stock_id', $request->stock_id)
             ->latest()
             ->first();
 
         if ($prev_ware_stock) {
             $prevGold = [
                 'vori' => $prev_ware_stock->bhori,
                 'ana' => $prev_ware_stock->ana,
                 'roti' => $prev_ware_stock->roti,
                 'point' => $prev_ware_stock->point,
             ];
 
             // Adjust quantities by subtracting the original main stock quantities
             $prevGoldAdjusted = subGold($prevGold, $original_main_stock_gold);
 
             // Update with new quantities
             $prevGoldUpdated = addGold($prevGoldAdjusted, $total);
 
             $prev_ware_stock->stock_id = $main_stock->id;
             $prev_ware_stock->karat = $request->karat;
             $prev_ware_stock->ana = $prevGoldUpdated['ana'];
             $prev_ware_stock->bhori = $prevGoldUpdated['vori'];
             $prev_ware_stock->roti = $prevGoldUpdated['roti'];
             $prev_ware_stock->point = $prevGoldUpdated['point'];
             $prev_ware_stock->qty = $request->qtr;
             $prev_ware_stock->gram = $request->gram;
             $prev_ware_stock->save();
         } else {
             $warehouse_table = new Warehouse;
             $warehouse_table->stock_id = $main_stock->id;
             $warehouse_table->product_id = $request->product_id;
             $warehouse_table->category_id = $request->category_id;
             $warehouse_table->karat = $request->karat;
             $warehouse_table->ana = $total['ana'];
             $warehouse_table->bhori = $total['vori'];
             $warehouse_table->roti = $total['roti'];
             $warehouse_table->point = $total['point'];
             $warehouse_table->qty = $request->qtr;
             $warehouse_table->gram = $request->gram;
             $warehouse_table->save();
         }
     }
 
     return redirect()->route('stock.index')->with('message', 'Stock Updated Successfully 🙂');
    }
    public function stock_delete(Request $request){

        if ($request->stock_id) {
            $stock = Stock::find($request->stock_id);
            $shop = Shop::where('category_id',$stock->category_id)->where('product_id',$stock->product_id)->first();
            $warehouse = Warehouse::where('category_id',$stock->category_id)->where('product_id',$stock->product_id)->first();
            if ($stock) {
                $stock->delete();
            }
           
            if($shop){
                $shop->delete();
            }
            if($warehouse){
                $warehouse->delete();
            }
        }
        if ($request->shop_id) {
            $shop = Shop::find($request->shop_id);
            // $stock = Stock::where('category_id',$shop->category_id)->where('product_id',$shop->product_id)->first();

            if($shop){
                $shop->delete();
            }
            // if ($stock) {
            //     $stock->delete();
            // }

        }
        if ($request->warehouse_id) {
            $warehouse = Warehouse::find($request->warehouse_id);
            // $stock = Stock::where('category_id',$shop->category_id)->where('product_id',$shop->product_id)->first();

            if($warehouse){
                $warehouse->delete();
            }
            // if ($stock) {
            //     $stock->delete();
            // }

        }
        
       
        return redirect()->back()->with('message', 'Stock Deleted Successfully 🙂');
    }

    public function send_to_shop(Request $request)
    {
        $purchase = \App\Models\Purchase::findOrFail($request->purchase_id);
        $fromLocation = $purchase->location;

        // Update the purchase location
        $purchase->location = 'is_shop';
        $purchase->save();

        \App\Models\PurchaseLocationHistory::create([
            'purchase_id'    => $purchase->id,
            'from_location'  => $fromLocation,
            'to_location'    => 'is_shop',
            'transferred_by' => auth()->id(),
            'note'           => 'Sent to Shop Stock',
        ]);

        // Gold amount from purchase
        $gold = [
            'vori' => $purchase->bhori ?? 0,
            'ana'  => $purchase->ana   ?? 0,
            'roti' => $purchase->roti  ?? 0,
            'point'=> $purchase->point ?? 0,
        ];

        $prev = Shop::where('product_id', $purchase->product_id)
                    ->where('category_id', $purchase->category_id)
                    ->latest()->first();

        if ($prev) {
            $prevGold = ['vori' => $prev->bhori, 'ana' => $prev->ana, 'roti' => $prev->roti, 'point' => $prev->point];
            $updated  = addGold($prevGold, $gold);
            $prev->karat  = $purchase->karat;
            $prev->bhori  = $updated['vori'];
            $prev->ana    = $updated['ana'];
            $prev->roti   = $updated['roti'];
            $prev->point  = $updated['point'];
            $prev->gram   = ($prev->gram ?? 0) + ($purchase->gram ?? 0);
            $prev->qty    = ($prev->qty  ?? 0) + 1;
            $prev->save();
        } else {
            $shop = new Shop;
            $shop->product_id  = $purchase->product_id;
            $shop->category_id = $purchase->category_id;
            $shop->karat       = $purchase->karat;
            $shop->bhori       = $gold['vori'];
            $shop->ana         = $gold['ana'];
            $shop->roti        = $gold['roti'];
            $shop->point       = $gold['point'];
            $shop->gram        = $purchase->gram ?? 0;
            $shop->qty         = 1;
            $shop->save();
        }

        return redirect()->back()->with('message', 'পণ্যটি সফলভাবে শপে পাঠানো হয়েছে 🏪');
    }

    public function send_to_warehouse(Request $request)
    {
        $purchase = \App\Models\Purchase::findOrFail($request->purchase_id);
        $fromLocation = $purchase->location;

        // Update the purchase location
        $purchase->location = 'is_warehouse';
        $purchase->save();

        \App\Models\PurchaseLocationHistory::create([
            'purchase_id'    => $purchase->id,
            'from_location'  => $fromLocation,
            'to_location'    => 'is_warehouse',
            'transferred_by' => auth()->id(),
            'note'           => 'Sent to Warehouse Stock',
        ]);

        // Gold amount from purchase
        $gold = [
            'vori' => $purchase->bhori ?? 0,
            'ana'  => $purchase->ana   ?? 0,
            'roti' => $purchase->roti  ?? 0,
            'point'=> $purchase->point ?? 0,
        ];

        $prev = Warehouse::where('product_id', $purchase->product_id)
                         ->where('category_id', $purchase->category_id)
                         ->latest()->first();

        if ($prev) {
            $prevGold = ['vori' => $prev->bhori, 'ana' => $prev->ana, 'roti' => $prev->roti, 'point' => $prev->point];
            $updated  = addGold($prevGold, $gold);
            $prev->karat  = $purchase->karat;
            $prev->bhori  = $updated['vori'];
            $prev->ana    = $updated['ana'];
            $prev->roti   = $updated['roti'];
            $prev->point  = $updated['point'];
            $prev->gram   = ($prev->gram ?? 0) + ($purchase->gram ?? 0);
            $prev->qty    = ($prev->qty  ?? 0) + 1;
            $prev->save();
        } else {
            $warehouse = new Warehouse;
            $warehouse->product_id  = $purchase->product_id;
            $warehouse->category_id = $purchase->category_id;
            $warehouse->karat       = $purchase->karat;
            $warehouse->bhori       = $gold['vori'];
            $warehouse->ana         = $gold['ana'];
            $warehouse->roti        = $gold['roti'];
            $warehouse->point       = $gold['point'];
            $warehouse->gram        = $purchase->gram ?? 0;
            $warehouse->qty         = 1;
            $warehouse->save();
        }

        return redirect()->back()->with('message', 'পণ্যটি সফলভাবে গুদামে পাঠানো হয়েছে 🏭');
    }

    public function shop_stock_list()
    {
        $purchases = \App\Models\Purchase::with(['invoice', 'productCategory', 'product', 'user'])
            ->where('location', 'is_shop')
            ->latest()
            ->get();
        return view('admin.stock.shopStockList', compact('purchases'));
    }

    public function warehouse_stock_list()
    {
        $purchases = \App\Models\Purchase::with(['invoice', 'productCategory', 'product', 'user'])
            ->where('location', 'is_warehouse')
            ->latest()
            ->get();
        return view('admin.stock.warehouseStockList', compact('purchases'));
    }

    public function send_to_hold(Request $request)
    {
        $purchase = \App\Models\Purchase::findOrFail($request->purchase_id);
        $fromLocation = $purchase->location;

        $purchase->location = 'is_hold';
        $purchase->save();

        \App\Models\PurchaseLocationHistory::create([
            'purchase_id'    => $purchase->id,
            'from_location'  => $fromLocation,
            'to_location'    => 'is_hold',
            'transferred_by' => auth()->id(),
            'note'           => 'Returned to Hold Stock',
        ]);

        return redirect()->back()->with('message', 'পণ্যটি সফলভাবে হোল্ডে ফেরত পাঠানো হয়েছে ↩️');
    }

    public function send_to_karigor(Request $request)
    {
        $purchase = \App\Models\Purchase::findOrFail($request->purchase_id);
        $fromLocation = $purchase->location;

        $purchase->location = 'is_karigor';
        $purchase->save();

        \App\Models\PurchaseLocationHistory::create([
            'purchase_id'         => $purchase->id,
            'from_location'       => $fromLocation,
            'to_location'         => 'is_karigor',
            'transferred_by'      => auth()->id(),
            'assigned_karigor_id' => $request->karigor_id ?? null,
            'task_type'           => $request->task_type ?? null,
            'extra_raw_gold'      => $request->extra_raw_gold ?? null,
            'note'                => 'Sent to Karigor Stock',
        ]);

        return redirect()->back()->with('message', 'পণ্যটি সফলভাবে কারিগর Stock এ পাঠানো হয়েছে 🔨');
    }

    public function total_stock_list()
    {
        $purchases = \App\Models\Purchase::with(['invoice', 'productCategory', 'product', 'user'])
            ->latest()
            ->get();
        return view('admin.stock.mainStockList', compact('purchases'));
    }
}
