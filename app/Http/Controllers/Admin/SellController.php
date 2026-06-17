<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Shop;
use App\Models\Warehouse;
use App\Models\Stock;
use App\Models\Sell;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Invoice;
use App\Models\ProductCategory;

require_once app_path('helpers.php');

class SellController extends Controller
{
    public function sell_index()
    {
        // dd(auth()->user()->role->role_slug);
        $transactions = Transaction::with('sells')->whereHas('sells', function ($query) {
            $query->whereNotNull('transaction_id');
        })->get();
        // $sells=Sell::latest()->get();
        $users=User::latest()->get();

        $categories = DB::table('product_categories')
                        ->join('shops', 'product_categories.id', '=', 'shops.category_id')
                        ->select('product_categories.*')
                        ->distinct()
                        ->get();

        $products = Product::select(['id', 'product_name'])->get();
        return view('admin.sell.index',compact('transactions','categories','products','users'));
    }

    public function sell_store(Request $request)
    {
        DB::beginTransaction(); // Start a transaction
    
        try {
            // Create a new transaction
            $transaction = Transaction::create([
                'user_id' => $request->user_id,
                'category_id' => $request->category_id,
                'product_id' => $request->product_id,
                'details' => $request->details,
                'total_price' => $request->total_price,
                'adv_payment' => $request->adv_payment,
                'due_payment' => $request->due_payment,
                'due_payment_date' => $request->due_payment_date,
                'total_payment' => $request->total_payment,
            ]);
            // dd($transaction->id);
            // Create a new invoice
            $invoice = Invoice::create([
                'user_id' => $request->user_id,
                'category_id' => $request->category_id,
                'product_id' => $request->product_id,
                'transaction_id' => $transaction->id,
                'invoice_no' => 'INV-' . rand(),
                'details' => $request->details,
                'total_price' => $request->total_price,
                'adv_payment' => $request->adv_payment,
                'due_payment' => $request->due_payment,
                'due_payment_date' => $request->due_payment_date,
                'total_payment' => $request->total_payment,
            ]);

            // Handle photo upload
            if ($request->photo) {
                $imageName = 'sells' . time() . '.' . $request->photo->extension();
                $image = $request->photo->move(public_path('user/sells'), $imageName);
            } else {
                $imageName = 'default-cover.jpg'; // default image
            }
    
            // Initialize totals for the new sells
            $totalBhori = 0;
            $totalAna = 0;
            $totalRoti = 0;
            $totalPoint = 0;
            $totalGram = 0;
    
            // Create new sells and calculate totals
            for ($i = 0; $i < count($request->bhori); $i++) {
                $request_gold = [
                    'vori' => $request->bhori[$i],
                    'ana' => $request->ana[$i],
                    'roti' => $request->roti[$i],
                    'point' => $request->point[$i],
                ];
    
                $total = calculateTotalGold($request_gold);
    
                Sell::create([
                    'user_id' => $request->user_id,
                    'category_id' => $request->category_id,
                    'product_id' => $request->product_id,
                    'transaction_id' => $transaction->id,
                    'invoice_id' => $invoice->id,
                    'karat' => $request->karat[$i],
                    'unit_price' => $request->unit_price[$i],
                    'qtr' => 1,
                    'bhori' => $total['vori'],
                    'ana' => $total['ana'],
                    'roti' => $total['roti'],
                    'point' => $total['point'],
                    'gram' => $request->gram[$i],
                    'wage' => $request->wage[$i],
                    'details' => $request->details,
                    'total_price' => $request->price[$i],
                    'order_date' => $request->order_date,
                    'due_payment_date' => $request->due_payment_date,
                    'receive_date' => $request->receive_date,
                    'photo' => $imageName,
                ]);
    
                // Update totals
                $totalBhori += $total['vori'];
                $totalAna += $total['ana'];
                $totalRoti += $total['roti'];
                $totalPoint += $total['point'];
                $totalGram += $request->gram[$i];
            }
    
            // Fetch the latest stock data
            $shop_stock = Shop::where('product_id', $request->product_id)
                ->where('category_id', $request->category_id)
                ->latest()
                ->first();
            $main_stock = Stock::where('product_id', $request->product_id)
                ->where('category_id', $request->category_id)
                ->latest()
                ->first();
    
            $prev_shop_stock = [
                'vori' => $shop_stock->bhori ?? 0,
                'ana' => $shop_stock->ana ?? 0,
                'roti' => $shop_stock->roti ?? 0,
                'point' => $shop_stock->point ?? 0,
            ];
    
            $current_shop_stock = subGold($prev_shop_stock, [
                'vori' => $totalBhori,
                'ana' => $totalAna,
                'roti' => $totalRoti,
                'point' => $totalPoint,
            ]);
    
            $prev_main_stock = [
                'vori' => $main_stock->bhori ?? 0,
                'ana' => $main_stock->ana ?? 0,
                'roti' => $main_stock->roti ?? 0,
                'point' => $main_stock->point ?? 0,
            ];
    
            $current_main_stock = subGold($prev_main_stock, [
                'vori' => $totalBhori,
                'ana' => $totalAna,
                'roti' => $totalRoti,
                'point' => $totalPoint,
            ]);
    
            // Calculate new stock quantities
            $main_stock_qty = $main_stock->qty - $request->qtr;
            $shop_stock_qty = $shop_stock->qty - $request->qtr;
    
            // Update stock quantities in shop and main stock
            $shop_stock->bhori = $current_shop_stock['vori'] ?? $prev_shop_stock['vori'];
            $shop_stock->ana = $current_shop_stock['ana'] ?? $prev_shop_stock['ana'];
            $shop_stock->roti = $current_shop_stock['roti'] ?? $prev_shop_stock['roti'];
            $shop_stock->point = $current_shop_stock['point'] ?? $prev_shop_stock['point'];
            $shop_stock->qty = $shop_stock_qty;
            $shop_stock->gram = $shop_stock->gram - $totalGram;
            $shop_stock->save();
    
            $main_stock->bhori = $current_main_stock['vori'] ?? $prev_main_stock['vori'];
            $main_stock->ana = $current_main_stock['ana'] ?? $prev_main_stock['ana'];
            $main_stock->roti = $current_main_stock['roti'] ?? $prev_main_stock['roti'];
            $main_stock->point = $current_main_stock['point'] ?? $prev_main_stock['point'];
            $main_stock->qty = $main_stock_qty;
            $main_stock->gram = $main_stock->gram - $totalGram;
            $main_stock->save();
    
            DB::commit(); // Commit the transaction
    
            return redirect()->route('sells.index')->with('message', 'Product Sold Successfully 🙂');
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback the transaction in case of error
    
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function sells_edit($id){
        $transaction = Transaction::with('sells')->find($id);
        $users = User::latest()->get();
        $categories = ProductCategory::select(['id', 'category_name'])->get();
        $products = Product::select(['id', 'product_name'])->get();
        return view('admin.sell.edit',compact('users','transaction','categories','products'));
    }

    public function sell_update(Request $request)
    {
        // dd($request);
        // Retrieve the transaction and related invoice
        $transaction = Transaction::findOrFail($request->transaction_id);
        $invoice = Invoice::where('transaction_id', $request->transaction_id)->firstOrFail();

        // Update the transaction
        $transaction->update([
            'user_id' => $request->user_id,
            'category_id' => $request->category_id,
            'product_id' => $request->product_id,
            'details' => $request->details,
            'total_price' => $request->total_price,
            'adv_payment' => $request->adv_payment,
            'due_payment' => $request->due_payment,
            'due_payment_date' => $request->due_payment_date,
            'total_payment' => $request->total_payment,
        ]);

        // Update the invoice
        $invoice->update([
            'user_id' => $request->user_id,
            'category_id' => $request->category_id,
            'product_id' => $request->product_id,
            'details' => $request->details,
            'total_price' => $request->total_price,
            'adv_payment' => $request->adv_payment,
            'due_payment' => $request->due_payment,
            'due_payment_date' => $request->due_payment_date,
            'total_payment' => $request->total_payment,
        ]);

        // Handle photo upload
        if ($request->photo) {
            $imageName = 'purchase' . time() . '.' . $request->photo->extension();
            $request->photo->move(public_path('user/purchase'), $imageName);
        } else {
            $imageName = 'default-cover.jpg'; // default image
        }

        // Delete previous sells
        $previous_sells = Sell::where('transaction_id', $transaction->id)->get();
        
        // Calculate the previous total stock sold
        $previous_sub_stock = ['vori' => 0, 'ana' => 0, 'roti' => 0, 'point' => 0];
        foreach ($previous_sells as $sell) {
            $previous_sub_stock = addGold($previous_sub_stock, [
                'vori' => $sell->bhori,
                'ana' => $sell->ana,
                'roti' => $sell->roti,
                'point' => $sell->point,
            ]);
        }
        Sell::where('transaction_id', $transaction->id)->delete();

        // Initialize totals for the new sells
        $totalBhori = 0;
        $totalAna = 0;
        $totalRoti = 0;
        $totalPoint = 0;
        $totalGram = 0;

        // Create new sells and calculate totals
        for ($i = 0; $i < count($request->bhori); $i++) {
            $request_gold = [
                'vori' => $request->bhori[$i],
                'ana' => $request->ana[$i],
                'roti' => $request->roti[$i],
                'point' => $request->point[$i],
            ];

            $total = calculateTotalGold($request_gold);

            $sellData = [
                'user_id' => $request->user_id,
                'category_id' => $request->category_id,
                'product_id' => $request->product_id,
                'transaction_id' => $transaction->id,
                'invoice_id' => $invoice->id,
                'karat' => $request->karat[$i],
                'unit_price' => $request->unit_price[$i],
                'qty' => 1,
                'bhori' => $total['vori'],
                'ana' => $total['ana'],
                'roti' => $total['roti'],
                'point' => $total['point'],
                'gram' => $request->gram[$i],
                'wage' => $request->wage[$i],
                'details' => $request->details,
                'total_price' => $request->price[$i],
                'order_date' => $request->order_date,
                'due_payment_date' => $request->due_payment_date,
                'receive_date' => $request->receive_date,
                'location' => $request->location,
                'photo' => $imageName,
            ];

            Sell::create($sellData);

            // Update totals
            $totalBhori += $total['vori'];
            $totalAna += $total['ana'];
            $totalRoti += $total['roti'];
            $totalPoint += $total['point'];
            $totalGram += $request->gram[$i];
        }

        $new_sub_stock = [
            'vori' => $totalBhori,
            'ana' => $totalAna,
            'roti' => $totalRoti,
            'point' => $totalPoint
        ];

        // Fetch the latest stock data
        $shop_stock = Shop::where('product_id', $request->product_id)->where('category_id', $request->category_id)->latest()->first();
        $main_stock = Stock::where('product_id', $request->product_id)->where('category_id', $request->category_id)->latest()->first();

        $prev_shop_stock = ['vori' => $shop_stock->bhori ?? 0, 'ana' => $shop_stock->ana ?? 0, 'roti' => $shop_stock->roti ?? 0, 'point' => $shop_stock->point ?? 0];
        $prev_main_stock = ['vori' => $main_stock->bhori ?? 0, 'ana' => $main_stock->ana ?? 0, 'roti' => $main_stock->roti ?? 0, 'point' => $main_stock->point ?? 0];

        // Revert previous stock changes before applying new ones
        $current_shop_stock = addGold($prev_shop_stock, $previous_sub_stock);
        $current_shop_stock = subGold($current_shop_stock, $previous_sub_stock);

        // Deduct the new sold quantity
        $current_shop_stock = subGold($current_shop_stock, $new_sub_stock);

        $current_main_stock = addGold($prev_main_stock, $previous_sub_stock);
        $current_main_stock = subGold($current_main_stock, $previous_sub_stock);

        // Deduct the new sold quantity
        $current_main_stock = subGold($current_main_stock, $new_sub_stock);

        // Calculate new stock quantities
        $shop_stock_qty = $shop_stock->qty - array_sum($previous_sub_stock) + $request->qtr;
        $main_stock_qty = $main_stock->qty - array_sum($previous_sub_stock) + $request->qtr;

        // Update stock quantities in shop and main stock
        $shop_stock->bhori = $current_shop_stock['vori'] ?? $prev_shop_stock['vori'];
        $shop_stock->ana = $current_shop_stock['ana'] ?? $prev_shop_stock['ana'];
        $shop_stock->roti = $current_shop_stock['roti'] ?? $prev_shop_stock['roti'];
        $shop_stock->point = $current_shop_stock['point'] ?? $prev_shop_stock['point'];
        $shop_stock->qty = $shop_stock_qty;
        $shop_stock->save();

        $main_stock->bhori = $current_main_stock['vori'] ?? $prev_main_stock['vori'];
        $main_stock->ana = $current_main_stock['ana'] ?? $prev_main_stock['ana'];
        $main_stock->roti = $current_main_stock['roti'] ?? $prev_main_stock['roti'];
        $main_stock->point = $current_main_stock['point'] ?? $prev_main_stock['point'];
        $main_stock->qty = $main_stock_qty;
        $main_stock->save();

        return redirect()->route('sells.index')->with('message', 'Product Sale Updated Successfully 🙂');
    }
    

}
