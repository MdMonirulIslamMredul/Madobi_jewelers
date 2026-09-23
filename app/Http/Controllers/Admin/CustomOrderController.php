<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomOrder;
use App\Models\CustomOrderItem;
use App\Models\KarigorJob;
use App\Models\KarigorMojuri;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductPrice;
use App\Models\RawStock;
use App\Models\SellPayment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CustomOrderController extends Controller
{
    /**
     * Display listing of custom orders.
     */
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');

        $query = CustomOrder::with(['customer', 'category', 'karigor', 'karigorJob', 'payments', 'items.category', 'items.karigor'])->latest();

        if ($status !== 'all' && in_array($status, ['pending', 'in_production', 'ready_for_delivery', 'delivered', 'cancelled'])) {
            $query->where('status', $status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_no', 'LIKE', "%{$search}%")
                  ->orWhere('product_name', 'LIKE', "%{$search}%")
                  ->orWhereHas('customer', function ($cq) use ($search) {
                      $cq->where('name', 'LIKE', "%{$search}%")
                         ->orWhere('last_name', 'LIKE', "%{$search}%")
                         ->orWhere('phone', 'LIKE', "%{$search}%");
                  });
            });
        }

        $orders = $query->paginate(15)->appends($request->all());

        // Count stats for tabs
        $counts = [
            'all'                => CustomOrder::count(),
            'in_production'      => CustomOrder::where('status', 'in_production')->count(),
            'ready_for_delivery' => CustomOrder::where('status', 'ready_for_delivery')->count(),
            'delivered'          => CustomOrder::where('status', 'delivered')->count(),
        ];

        $karigorMojuris = KarigorMojuri::orderBy('category_name', 'asc')->get();

        return view('admin.custom_order.index', compact('orders', 'status', 'counts', 'karigorMojuris'));
    }

    /**
     * Show the form for creating a new custom jewelry order.
     */
    public function create()
    {
        $categories = ProductCategory::orderBy('category_name', 'asc')->get();
        $products = Product::orderBy('product_name', 'asc')->get();
        $productPrices = ProductPrice::all();

        $karigors = User::whereHas('role', function ($q) {
            $q->where('role_slug', 'karigor');
        })->where('is_active', 1)->get();

        return view('admin.custom_order.create', compact('categories', 'products', 'productPrices', 'karigors'));
    }

    /**
     * Store a newly created custom order.
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_id'           => 'required|exists:users,id',
            'estimated_price'       => 'nullable|numeric|min:0',
            'advance_payment'       => 'nullable|numeric|min:0',
            'payment_method'        => 'nullable|string',
            'transaction_reference' => 'nullable|string|max:255',
            'delivery_date'         => 'nullable|date',
            'details'               => 'nullable|string|max:1000',
            'design_photo'          => 'nullable|image|max:3072',
            'items'                 => 'nullable|array|min:1',
            'items.*.category_id'   => 'nullable|exists:product_categories,id',
            'items.*.product_name'  => 'required_with:items|string|max:255',
            'items.*.karat'         => 'nullable|string|max:50',
            'items.*.target_bhori'  => 'nullable|integer|min:0',
            'items.*.target_ana'    => 'nullable|integer|min:0|max:15',
            'items.*.target_roti'   => 'nullable|integer|min:0|max:5',
            'items.*.target_point'  => 'nullable|integer|min:0|max:9',
            'items.*.target_gram'   => 'nullable|numeric|min:0',
            'items.*.raw_gold_needed' => 'nullable|numeric|min:0',
            'items.*.assigned_karigor_id' => 'nullable|exists:users,id',
        ]);

        DB::beginTransaction();
        try {
            $orderNo = 'ORD-' . date('Ymd') . '-' . strtoupper(Str::random(5));

            $photoPath = null;
            if ($request->hasFile('design_photo')) {
                $file = $request->file('design_photo');
                $filename = 'order_' . time() . '_' . rand(100, 999) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/orders'), $filename);
                $photoPath = 'uploads/orders/' . $filename;
            }

            $advancePayment = (float) ($request->advance_payment ?? 0);
            $estimatedPrice = (float) ($request->estimated_price ?? 0);
            $dueAmount = max(0, $estimatedPrice - $advancePayment);

            $paymentStatus = 'due';
            if ($advancePayment > 0) {
                $paymentStatus = ($dueAmount <= 0 && $estimatedPrice > 0) ? 'paid' : 'partial';
            }

            // Determine if multi-item submission or single-item fallback
            $rawItems = $request->input('items', []);
            if (empty($rawItems) && $request->filled('product_name')) {
                $rawItems = [[
                    'category_id'         => $request->category_id,
                    'product_name'        => $request->product_name,
                    'karat'               => $request->karat ?? '22K',
                    'quantity'            => $request->quantity ?? 1,
                    'target_bhori'        => $request->target_bhori ?? 0,
                    'target_ana'          => $request->target_ana ?? 0,
                    'target_roti'         => $request->target_roti ?? 0,
                    'target_point'        => $request->target_point ?? 0,
                    'target_gram'         => $request->target_gram ?? 0,
                    'raw_gold_needed'     => $request->raw_gold_needed ?? 0,
                    'assigned_karigor_id' => $request->assigned_karigor_id,
                    'details'             => $request->details,
                ]];
            }

            $totalTargetGram = 0;
            $totalRawGold = 0;
            $totalQty = 0;
            $productNames = [];
            $firstCatId = null;
            $firstKarigorId = null;

            foreach ($rawItems as $it) {
                $totalTargetGram += (float) ($it['target_gram'] ?? 0);
                $totalRawGold += (float) ($it['raw_gold_needed'] ?? 0);
                $totalQty += (int) ($it['quantity'] ?? 1);
                if (!empty($it['product_name'])) {
                    $productNames[] = $it['product_name'];
                }
                if (!$firstCatId && !empty($it['category_id'])) {
                    $firstCatId = $it['category_id'];
                }
                if (!$firstKarigorId && !empty($it['assigned_karigor_id'])) {
                    $firstKarigorId = $it['assigned_karigor_id'];
                }
            }

            $productSummary = !empty($productNames) ? implode(', ', array_unique($productNames)) : 'কাস্টম জুয়েলারি';

            // Create Master CustomOrder
            $order = CustomOrder::create([
                'order_no'            => $orderNo,
                'customer_id'         => $request->customer_id,
                'category_id'         => $firstCatId,
                'product_name'        => $productSummary,
                'karat'               => $rawItems[0]['karat'] ?? '22K',
                'quantity'            => max(1, $totalQty),
                'target_bhori'        => $rawItems[0]['target_bhori'] ?? 0,
                'target_ana'          => $rawItems[0]['target_ana'] ?? 0,
                'target_roti'         => $rawItems[0]['target_roti'] ?? 0,
                'target_point'        => $rawItems[0]['target_point'] ?? 0,
                'target_gram'         => $totalTargetGram,
                'raw_gold_needed'     => $totalRawGold,
                'assigned_karigor_id' => $firstKarigorId,
                'estimated_price'     => $estimatedPrice,
                'grand_total'         => $estimatedPrice,
                'paid_amount'         => $advancePayment,
                'due_amount'          => $dueAmount,
                'order_date'          => now(),
                'delivery_date'       => $request->delivery_date,
                'status'              => 'in_production',
                'payment_status'      => $paymentStatus,
                'details'             => $request->details,
                'design_photo'        => $photoPath,
            ]);

            $masterJobId = null;

            // Create each CustomOrderItem and associated KarigorJob
            foreach ($rawItems as $idx => $it) {
                $itemPhotoPath = null;
                if ($request->hasFile("items.{$idx}.design_photo")) {
                    $itemFile = $request->file("items.{$idx}.design_photo");
                    $itemFilename = 'item_' . time() . '_' . $idx . '_' . rand(100, 999) . '.' . $itemFile->getClientOriginalExtension();
                    $itemFile->move(public_path('uploads/orders'), $itemFilename);
                    $itemPhotoPath = 'uploads/orders/' . $itemFilename;
                }

                $orderItem = CustomOrderItem::create([
                    'custom_order_id'     => $order->id,
                    'category_id'         => $it['category_id'] ?? null,
                    'product_name'        => $it['product_name'] ?? 'Jewelry Item #' . ($idx + 1),
                    'karat'               => $it['karat'] ?? '22K',
                    'quantity'            => (int) ($it['quantity'] ?? 1),
                    'target_bhori'        => (float) ($it['target_bhori'] ?? 0),
                    'target_ana'          => (float) ($it['target_ana'] ?? 0),
                    'target_roti'         => (float) ($it['target_roti'] ?? 0),
                    'target_point'        => (float) ($it['target_point'] ?? 0),
                    'target_gram'         => (float) ($it['target_gram'] ?? 0),
                    'raw_gold_needed'     => (float) ($it['raw_gold_needed'] ?? 0),
                    'unit_price_per_gram' => (float) ($it['unit_price_per_gram'] ?? 0),
                    'estimated_price'     => (float) ($it['estimated_price'] ?? 0),
                    'assigned_karigor_id' => $it['assigned_karigor_id'] ?? null,
                    'design_photo'        => $itemPhotoPath,
                    'details'             => $it['details'] ?? null,
                    'status'              => 'in_production',
                ]);

                if (!$photoPath && $itemPhotoPath) {
                    $photoPath = $itemPhotoPath;
                    $order->design_photo = $itemPhotoPath;
                    $order->save();
                }

                if (!empty($it['assigned_karigor_id'])) {
                    $alloyWeight = max(0, (float)$orderItem->target_gram - (float)$orderItem->raw_gold_needed);

                    // Check if raw material was given to karigor
                    $isRawGiven = !empty($it['is_raw_material_given']);
                    $givenAmount = floatval($it['given_raw_material'] ?? $orderItem->raw_gold_needed ?? 0);
                    $rawCatId = $orderItem->category_id ?? $order->category_id ?? 1;

                    $karigorJob = KarigorJob::create([
                        'custom_order_id'          => $order->id,
                        'karigor_id'               => $it['assigned_karigor_id'],
                        'assigned_by'              => auth()->id(),
                        'task_type'                => 'custom_order',
                        'status'                   => 'in_progress',
                        'given_gross_weight'       => $orderItem->target_gram,
                        'given_purity_weight'      => $orderItem->raw_gold_needed,
                        'assigned_extra_raw_gold'  => 0.000,
                        'is_raw_material_given'    => $isRawGiven && $givenAmount > 0 ? true : false,
                        'raw_material_category_id' => $rawCatId,
                        'given_raw_material'       => $isRawGiven && $givenAmount > 0 ? $givenAmount : 0,
                        'assigned_at'              => now(),
                        'notes'                    => "কাস্টম অর্ডার #{$orderNo} - {$orderItem->product_name} ({$orderItem->karat}) | মোট ওজন: " . number_format($orderItem->target_gram, 3) . " গ্রাম, পাকা সোনা: " . number_format($orderItem->raw_gold_needed, 3) . " গ্রাম, খাদ: " . number_format($alloyWeight, 3) . " গ্রাম" . ($isRawGiven && $givenAmount > 0 ? " | প্রদত্ত কাঁচামাল: " . number_format($givenAmount, 3) . " গ্রাম (Raw Stock থেকে বিয়োগকৃত)" : ""),
                    ]);

                    $orderItem->karigor_job_id = $karigorJob->id;
                    $orderItem->save();

                    // Deduct from Raw Stock if raw material is given
                    if ($isRawGiven && $givenAmount > 0) {
                        $kUser = $karigorJob->karigor;
                        $kName = $kUser ? ($kUser->name . ' ' . ($kUser->last_name ?? '')) : "ID #{$karigorJob->karigor_id}";
                        RawStock::deductStock($rawCatId, $givenAmount, [
                            'karigor_job_id'  => $karigorJob->id,
                            'custom_order_id' => $order->id,
                            'karigor_id'      => $karigorJob->karigor_id,
                            'reason'          => "কাস্টম অর্ডার #{$orderNo} ({$orderItem->product_name}) তৈরিতে কারিগর {$kName} কে কাঁচামাল প্রদান",
                            'created_by'      => auth()->id(),
                        ]);
                    }

                    if (!$masterJobId) {
                        $masterJobId = $karigorJob->id;
                    }
                }
            }

            if ($masterJobId) {
                $order->karigor_job_id = $masterJobId;
                $order->save();
            }

            // Record Advance Payment step if paid > 0
            if ($advancePayment > 0) {
                SellPayment::create([
                    'payment_type'          => 'custom_order',
                    'custom_order_id'       => $order->id,
                    'payment_step'          => 1,
                    'amount'                => $advancePayment,
                    'payment_method'        => $request->payment_method ?? 'cash',
                    'transaction_reference' => $request->transaction_reference,
                    'payment_date'          => now(),
                    'received_by'           => auth()->id(),
                    'note'                  => 'অর্ডারকালীন অগ্রিম পেমেন্ট (Advance Payment)',
                ]);
            }

            DB::commit();

            return redirect()->route('custom-orders.invoice', $order->id)
                ->with('success', "কাস্টম অর্ডার #{$orderNo} (" . count($rawItems) . " টি পণ্য) সফলভাবে গ্রহণ করা হয়েছে। গ্রাহক রসিদ ও কারিগর জব কার্ড প্রস্তুত।");
        } catch (\Exception $e) {
            DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Custom Order Creation Error: ' . $e->getMessage(), [
                'exception' => $e
            ]);

            $friendlyMsg = 'অর্ডার সংরক্ষণে সমস্যা হয়েছে। অনুগ্রহ করে সকল তথ্য (গ্রাহক, পণ্যের বিবরণ, ওজন ও কারিগর) সঠিকভাবে প্রদান করে পুনরায় চেষ্টা করুন।';
            
            // In debug mode, append a concise sanitized note without full raw SQL query dump
            if (config('app.debug')) {
                $rawMsg = $e->getMessage();
                $cleanMsg = preg_replace('/\(SQL:[\s\S]*\)/', '', $rawMsg);
                $friendlyMsg .= ' (কারিগরি নোট: ' . trim($cleanMsg) . ')';
            }

            return redirect()->back()
                ->withInput()
                ->with('error', $friendlyMsg);
        }
    }

    /**
     * Display Customer Pre-order Invoice / Receipt.
     */
    public function customerInvoice($id)
    {
        $order = CustomOrder::with([
            'customer',
            'category',
            'karigor',
            'payments.receiver',
            'items.category',
            'items.karigor',
        ])->findOrFail($id);

        $productPrices = ProductPrice::all()->keyBy('product_name');

        return view('admin.custom_order.customer_invoice', compact('order', 'productPrices'));
    }

    /**
     * Display Karigor Job Card / Work Order Invoice.
     */
    public function karigorInvoice(Request $request, $id, $karigor_id = null)
    {
        $order = CustomOrder::with([
            'customer',
            'category',
            'karigor',
            'karigorJob',
            'items.category',
            'items.karigor',
            'items.karigorJob',
        ])->findOrFail($id);

        // If a specific karigor_id is provided, filter items for that karigor
        $selectedKarigor = null;
        if ($karigor_id) {
            $selectedKarigor = User::findOrFail($karigor_id);
            $items = $order->items->where('assigned_karigor_id', $karigor_id);
        } else {
            $items = $order->items;
            $selectedKarigor = $order->karigor ?? ($order->items->first()->karigor ?? null);
        }

        // Get unique karigors assigned in this order for quick filter tabs
        $assignedKarigors = $order->items->pluck('karigor')->filter()->unique('id');

        // Resolve active job for status checking
        $activeJob = null;
        if ($selectedKarigor) {
            $activeJob = KarigorJob::where('custom_order_id', $order->id)
                ->where('karigor_id', $selectedKarigor->id)
                ->latest()
                ->first();
        }
        if (!$activeJob && $order->karigor_job_id) {
            $activeJob = $order->karigorJob;
        }
        if (!$activeJob) {
            $activeJob = KarigorJob::where('custom_order_id', $order->id)->latest()->first();
        }

        return view('admin.custom_order.karigor_invoice', compact('order', 'items', 'selectedKarigor', 'assignedKarigors', 'karigor_id', 'activeJob'));
    }

    /**
     * Show custom order details.
     */
    public function show($id)
    {
        $order = CustomOrder::with([
            'customer',
            'category',
            'karigor',
            'karigorJob',
            'payments.receiver',
            'items.category',
            'items.karigor',
            'items.karigorJob',
        ])->findOrFail($id);

        return view('admin.custom_order.show', compact('order'));
    }

    /**
     * Receive completed jewelry from Karigor.
     */
    public function receiveFromKarigor(Request $request, $id)
    {
        $request->validate([
            'actual_weight_gram' => 'required|numeric|min:0.001',
            'karigor_fee'        => 'required|numeric|min:0',
            'returned_raw_gold'  => 'nullable|numeric|min:0',
            'wastage_gold'       => 'nullable|numeric|min:0',
            'notes'              => 'nullable|string|max:500',
        ]);

        $order = CustomOrder::findOrFail($id);

        DB::beginTransaction();
        try {
            $order->actual_weight_gram = $request->actual_weight_gram;
            $order->karigor_fee = $request->karigor_fee;
            $order->status = 'ready_for_delivery';
            $order->save();

            // Update KarigorJob
            if ($order->karigor_job_id) {
                $job = KarigorJob::find($order->karigor_job_id);
                if ($job) {
                    $job->status = 'completed';
                    $job->returned_gross_weight = $request->actual_weight_gram;
                    $job->returned_raw_gold = $request->returned_raw_gold ?? 0;
                    $job->wastage_gold = $request->wastage_gold ?? 0;
                    $job->completed_at = now();
                    if ($request->filled('notes')) {
                        $job->notes = ($job->notes ? $job->notes . " | " : "") . $request->notes;
                    }
                    $job->save();
                }
            }

            // If any unused raw gold/material is returned from custom order, credit it back to RawStock
            if ($request->filled('returned_raw_gold') && floatval($request->returned_raw_gold) > 0) {
                $retAmount = floatval($request->returned_raw_gold);
                $rawCatId = $order->category_id ?? 1;
                $kUser = $order->karigor;
                $kName = $kUser ? ($kUser->name . ' ' . ($kUser->last_name ?? '')) : '';
                RawStock::addStock($rawCatId, $retAmount, [
                    'karigor_job_id'  => $order->karigor_job_id,
                    'custom_order_id' => $order->id,
                    'karigor_id'      => $order->assigned_karigor_id,
                    'reason'          => "কাস্টম অর্ডার #{$order->order_no} গ্রহণকালে কারিগর {$kName} থেকে অব্যবহৃত কাঁচামাল ফেরত",
                    'created_by'      => auth()->id(),
                ]);
            }

            // Ensure all KarigorJobs for this custom order are marked as completed
            KarigorJob::where('custom_order_id', $order->id)
                ->where('status', '!=', 'completed')
                ->update([
                    'status'       => 'completed',
                    'completed_at' => now(),
                ]);

            // Synchronize all custom order items to ready_for_delivery
            CustomOrderItem::where('custom_order_id', $order->id)->update([
                'status' => 'ready_for_delivery',
            ]);

            DB::commit();

            return redirect()->back()->with('success', "কারিগর থেকে পণ্য সফলভাবে গ্রহণ করা হয়েছে। অর্ডার ডেলিভারির জন্য প্রস্তুত।");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'গ্রহণে ত্রুটি: ' . $e->getMessage());
        }
    }

    /**
     * Final billing & delivery to customer.
     */
    public function deliverAndBill(Request $request, $id)
    {
        $request->validate([
            'actual_price'          => 'required|numeric|min:0',
            'karigor_fee'           => 'required|numeric|min:0',
            'discount'              => 'nullable|numeric|min:0',
            'delivery_payment'      => 'nullable|numeric|min:0',
            'payment_method'        => 'nullable|string',
            'transaction_reference' => 'nullable|string|max:255',
            'due_date'              => 'nullable|date',
            'delivery_notes'        => 'nullable|string|max:500',
        ]);

        $order = CustomOrder::findOrFail($id);

        DB::beginTransaction();
        try {
            $actualPrice = (float) $request->actual_price;
            $karigorFee = (float) $request->karigor_fee;
            $discount = (float) ($request->discount ?? 0);

            $grandTotal = max(0, $actualPrice + $karigorFee - $discount);
            $previousPaid = (float) $order->paid_amount;
            $deliveryPayment = (float) ($request->delivery_payment ?? 0);

            $newTotalPaid = $previousPaid + $deliveryPayment;
            $dueAmount = max(0, $grandTotal - $newTotalPaid);

            $paymentStatus = 'due';
            if ($dueAmount <= 0) {
                $paymentStatus = 'paid';
            } elseif ($newTotalPaid > 0) {
                $paymentStatus = 'partial';
            }

            $order->actual_price = $actualPrice;
            $order->karigor_fee = $karigorFee;
            $order->discount = $discount;
            $order->grand_total = $grandTotal;
            $order->paid_amount = $newTotalPaid;
            $order->due_amount = $dueAmount;
            $order->due_date = $request->due_date;
            $order->status = 'delivered';
            $order->payment_status = $paymentStatus;
            $order->delivery_date = now();
            if ($request->filled('delivery_notes')) {
                $order->details = ($order->details ? $order->details . "\n" : "") . "ডেলিভারি নোট: " . $request->delivery_notes;
            }
            $order->save();

            // Synchronize all custom order items to delivered
            CustomOrderItem::where('custom_order_id', $order->id)->update([
                'status' => 'delivered',
            ]);

            // Record delivery payment step if payment made
            if ($deliveryPayment > 0) {
                $nextStep = SellPayment::where('custom_order_id', $order->id)->count() + 1;

                SellPayment::create([
                    'payment_type'          => 'custom_order',
                    'custom_order_id'       => $order->id,
                    'payment_step'          => $nextStep,
                    'amount'                => $deliveryPayment,
                    'payment_method'        => $request->payment_method ?? 'cash',
                    'transaction_reference' => $request->transaction_reference,
                    'payment_date'          => now(),
                    'received_by'           => auth()->id(),
                    'note'                  => 'ডেলিভারিকালীন পেমেন্ট (Delivery Payment)',
                ]);
            }

            DB::commit();

            return redirect()->route('custom-orders.show', $order->id)
                ->with('success', "অর্ডার সফলভাবে ডেলিভারি সম্পন্ন হয়েছে এবং ইনভয়েস আপডেট করা হয়েছে।");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'ডেলিভারিতে ত্রুটি: ' . $e->getMessage());
        }
    }
}
