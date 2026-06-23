<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\Shop;
use App\Models\Warehouse;
use App\Models\Stock;
use App\Models\Transaction;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\ProductCategory;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

require_once app_path('helpers.php');

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $transactions = Transaction::with('purchases')->whereHas('purchases', function ($query) {
            $query->whereNotNull('transaction_id');
        })->get();


        $users = User::latest()->get();
        $categories = ProductCategory::select(['id', 'category_name'])->get();

        return view('admin.purchase.purchase', compact('transactions', 'categories', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function warehouse_index()
    {
        $transactions = Transaction::with('purchases')->whereHas('purchases', function ($query) {
            $query->where('location', 'is_warehouse');
        })->latest()->get();

        return view('admin.purchase.warehouse', compact('transactions'));
    }
    public function shop_index()
    {
        $transactions = Transaction::with('purchases')->whereHas('purchases', function ($query) {
            $query->where('location', 'is_shop');
        })->latest()->get();

        return view('admin.purchase.shop', compact('transactions'));
    }

    public function due()
    {
        $transactions = Transaction::with('purchases')->whereHas('purchases')->where('due_payment', '>', 0)->whereNotNull('due_payment')->latest()->get();

        return view('admin.purchase.due', compact('transactions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request);

        $transaction = Transaction::create([
            'user_id' => $request->user_id,
            'category_id' => $request->category_id[0] ?? null,
            'product_id' => $request->product_id[0] ?? null,
            'details' => $request->details[0] ?? null,
            'total_price' => $request->total_price,
            'adv_payment' => $request->adv_payment,
            'due_payment' => $request->due_payment,
            'due_payment_date' => $request->due_payment_date,
            'total_payment' => $request->total_payment,
        ]);

        $invoice = Invoice::create([
            'user_id' => $request->user_id,
            'category_id' => $request->category_id[0] ?? null,
            'product_id' => $request->product_id[0] ?? null,
            'transaction_id' => $transaction->id,
            'invoice_no' => 'INV-' . rand(),
            'details' => $request->details[0] ?? null,
            'total_price' => $request->total_price,
            'adv_payment' => $request->adv_payment,
            'due_payment' => $request->due_payment,
            'due_payment_date' => $request->due_payment_date,
            'total_payment' => $request->total_payment,
        ]);

        // Loop through each item and store the details
        $totalBhori = 0;
        $totalAna = 0;
        $totalRoti = 0;
        $totalPoint = 0;
        $totalGram = 0;

        $photos = $request->file('photo') ?? [];

        for ($i = 0; $i < count($request->bhori); $i++) {
            $request_gold = [
                'vori' => $request->bhori[$i],
                'ana' => $request->ana[$i],
                'roti' => $request->roti[$i],
                'point' => $request->point[$i],
            ];

            $total = calculateTotalGold($request_gold);

            $item_category_id = $request->category_id[$i] ?? null;
            $item_product_id = $request->product_id[$i] ?? null;
            $item_karat = $request->karat[$i] ?? null;
            if ($item_karat === 'Paeine') {
                $item_karat = $request->karat_other[$i] ?? null;
            }
            $item_unit_price = $request->unit_price[$i] ?? null;
            $item_gram = $request->gram[$i] ?? 0;
            $item_total_price = $request->price[$i] ?? 0;
            $item_details = $request->details[$i] ?? null;

            // Handle per-item photo upload
            if (!empty($photos[$i])) {
                $imageName = 'purchase' . time() . $i . '.' . $photos[$i]->extension();
                $photos[$i]->move(public_path('user/purchase'), $imageName);
            } else {
                $imageName = 'default-cover.jpg';
            }

            $pur = Purchase::create([
                'user_id' => $request->user_id,
                'category_id' => $item_category_id,
                'product_id' => $item_product_id,
                'transaction_id' => $transaction->id,
                'invoice_id' => $invoice->id,
                'karat' => $item_karat,
                'unit_price' => $item_unit_price,
                'qtr' => 1,
                'bhori' => $total['vori'],
                'ana' => $total['ana'],
                'roti' => $total['roti'],
                'point' => $total['point'],
                'gram' => $item_gram,
                'details' => $item_details,
                'total_price' => $item_total_price,
                'order_date' => $request->order_date,
                'due_payment_date' => $request->due_payment_date,
                'receive_date' => $request->receive_date,
                'location' => 'is_hold',
                'photo' => $imageName,
            ]);

            $totalBhori += $total['vori'];
            $totalAna += $total['ana'];
            $totalRoti += $total['roti'];
            $totalPoint += $total['point'];
            $totalGram += $item_gram;

            // Handle stock update for this specific product/category
            $prev_stock = Stock::where('product_id', $item_product_id)
                ->where('category_id', $item_category_id)
                ->latest()
                ->first();
            if ($prev_stock) {
                $prevGold = [
                    'vori' => $prev_stock->bhori,
                    'ana' => $prev_stock->ana,
                    'roti' => $prev_stock->roti,
                    'point' => $prev_stock->point,
                ];

                $total_gold = addGold($prevGold, [
                    'vori' => $total['vori'],
                    'ana' => $total['ana'],
                    'roti' => $total['roti'],
                    'point' => $total['point'],
                ]);

                $updateQty = $prev_stock->qty + 1;
                $updateGram = $prev_stock->gram + $item_gram;

                $prev_stock->update([
                    'karat' => $item_karat,
                    'ana' => $total_gold['ana'],
                    'bhori' => $total_gold['vori'],
                    'roti' => $total_gold['roti'],
                    'point' => $total_gold['point'],
                    'gram' => $updateGram,
                    'qty' => $updateQty,
                    'unit_price' => $item_unit_price,
                    'location' => $request->location,
                ]);

                $stock_id = $prev_stock->id;
            } else {
                $stock = Stock::create([
                    'product_id' => $item_product_id,
                    'category_id' => $item_category_id,
                    'karat' => $item_karat,
                    'ana' => $total['ana'],
                    'bhori' => $total['vori'],
                    'roti' => $total['roti'],
                    'point' => $total['point'],
                    'gram' => $item_gram,
                    'qty' => 1,
                    'unit_price' => $item_unit_price,
                    'location' => $request->location,
                ]);

                $stock_id = $stock->id;
            }

            $item_gold = [
                'vori' => $total['vori'],
                'ana' => $total['ana'],
                'roti' => $total['roti'],
                'point' => $total['point'],
            ];

            // Handle shop and warehouse updates for this specific product/category
            if ($request->location == "is_shop") {
                $prev_shop_stock = Shop::where('product_id', $item_product_id)
                    ->where('category_id', $item_category_id)
                    ->latest()
                    ->first();
                if ($prev_shop_stock) {
                    $prevGold = [
                        'vori' => $prev_shop_stock->bhori,
                        'ana' => $prev_shop_stock->ana,
                        'roti' => $prev_shop_stock->roti,
                        'point' => $prev_shop_stock->point,
                    ];

                    $total_gold = addGold($prevGold, $item_gold);

                    $prev_shop_stock->update([
                        'stock_id' => $stock_id,
                        'karat' => $item_karat,
                        'ana' => $total_gold['ana'],
                        'bhori' => $total_gold['vori'],
                        'roti' => $total_gold['roti'],
                        'point' => $total_gold['point'],
                        'qty' => $prev_shop_stock->qty + 1,
                        'gram' => $prev_shop_stock->gram + $item_gram,
                    ]);
                } else {
                    Shop::create([
                        'stock_id' => $stock_id,
                        'purchase_id' => $pur->id,
                        'product_id' => $item_product_id,
                        'category_id' => $item_category_id,
                        'karat' => $item_karat,
                        'ana' => $item_gold['ana'],
                        'bhori' => $item_gold['vori'],
                        'roti' => $item_gold['roti'],
                        'point' => $item_gold['point'],
                        'qty' => 1,
                        'gram' => $item_gram,
                    ]);
                }
            } elseif ($request->location == "is_warehouse") {
                $prev_ware_stock = Warehouse::where('product_id', $item_product_id)
                    ->where('category_id', $item_category_id)
                    ->latest()
                    ->first();
                if ($prev_ware_stock) {
                    $prevGold = [
                        'vori' => $prev_ware_stock->bhori,
                        'ana' => $prev_ware_stock->ana,
                        'roti' => $prev_ware_stock->roti,
                        'point' => $prev_ware_stock->point,
                    ];

                    $total_gold = addGold($prevGold, $item_gold);

                    $prev_ware_stock->update([
                        'stock_id' => $stock_id,
                        'karat' => $item_karat,
                        'ana' => $total_gold['ana'],
                        'bhori' => $total_gold['vori'],
                        'roti' => $total_gold['roti'],
                        'point' => $total_gold['point'],
                        'qty' => $prev_ware_stock->qty + 1,
                        'gram' => $prev_ware_stock->gram + $item_gram,
                    ]);
                } else {
                    Warehouse::create([
                        'stock_id' => $stock_id,
                        'purchase_id' => $pur->id,
                        'product_id' => $item_product_id,
                        'category_id' => $item_category_id,
                        'karat' => $item_karat,
                        'ana' => $item_gold['ana'],
                        'bhori' => $item_gold['vori'],
                        'roti' => $item_gold['roti'],
                        'point' => $item_gold['point'],
                        'qty' => 1,
                        'gram' => $item_gram,
                    ]);
                }
            }
        }

        return redirect()->back()->with('message', 'Purchase Order Completed Successfully 🙂');
    }

    private function updateShopStock($request, $total, $totalGram, $stock_id, $pur)
    {
        $prev_shop_stock = Shop::where('product_id', $request->product_id)
            ->where('category_id', $request->category_id)
            ->latest()
            ->first();
        if ($prev_shop_stock) {
            $prevGold = [
                'vori' => $prev_shop_stock->bhori,
                'ana' => $prev_shop_stock->ana,
                'roti' => $prev_shop_stock->roti,
                'point' => $prev_shop_stock->point,
            ];

            $total_gold = addGold($prevGold, $total);

            $prev_shop_stock->update([
                'stock_id' => $stock_id,
                'karat' => $request->karat[0],
                'ana' => $total_gold['ana'],
                'bhori' => $total_gold['vori'],
                'roti' => $total_gold['roti'],
                'point' => $total_gold['point'],
                'qty' => $prev_shop_stock->qty + $request->qtr,
                'gram' => $prev_shop_stock->gram + $totalGram,
            ]);
        } else {
            Shop::create([
                'stock_id' => $stock_id,
                'purchase_id' => $pur->id,
                'product_id' => $request->product_id,
                'category_id' => $request->category_id,
                'karat' => $request->karat[0],
                'ana' => $total['ana'],
                'bhori' => $total['vori'],
                'roti' => $total['roti'],
                'point' => $total['point'],
                'qty' => $request->qtr,
                'gram' => $totalGram,
            ]);
        }
    }

    private function updateWarehouseStock($request, $total, $totalGram, $stock_id, $pur)
    {
        // dd($total);
        $prev_ware_stock = Warehouse::where('product_id', $request->product_id)
            ->where('category_id', $request->category_id)
            ->latest()
            ->first();
        if ($prev_ware_stock) {
            $prevGold = [
                'vori' => $prev_ware_stock->bhori,
                'ana' => $prev_ware_stock->ana,
                'roti' => $prev_ware_stock->roti,
                'point' => $prev_ware_stock->point,
            ];

            $total_gold = addGold($prevGold, $total);

            $prev_ware_stock->update([
                'stock_id' => $stock_id,
                'karat' => $request->karat[0],
                'ana' => $total_gold['ana'],
                'bhori' => $total_gold['vori'],
                'roti' => $total_gold['roti'],
                'point' => $total_gold['point'],
                'qty' => $prev_ware_stock->qty + $request->qtr,
                'gram' => $prev_ware_stock->gram + $totalGram,
            ]);
        } else {
            Warehouse::create([
                'stock_id' => $stock_id,
                'purchase_id' => $pur->id,
                'product_id' => $request->product_id,
                'category_id' => $request->category_id,
                'karat' => $request->karat[0],
                'ana' => $total['ana'],
                'bhori' => $total['vori'],
                'roti' => $total['roti'],
                'point' => $total['point'],
                'qty' => $request->qtr,
                'gram' => $totalGram,
            ]);
        }
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
        $transaction = Transaction::with('purchases')->find($id);
        $users = User::latest()->get();
        $categories = ProductCategory::select(['id', 'category_name'])->get();
        $products = Product::select(['id', 'product_name'])->get();

        return view('admin.purchase.edit', compact('transaction', 'users', 'categories', 'products'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $transaction = Transaction::find($request->transaction_id);
        $invoice = Invoice::where('transaction_id', $request->transaction_id)->first();

        $transaction->update([
            'user_id' => $request->user_id,
            'category_id' => $request->category_id[0] ?? null,
            'product_id' => $request->product_id[0] ?? null,
            'details' => $request->details[0] ?? null,
            'total_price' => $request->total_price,
            'adv_payment' => $request->adv_payment,
            'due_payment' => $request->due_payment,
            'due_payment_date' => $request->due_payment_date,
            'total_payment' => $request->total_payment,
        ]);

        $invoice->update([
            'user_id' => $request->user_id,
            'category_id' => $request->category_id[0] ?? null,
            'product_id' => $request->product_id[0] ?? null,
            'details' => $request->details[0] ?? null,
            'total_price' => $request->total_price,
            'adv_payment' => $request->adv_payment,
            'due_payment' => $request->due_payment,
            'due_payment_date' => $request->due_payment_date,
            'total_payment' => $request->total_payment,
        ]);
        $old_purchase = Purchase::where('transaction_id', $transaction->id)->first();
        $location = $request->has('location') ? $request->location : ($old_purchase ? $old_purchase->location : 'is_hold');
        
        Purchase::where('transaction_id', $transaction->id)->delete();
        // Loop through each item and store the details
        $totalBhori = 0;
        $totalAna = 0;
        $totalRoti = 0;
        $totalPoint = 0;
        $totalGram = 0;

        $photos = $request->file('photo') ?? [];

        for ($i = 0; $i < count($request->bhori); $i++) {
            $request_gold = [
                'vori' => $request->bhori[$i],
                'ana' => $request->ana[$i],
                'roti' => $request->roti[$i],
                'point' => $request->point[$i],
            ];

            $total = calculateTotalGold($request_gold);

            $item_category_id = $request->category_id[$i] ?? null;
            $item_product_id = $request->product_id[$i] ?? null;
            $item_karat = $request->karat[$i] ?? null;
            if ($item_karat === 'Paeine') {
                $item_karat = $request->karat_other[$i] ?? null;
            }
            $item_unit_price = $request->unit_price[$i] ?? null;
            $item_gram = $request->gram[$i] ?? 0;
            $item_total_price = $request->price[$i] ?? 0;
            $item_details = $request->details[$i] ?? null;

            // Handle per-item photo upload
            if (!empty($photos[$i])) {
                $imageName = 'purchase' . time() . $i . '.' . $photos[$i]->extension();
                $photos[$i]->move(public_path('user/purchase'), $imageName);
            } else {
                $imageName = 'default-cover.jpg';
            }

            $pur = Purchase::create([
                'user_id' => $request->user_id,
                'category_id' => $item_category_id,
                'product_id' => $item_product_id,
                'transaction_id' => $transaction->id,
                'invoice_id' => $invoice->id,
                'karat' => $item_karat,
                'unit_price' => $item_unit_price,
                'qtr' => 1,
                'bhori' => $total['vori'],
                'ana' => $total['ana'],
                'roti' => $total['roti'],
                'point' => $total['point'],
                'gram' => $item_gram,
                'details' => $item_details,
                'total_price' => $item_total_price,
                'order_date' => $request->order_date,
                'due_payment_date' => $request->due_payment_date,
                'receive_date' => $request->receive_date,
                'location' => $location,
                'photo' => $imageName,
            ]);

            $totalBhori += $total['vori'];
            $totalAna += $total['ana'];
            $totalRoti += $total['roti'];
            $totalPoint += $total['point'];
            $totalGram += $item_gram;

            // Handle stock update for this specific product/category
            $prev_stock = Stock::where('product_id', $item_product_id)
                ->where('category_id', $item_category_id)
                ->latest()
                ->first();
            if ($prev_stock) {
                $prevGold = [
                    'vori' => $prev_stock->bhori,
                    'ana' => $prev_stock->ana,
                    'roti' => $prev_stock->roti,
                    'point' => $prev_stock->point,
                ];

                $total_gold = addGold($prevGold, [
                    'vori' => $total['vori'],
                    'ana' => $total['ana'],
                    'roti' => $total['roti'],
                    'point' => $total['point'],
                ]);

                $updateQty = $prev_stock->qty + 1;
                $updateGram = $prev_stock->gram + $item_gram;

                $prev_stock->update([
                    'karat' => $item_karat,
                    'ana' => $total_gold['ana'],
                    'bhori' => $total_gold['vori'],
                    'roti' => $total_gold['roti'],
                    'point' => $total_gold['point'],
                    'gram' => $updateGram,
                    'qty' => $updateQty,
                    'unit_price' => $item_unit_price,
                    'location' => $location,
                ]);

                $stock_id = $prev_stock->id;
            } else {
                $stock = Stock::create([
                    'product_id' => $item_product_id,
                    'category_id' => $item_category_id,
                    'karat' => $item_karat,
                    'ana' => $total['ana'],
                    'bhori' => $total['vori'],
                    'roti' => $total['roti'],
                    'point' => $total['point'],
                    'gram' => $item_gram,
                    'qty' => 1,
                    'unit_price' => $item_unit_price,
                    'location' => $location,
                ]);

                $stock_id = $stock->id;
            }

            $item_gold = [
                'vori' => $total['vori'],
                'ana' => $total['ana'],
                'roti' => $total['roti'],
                'point' => $total['point'],
            ];

            // Handle shop and warehouse updates for this specific product/category
            if ($location == "is_shop") {
                $prev_shop_stock = Shop::where('product_id', $item_product_id)
                    ->where('category_id', $item_category_id)
                    ->latest()
                    ->first();
                if ($prev_shop_stock) {
                    $prevGold = [
                        'vori' => $prev_shop_stock->bhori,
                        'ana' => $prev_shop_stock->ana,
                        'roti' => $prev_shop_stock->roti,
                        'point' => $prev_shop_stock->point,
                    ];

                    $total_gold = addGold($prevGold, $item_gold);

                    $prev_shop_stock->update([
                        'stock_id' => $stock_id,
                        'karat' => $item_karat,
                        'ana' => $total_gold['ana'],
                        'bhori' => $total_gold['vori'],
                        'roti' => $total_gold['roti'],
                        'point' => $total_gold['point'],
                        'qty' => $prev_shop_stock->qty + 1,
                        'gram' => $prev_shop_stock->gram + $item_gram,
                    ]);
                } else {
                    Shop::create([
                        'stock_id' => $stock_id,
                        'purchase_id' => $pur->id,
                        'product_id' => $item_product_id,
                        'category_id' => $item_category_id,
                        'karat' => $item_karat,
                        'ana' => $item_gold['ana'],
                        'bhori' => $item_gold['vori'],
                        'roti' => $item_gold['roti'],
                        'point' => $item_gold['point'],
                        'qty' => 1,
                        'gram' => $item_gram,
                    ]);
                }
            } elseif ($location == "is_warehouse") {
                $prev_ware_stock = Warehouse::where('product_id', $item_product_id)
                    ->where('category_id', $item_category_id)
                    ->latest()
                    ->first();
                if ($prev_ware_stock) {
                    $prevGold = [
                        'vori' => $prev_ware_stock->bhori,
                        'ana' => $prev_ware_stock->ana,
                        'roti' => $prev_ware_stock->roti,
                        'point' => $prev_ware_stock->point,
                    ];

                    $total_gold = addGold($prevGold, $item_gold);

                    $prev_ware_stock->update([
                        'stock_id' => $stock_id,
                        'karat' => $item_karat,
                        'ana' => $total_gold['ana'],
                        'bhori' => $total_gold['vori'],
                        'roti' => $total_gold['roti'],
                        'point' => $total_gold['point'],
                        'qty' => $prev_ware_stock->qty + 1,
                        'gram' => $prev_ware_stock->gram + $item_gram,
                    ]);
                } else {
                    Warehouse::create([
                        'stock_id' => $stock_id,
                        'purchase_id' => $pur->id,
                        'product_id' => $item_product_id,
                        'category_id' => $item_category_id,
                        'karat' => $item_karat,
                        'ana' => $item_gold['ana'],
                        'bhori' => $item_gold['vori'],
                        'roti' => $item_gold['roti'],
                        'point' => $item_gold['point'],
                        'qty' => 1,
                        'gram' => $item_gram,
                    ]);
                }
            }
        }

        return redirect()->back()->with('message', 'Purchase Order Updated Successfully 🙂');
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

    public function getProduct($category_id)
    {
        $products = Product::select(['id', 'product_name'])->where('category_id', $category_id)->get();
        return response()->json($products);
    }
    public function getProductShop($category_id)
    {
        $products = DB::table('products')
            ->join('shops', 'products.id', '=', 'shops.product_id')
            ->where('products.category_id', $category_id)
            ->select('products.*')
            ->distinct()
            ->get();
        return response()->json($products);
    }

    public function calculate(Request $request)
    {
        if ($request->input('wage')) {
            $wage = $request->input('wage');
        } else {
            $wage = 0;
        }
        $unit_price = $request->input('unit_price');
        // $qty = $request->input('qtr');
        $bhori = $request->input('bhori');
        $ana = $request->input('ana');
        $roti = $request->input('roti');
        $point = $request->input('point');

        $bhori_from_ana = $ana / 16;
        $bhori_from_roti = $roti / 96;
        $bhori_from_point = $point / 960;

        $total_bhori = $bhori + $bhori_from_ana + $bhori_from_roti + $bhori_from_point;
        $total_price = ($unit_price * $total_bhori) + $wage;
        // $total_price = ($unit_price * $qty * $total_bhori) + $wage;

        return response()->json(['total_price' => $total_price]);
    }
    public function calculateTotal(Request $request)
    {
        $total_price = $request->input('total_price');
        $adv_payment = $request->input('adv_payment');

        $due_payment = $total_price - $adv_payment; // Perform your calculation here
        $total_payment = $total_price - $due_payment; // Perform your calculation here

        return response()->json([
            'due_payment' => $due_payment,
            'total_payment' => $total_payment,
        ]);
    }

    // public function totalDueForSupplier($user_id)
    // {
    //     $totalPrice = Purchase::where('user_id', $user_id)->sum('total_price');
    //     $totalPayment = Purchase::where('user_id', $user_id)->sum('total_payment');
    //     $totalDue = Purchase::where('user_id', $user_id)->sum('due_payment');

    //     $purchase = Purchase::where('user_id', $user_id)->first();

    //     return view('admin.purchase.cleardue', compact('totalPrice', 'totalPayment', 'totalDue', 'purchase'));

    // }


}
