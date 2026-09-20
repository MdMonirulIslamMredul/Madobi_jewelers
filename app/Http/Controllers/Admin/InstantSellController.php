<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InstantSell;
use App\Models\InstantSellItem;
use App\Models\Purchase;
use App\Models\PurchaseLocationHistory;
use App\Models\SellPayment;
use App\Models\User;
use App\Models\ProductPrice;
use App\Models\KarigorMojuri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class InstantSellController extends Controller
{
    /**
     * Display a listing of instant sales.
     */
    public function index(Request $request)
    {
        $query = InstantSell::with(['customer', 'seller', 'items.purchase', 'payments'])->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('invoice_no', 'LIKE', "%{$search}%")
                  ->orWhereHas('customer', function ($cq) use ($search) {
                      $cq->where('name', 'LIKE', "%{$search}%")
                         ->orWhere('last_name', 'LIKE', "%{$search}%")
                         ->orWhere('phone', 'LIKE', "%{$search}%");
                  });
            });
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        $sells = $query->paginate(15)->appends($request->all());

        // Quick statistics
        $totalSalesAmount = InstantSell::sum('grand_total');
        $totalProfitAmount = InstantSell::sum('total_profit');
        $totalDueAmount = InstantSell::sum('due_amount');

        return view('admin.sell.shop_sell_index', compact('sells', 'totalSalesAmount', 'totalProfitAmount', 'totalDueAmount'));
    }

    /**
     * Show the form for creating a new instant sale (Shop POS).
     */
    public function create()
    {
        // Load only products currently in shop
        $shopProducts = Purchase::with(['product', 'productCategory'])
            ->where('location', 'is_shop')
            ->orderBy('id', 'desc')
            ->get();

        $productPrices = ProductPrice::orderBy('id', 'asc')->get();
        $karigorMojuris = KarigorMojuri::orderBy('category_name', 'asc')->orderBy('type', 'desc')->get();

        return view('admin.sell.shop_sell', compact('shopProducts', 'productPrices', 'karigorMojuris'));
    }

    /**
     * Store a newly created instant sale.
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_id'            => 'required|exists:users,id',
            'items'                  => 'required|array|min:1',
            'items.*.purchase_id'    => 'required|exists:purchases,id',
            'items.*.selling_price'  => 'required|numeric|min:0',
            'karigor_mojuri_id'      => 'nullable|exists:karigor_mojuris,id',
            'karigor_mojuri_rate'    => 'nullable|numeric|min:0',
            'karigor_mojuri_total'   => 'nullable|numeric|min:0',
            'discount'               => 'nullable|numeric|min:0',
            'paid_amount'            => 'nullable|numeric|min:0',
            'payment_method'         => 'nullable|string',
            'transaction_reference'  => 'nullable|string|max:255',
            'due_date'               => 'nullable|date',
            'notes'                  => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();
        try {
            $invoiceNo = 'SL-' . date('Ymd') . '-' . strtoupper(Str::random(5));

            $totalCost = 0;
            $subtotal = 0;
            $itemsData = [];

            foreach ($request->items as $item) {
                $purchase = Purchase::lockForUpdate()->findOrFail($item['purchase_id']);

                if ($purchase->location !== 'is_shop') {
                    throw new \Exception("পণ্য '{$purchase->product->product_name}' আর শপে উপলব্ধ নেই।");
                }

                $cost = (float) ($purchase->total_price ?? 0);
                $sellingPrice = (float) $item['selling_price'];
                $profit = $sellingPrice - $cost;

                $totalCost += $cost;
                $subtotal += $sellingPrice;

                $itemsData[] = [
                    'purchase'      => $purchase,
                    'purchase_cost' => $cost,
                    'selling_price' => $sellingPrice,
                    'profit'        => $profit,
                ];
            }

            $karigorMojuriId = $request->karigor_mojuri_id ?: null;
            $karigorMojuriRate = (float) ($request->karigor_mojuri_rate ?? 0);
            $karigorMojuriTotal = (float) ($request->karigor_mojuri_total ?? 0);

            $discount = (float) ($request->discount ?? 0);
            $grandTotal = max(0, $subtotal + $karigorMojuriTotal - $discount);
            $totalProfit = $grandTotal - $totalCost;

            $paidAmount = min($grandTotal, (float) ($request->paid_amount ?? 0));
            $dueAmount = max(0, $grandTotal - $paidAmount);

            $paymentStatus = 'due';
            if ($dueAmount <= 0) {
                $paymentStatus = 'paid';
            } elseif ($paidAmount > 0) {
                $paymentStatus = 'partial';
            }

            // Create InstantSell record
            $sell = InstantSell::create([
                'invoice_no'           => $invoiceNo,
                'customer_id'          => $request->customer_id,
                'seller_id'            => auth()->id(),
                'total_cost'           => $totalCost,
                'subtotal'             => $subtotal,
                'karigor_mojuri_id'    => $karigorMojuriId,
                'karigor_mojuri_rate'  => $karigorMojuriRate,
                'karigor_mojuri_total' => $karigorMojuriTotal,
                'discount'             => $discount,
                'vat_tax'              => 0,
                'grand_total'          => $grandTotal,
                'total_profit'         => $totalProfit,
                'paid_amount'          => $paidAmount,
                'due_amount'           => $dueAmount,
                'due_date'             => $request->due_date,
                'payment_status'       => $paymentStatus,
                'notes'                => $request->notes,
            ]);

            // Save items and update purchase statuses
            foreach ($itemsData as $row) {
                $p = $row['purchase'];

                InstantSellItem::create([
                    'instant_sell_id' => $sell->id,
                    'purchase_id'     => $p->id,
                    'product_name'    => $p->product->product_name ?? 'N/A',
                    'category_name'   => $p->productCategory->category_name ?? 'N/A',
                    'karat'           => $p->karat,
                    'bhori'           => $p->bhori ?? 0,
                    'ana'             => $p->ana ?? 0,
                    'roti'            => $p->roti ?? 0,
                    'point'           => $p->point ?? 0,
                    'gram'            => $p->gram ?? 0,
                    'purchase_cost'   => $row['purchase_cost'],
                    'selling_price'   => $row['selling_price'],
                    'profit'          => $row['profit'],
                ]);

                // Update location to is_sold
                $p->location = 'is_sold';
                $p->save();

                // Track location history
                PurchaseLocationHistory::create([
                    'purchase_id'    => $p->id,
                    'from_location'  => 'is_shop',
                    'to_location'    => 'is_sold',
                    'transferred_by' => auth()->id(),
                    'note'           => "বিক্রয় করা হয়েছে (ইনভয়েস #{$invoiceNo}, গ্রাহক: {$sell->customer->name})",
                ]);
            }

            // Record initial payment step if paid > 0
            if ($paidAmount > 0) {
                SellPayment::create([
                    'payment_type'          => 'instant_sell',
                    'instant_sell_id'       => $sell->id,
                    'payment_step'          => 1,
                    'amount'                => $paidAmount,
                    'payment_method'        => $request->payment_method ?? 'cash',
                    'transaction_reference' => $request->transaction_reference,
                    'payment_date'          => now(),
                    'received_by'           => auth()->id(),
                    'note'                  => 'বিক্রয়কালীন পরিশোধ (Initial Payment)',
                ]);
            }

            DB::commit();

            return redirect()->route('instant-sells.show', $sell->id)
                ->with('success', 'পণ্য সফলভাবে বিক্রয় সম্পন্ন হয়েছে এবং ইনভয়েস তৈরি হয়েছে।');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'বিক্রয় সম্পন্ন হতে ত্রুটি হয়েছে: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified invoice.
     */
    public function show($id)
    {
        $sell = InstantSell::with(['customer', 'seller', 'items.purchase', 'payments.receiver', 'karigorMojuri'])->findOrFail($id);
        return view('admin.sell.invoice', compact('sell'));
    }

    /**
     * Get location and movement history of a shop product.
     */
    public function productHistory($id)
    {
        $purchase = Purchase::with([
            'product',
            'productCategory',
            'locationHistories.transferredBy',
            'locationHistories.karigor'
        ])->findOrFail($id);

        $locationNames = [
            'is_shop'      => 'শপ (Shop)',
            'is_warehouse' => 'গুদাম (Warehouse)',
            'is_karigor'   => 'কারিগর (Karigor)',
            'is_hold'      => 'হোল্ড (Hold)',
            'is_sold'      => 'বিক্রিত (Sold)',
        ];

        $histories = $purchase->locationHistories->map(function ($h) use ($locationNames) {
            return [
                'from'           => $locationNames[$h->from_location] ?? ($h->from_location ?: 'নতুন ক্রয় / প্রাথমিক মজুত'),
                'to'             => $locationNames[$h->to_location] ?? $h->to_location,
                'date'           => $h->created_at ? $h->created_at->format('d M Y, h:i A') : '—',
                'transferred_by' => $h->transferredBy->name ?? 'System',
                'karigor_name'   => $h->karigor ? trim($h->karigor->name . ' ' . ($h->karigor->last_name ?? '')) : null,
                'karigor_phone'  => $h->karigor ? $h->karigor->phone : null,
                'task_type'      => $h->task_type,
                'extra_raw_gold' => $h->extra_raw_gold,
                'note'           => $h->note,
            ];
        });

        return response()->json([
            'status' => 'success',
            'data'   => [
                'id'          => $purchase->id,
                'name'        => $purchase->product->product_name ?? 'N/A',
                'category'    => $purchase->productCategory->category_name ?? 'N/A',
                'karat'       => $purchase->karat,
                'cost'        => number_format($purchase->total_price, 2),
                'weight'      => "{$purchase->bhori}ভরি {$purchase->ana}আনা {$purchase->roti}রতি {$purchase->point}পয়েন্ট (" . number_format($purchase->gram, 3) . " গ্রাম)",
                'location'          => $locationNames[$purchase->location] ?? $purchase->location,
                'purchase_show_url' => route('purchase.show', $purchase->transaction_id ?? $purchase->id),
                'histories'         => $histories,
            ]
        ]);
    }
}
