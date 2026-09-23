<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use App\Models\RawMaterialPurchase;
use App\Models\RawStock;
use App\Models\RawStockHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class RawStockController extends Controller
{
    /**
     * Display Raw Stocks overview, raw purchases list (CRUD), and audit transaction log.
     */
    public function index(Request $request)
    {
        $categories = ProductCategory::all();
        $rawStocks = RawStock::with('productCategory')->get()->keyBy('category_id');

        // 1. Raw Material Purchases Query (CRUD List)
        $purchaseQuery = RawMaterialPurchase::with(['productCategory', 'createdBy'])->latest('purchase_date')->latest('id');

        if ($request->filled('purchase_category_id') && $request->purchase_category_id !== 'all') {
            $purchaseQuery->where('category_id', $request->purchase_category_id);
        }

        if ($request->filled('search')) {
            $s = trim($request->search);
            $purchaseQuery->where(function ($q) use ($s) {
                $q->where('invoice_no', 'like', "%{$s}%")
                  ->orWhere('supplier_name', 'like', "%{$s}%")
                  ->orWhere('supplier_phone', 'like', "%{$s}%")
                  ->orWhere('material_name', 'like', "%{$s}%");
            });
        }

        if ($request->filled('purchase_from_date')) {
            $purchaseQuery->whereDate('purchase_date', '>=', $request->purchase_from_date);
        }

        if ($request->filled('purchase_to_date')) {
            $purchaseQuery->whereDate('purchase_date', '<=', $request->purchase_to_date);
        }

        if ($request->filled('payment_status') && $request->payment_status !== 'all') {
            if ($request->payment_status === 'paid') {
                $purchaseQuery->where('due_amount', '<=', 0);
            } elseif ($request->payment_status === 'due') {
                $purchaseQuery->where('due_amount', '>', 0);
            }
        }

        $purchases = $purchaseQuery->paginate(15, ['*'], 'purchases_page')->withQueryString();

        // Totals for Purchases
        $totalPurchaseAmount = RawMaterialPurchase::sum('total_amount');
        $totalPaidAmount = RawMaterialPurchase::sum('paid_amount');
        $totalDueAmount = RawMaterialPurchase::sum('due_amount');
        $totalPurchasedGrams = RawMaterialPurchase::sum('gram');

        // 2. Audit History Query
        $historyQuery = RawStockHistory::with([
            'productCategory',
            'karigorJob',
            'customOrder',
            'purchase',
            'rawMaterialPurchase',
            'karigor',
            'createdBy'
        ])->latest('id');

        if ($request->filled('category_id') && $request->category_id !== 'all') {
            $historyQuery->where('category_id', $request->category_id);
        }

        if ($request->filled('type') && $request->type !== 'all') {
            $historyQuery->where('type', $request->type);
        }

        if ($request->filled('from_date')) {
            $historyQuery->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $historyQuery->whereDate('created_at', '<=', $request->to_date);
        }

        $histories = $historyQuery->paginate(20, ['*'], 'history_page')->withQueryString();

        // 3. Category Specific Stocks for Easy Access
        $goldCategory = $categories->first(function ($c) {
            return stripos($c->category_slug, 'gold') !== false || stripos($c->category_name, 'gold') !== false;
        });
        $rupaCategory = $categories->first(function ($c) {
            return stripos($c->category_slug, 'rupa') !== false || stripos($c->category_name, 'silver') !== false;
        });
        $diamondCategory = $categories->first(function ($c) {
            return stripos($c->category_slug, 'diamond') !== false;
        });
        $platinumCategory = $categories->first(function ($c) {
            return stripos($c->category_slug, 'platinum') !== false;
        });

        $goldStock = $goldCategory && isset($rawStocks[$goldCategory->id]) ? $rawStocks[$goldCategory->id] : null;
        $rupaStock = $rupaCategory && isset($rawStocks[$rupaCategory->id]) ? $rawStocks[$rupaCategory->id] : null;
        $diamondStock = $diamondCategory && isset($rawStocks[$diamondCategory->id]) ? $rawStocks[$diamondCategory->id] : null;
        $platinumStock = $platinumCategory && isset($rawStocks[$platinumCategory->id]) ? $rawStocks[$platinumCategory->id] : null;

        return view('admin.raw_stock.index', compact(
            'categories',
            'rawStocks',
            'purchases',
            'histories',
            'goldStock',
            'rupaStock',
            'diamondStock',
            'platinumStock',
            'totalPurchaseAmount',
            'totalPaidAmount',
            'totalDueAmount',
            'totalPurchasedGrams'
        ));
    }

    /**
     * Store new Raw Material Purchase (CRUD: Create).
     */
    public function purchaseStore(Request $request)
    {
        $request->validate([
            'category_id'     => 'required|exists:product_categories,id',
            'purchase_date'   => 'required|date',
            'gram'            => 'required|numeric|min:0.001',
            'invoice_no'      => 'nullable|string|max:100',
            'supplier_name'   => 'nullable|string|max:255',
            'supplier_phone'  => 'nullable|string|max:50',
            'material_name'   => 'nullable|string|max:255',
            'karat'           => 'nullable|string|max:50',
            'unit_price'      => 'nullable|numeric|min:0',
            'rate_type'       => 'nullable|in:per_gram,per_bhori',
            'total_amount'    => 'nullable|numeric|min:0',
            'paid_amount'     => 'nullable|numeric|min:0',
            'due_amount'      => 'nullable|numeric|min:0',
            'payment_method'  => 'nullable|string|max:100',
            'carat'           => 'nullable|numeric|min:0',
            'notes'           => 'nullable|string|max:1000',
        ]);

        return DB::transaction(function () use ($request) {
            $category = ProductCategory::findOrFail($request->category_id);
            $gram = floatval($request->gram);
            $units = RawStock::convertGramToUnits($gram);

            // Invoice number generation
            $invoiceNo = $request->filled('invoice_no') 
                ? trim($request->invoice_no) 
                : ('RAW-' . date('Ymd') . '-' . strtoupper(Str::random(4)));

            // Calculate pricing if needed
            $rateType = $request->rate_type ?? 'per_gram';
            $unitPrice = floatval($request->unit_price ?? 0);
            $totalAmount = floatval($request->total_amount ?? 0);

            if ($totalAmount <= 0 && $unitPrice > 0) {
                if ($rateType === 'per_bhori') {
                    $ratePerGram = $unitPrice / 11.664;
                    $totalAmount = round($gram * $ratePerGram, 2);
                } else {
                    $totalAmount = round($gram * $unitPrice, 2);
                }
            }

            $paidAmount = floatval($request->paid_amount ?? 0);
            $dueAmount = $request->filled('due_amount') 
                ? floatval($request->due_amount) 
                : max(0, $totalAmount - $paidAmount);

            // 1. Create Raw Material Purchase Record
            $purchase = RawMaterialPurchase::create([
                'category_id'    => $category->id,
                'invoice_no'     => $invoiceNo,
                'supplier_name'  => $request->supplier_name,
                'supplier_phone' => $request->supplier_phone,
                'purchase_date'  => $request->purchase_date,
                'material_name'  => $request->material_name ?: ($category->category_name . ' কাঁচামাল'),
                'karat'          => $request->karat,
                'gram'           => $gram,
                'bhori'          => $units['bhori'],
                'ana'            => $units['ana'],
                'roti'           => $units['roti'],
                'point'          => $units['point'],
                'carat'          => floatval($request->carat ?? 0),
                'unit_price'     => $unitPrice,
                'rate_type'      => $rateType,
                'total_amount'   => $totalAmount,
                'paid_amount'    => $paidAmount,
                'due_amount'     => $dueAmount,
                'payment_method' => $request->payment_method ?: 'Cash',
                'notes'          => $request->notes,
                'created_by'     => auth()->id(),
            ]);

            // 2. Add Stock to master raw_stocks & log history
            $supplierText = $request->supplier_name ? " (সাপ্লায়ার: {$request->supplier_name})" : "";
            $reason = "কাঁচামাল ক্রয় - মেমো #{$invoiceNo}{$supplierText}";

            $stock = RawStock::addStock($category->id, $gram, [
                'raw_material_purchase_id' => $purchase->id,
                'reason'                   => $reason,
                'notes'                    => $request->notes,
                'carat'                    => $request->carat,
                'created_by'               => auth()->id(),
            ]);

            // Associate stock ID
            if ($stock) {
                $purchase->raw_stock_id = $stock->id;
                $purchase->save();
            }

            return redirect()->back()->with('success', "কাঁচামাল ক্রয় সফলভাবে সংরক্ষিত হয়েছে! মেমো নং: {$invoiceNo}, মোট ওজন: " . number_format($gram, 3) . " গ্রাম ✅");
        });
    }

    /**
     * Show Raw Material Purchase details (CRUD: Read / AJAX).
     */
    public function purchaseShow($id)
    {
        $purchase = RawMaterialPurchase::with(['productCategory', 'createdBy'])->findOrFail($id);

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success'  => true,
                'purchase' => $purchase,
                'units'    => [
                    'bhori' => $purchase->bhori,
                    'ana'   => $purchase->ana,
                    'roti'  => $purchase->roti,
                    'point' => $purchase->point,
                ],
            ]);
        }

        return redirect()->route('raw-stock.purchase.invoice', $id);
    }

    /**
     * Update Raw Material Purchase (CRUD: Update).
     */
    public function purchaseUpdate(Request $request, $id)
    {
        $request->validate([
            'category_id'     => 'required|exists:product_categories,id',
            'purchase_date'   => 'required|date',
            'gram'            => 'required|numeric|min:0.001',
            'invoice_no'      => 'required|string|max:100',
            'supplier_name'   => 'nullable|string|max:255',
            'supplier_phone'  => 'nullable|string|max:50',
            'material_name'   => 'nullable|string|max:255',
            'karat'           => 'nullable|string|max:50',
            'unit_price'      => 'nullable|numeric|min:0',
            'rate_type'       => 'nullable|in:per_gram,per_bhori',
            'total_amount'    => 'nullable|numeric|min:0',
            'paid_amount'     => 'nullable|numeric|min:0',
            'due_amount'      => 'nullable|numeric|min:0',
            'payment_method'  => 'nullable|string|max:100',
            'carat'           => 'nullable|numeric|min:0',
            'notes'           => 'nullable|string|max:1000',
        ]);

        return DB::transaction(function () use ($request, $id) {
            $purchase = RawMaterialPurchase::findOrFail($id);
            $oldCatId = $purchase->category_id;
            $oldGram = floatval($purchase->gram);

            $newCatId = intval($request->category_id);
            $newGram = floatval($request->gram);
            $units = RawStock::convertGramToUnits($newGram);

            // Re-calculate pricing
            $rateType = $request->rate_type ?? 'per_gram';
            $unitPrice = floatval($request->unit_price ?? 0);
            $totalAmount = floatval($request->total_amount ?? 0);

            if ($totalAmount <= 0 && $unitPrice > 0) {
                if ($rateType === 'per_bhori') {
                    $ratePerGram = $unitPrice / 11.664;
                    $totalAmount = round($newGram * $ratePerGram, 2);
                } else {
                    $totalAmount = round($newGram * $unitPrice, 2);
                }
            }

            $paidAmount = floatval($request->paid_amount ?? 0);
            $dueAmount = $request->filled('due_amount') 
                ? floatval($request->due_amount) 
                : max(0, $totalAmount - $paidAmount);

            // Synchronize Stock changes
            if ($oldCatId === $newCatId) {
                $diff = $newGram - $oldGram;
                if ($diff > 0.0001) {
                    RawStock::addStock($newCatId, $diff, [
                        'raw_material_purchase_id' => $purchase->id,
                        'reason' => "কাঁচামাল ক্রয় মেমো #{$purchase->invoice_no} সম্পাদন: ওজন বৃদ্ধি (+ " . number_format($diff, 3) . " গ্রাম)",
                        'created_by' => auth()->id(),
                    ]);
                } elseif ($diff < -0.0001) {
                    RawStock::deductStock($newCatId, abs($diff), [
                        'raw_material_purchase_id' => $purchase->id,
                        'reason' => "কাঁচামাল ক্রয় মেমো #{$purchase->invoice_no} সম্পাদন: ওজন হ্রাস (- " . number_format(abs($diff), 3) . " গ্রাম)",
                        'created_by' => auth()->id(),
                    ]);
                }
            } else {
                // Category changed: deduct from old category and add to new category
                if ($oldGram > 0) {
                    RawStock::deductStock($oldCatId, $oldGram, [
                        'raw_material_purchase_id' => $purchase->id,
                        'reason' => "কাঁচামাল ক্রয় মেমো #{$purchase->invoice_no} ক্যাটাগরি পরিবর্তন (পুরাতন ক্যাটাগরি থেকে বাদ)",
                        'created_by' => auth()->id(),
                    ]);
                }
                RawStock::addStock($newCatId, $newGram, [
                    'raw_material_purchase_id' => $purchase->id,
                    'reason' => "কাঁচামাল ক্রয় মেমো #{$purchase->invoice_no} নতুন ক্যাটাগরিতে স্থানান্তর",
                    'created_by' => auth()->id(),
                ]);
            }

            // Update Purchase Record
            $purchase->update([
                'category_id'    => $newCatId,
                'invoice_no'     => trim($request->invoice_no),
                'supplier_name'  => $request->supplier_name,
                'supplier_phone' => $request->supplier_phone,
                'purchase_date'  => $request->purchase_date,
                'material_name'  => $request->material_name,
                'karat'          => $request->karat,
                'gram'           => $newGram,
                'bhori'          => $units['bhori'],
                'ana'            => $units['ana'],
                'roti'           => $units['roti'],
                'point'          => $units['point'],
                'carat'          => floatval($request->carat ?? 0),
                'unit_price'     => $unitPrice,
                'rate_type'      => $rateType,
                'total_amount'   => $totalAmount,
                'paid_amount'    => $paidAmount,
                'due_amount'     => $dueAmount,
                'payment_method' => $request->payment_method ?: 'Cash',
                'notes'          => $request->notes,
            ]);

            return redirect()->back()->with('success', "কাঁচামাল ক্রয় মেমো #{$purchase->invoice_no} সফলভাবে আপডেট ও স্টক সমন্বয় করা হয়েছে ✅");
        });
    }

    /**
     * Delete Raw Material Purchase and reverse stock (CRUD: Delete).
     */
    public function purchaseDestroy($id)
    {
        return DB::transaction(function () use ($id) {
            $purchase = RawMaterialPurchase::findOrFail($id);
            $gram = floatval($purchase->gram);
            $catId = $purchase->category_id;
            $invoiceNo = $purchase->invoice_no;

            // Revert stock from master
            if ($gram > 0) {
                RawStock::deductStock($catId, $gram, [
                    'reason'     => "কাঁচামাল ক্রয় মেমো #{$invoiceNo} মোছা হয়েছে (স্টক হতে " . number_format($gram, 3) . " গ্রাম ফেরত)",
                    'created_by' => auth()->id(),
                ]);
            }

            $purchase->delete();

            return redirect()->back()->with('success', "কাঁচামাল ক্রয় মেমো #{$invoiceNo} মুছে ফেলা হয়েছে এবং স্টক সফলভাবে সমন্বয় করা হয়েছে 🗑️");
        });
    }

    /**
     * Print Raw Material Purchase Voucher / Invoice.
     */
    public function purchaseInvoice($id)
    {
        $purchase = RawMaterialPurchase::with(['productCategory', 'createdBy', 'rawStock'])->findOrFail($id);
        return view('admin.raw_stock.invoice', compact('purchase'));
    }

    /**
     * Store manual raw stock addition (e.g. initial setup / direct addition).
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_id'   => 'required|exists:product_categories,id',
            'gram'          => 'required|numeric|min:0.001',
            'cost_per_gram' => 'nullable|numeric|min:0',
            'carat'         => 'nullable|numeric|min:0',
            'reason'        => 'nullable|string|max:255',
            'notes'         => 'nullable|string|max:500',
        ]);

        $category = ProductCategory::findOrFail($request->category_id);
        $reason = $request->filled('reason') ? $request->reason : "ম্যানুয়াল স্টক ইনপুট ({$category->category_name})";

        $stock = RawStock::addStock($request->category_id, $request->gram, [
            'reason'     => $reason,
            'notes'      => $request->notes,
            'carat'      => $request->carat,
            'created_by' => auth()->id(),
        ]);

        if ($request->filled('cost_per_gram') && floatval($request->cost_per_gram) > 0) {
            $stock->cost_per_gram = $request->cost_per_gram;
            $stock->total_cost = floatval($stock->gram) * floatval($request->cost_per_gram);
            $stock->save();
        }

        return redirect()->back()->with('success', "{$category->category_name} কাঁচা স্টকে " . number_format($request->gram, 3) . " গ্রাম সফলভাবে যোগ করা হয়েছে ✅");
    }

    /**
     * Adjust raw stock to a specific target value.
     */
    public function adjust(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:product_categories,id',
            'target_gram' => 'required|numeric|min:0',
            'reason'      => 'required|string|max:255',
            'notes'       => 'nullable|string|max:500',
        ]);

        $stock = RawStock::where('category_id', $request->category_id)->first();
        if (!$stock) {
            $stock = RawStock::addStock($request->category_id, $request->target_gram, [
                'reason' => 'Initial stock setup',
            ]);
            return redirect()->back()->with('success', 'কাঁচা স্টক সফলভাবে সংরক্ষিত হয়েছে ✅');
        }

        $prevGram = floatval($stock->gram);
        $newGram = floatval($request->target_gram);
        $diff = $newGram - $prevGram;
        $units = RawStock::convertGramToUnits($newGram);
        $diffUnits = RawStock::convertGramToUnits(abs($diff));

        $stock->gram  = number_format($newGram, 3, '.', '');
        $stock->bhori = $units['bhori'];
        $stock->ana   = $units['ana'];
        $stock->roti  = $units['roti'];
        $stock->point = $units['point'];
        $stock->save();

        RawStockHistory::create([
            'raw_stock_id'  => $stock->id,
            'category_id'   => $stock->category_id,
            'type'          => 'adjustment',
            'gram'          => abs($diff),
            'bhori'         => $diffUnits['bhori'],
            'ana'           => $diffUnits['ana'],
            'roti'          => $diffUnits['roti'],
            'point'         => $diffUnits['point'],
            'previous_gram' => $prevGram,
            'current_gram'  => $newGram,
            'reason'        => $request->reason . ' (সমন্বয়: ' . ($diff >= 0 ? '+' : '-') . number_format(abs($diff), 3) . ' গ্রাম)',
            'created_by'    => auth()->id(),
            'notes'         => $request->notes,
        ]);

        return redirect()->back()->with('success', 'কাঁচা স্টক সফলভাবে সমন্বয় করা হয়েছে ✅');
    }

    /**
     * API to fetch current raw stock for a category.
     */
    public function getStock($categoryId)
    {
        $stock = RawStock::where('category_id', $categoryId)->first();
        return response()->json([
            'available' => $stock ? floatval($stock->gram) : 0,
            'bhori'     => $stock ? $stock->bhori : 0,
            'ana'       => $stock ? $stock->ana : 0,
            'roti'      => $stock ? $stock->roti : 0,
            'point'     => $stock ? $stock->point : 0,
            'carat'     => $stock ? floatval($stock->carat) : 0,
            'formatted' => $stock ? (number_format($stock->gram, 3) . " গ্রাম (" . $stock->bhori . " ভরি " . $stock->ana . " আনা " . $stock->roti . " রতি " . $stock->point . " পয়েন্ট)") : "০.০০০ গ্রাম",
        ]);
    }
}
