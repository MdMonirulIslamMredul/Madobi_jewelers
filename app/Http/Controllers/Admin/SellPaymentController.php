<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InstantSell;
use App\Models\CustomOrder;
use App\Models\SellPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SellPaymentController extends Controller
{
    /**
     * Get payment history for an instant sell or custom order.
     */
    public function history(Request $request)
    {
        $type = $request->get('type');
        $id = $request->get('id');

        if ($type === 'instant_sell') {
            $record = InstantSell::with(['customer'])->findOrFail($id);
            $payments = SellPayment::with('receiver')->where('instant_sell_id', $id)->orderBy('payment_step', 'asc')->get();
            $invoiceOrOrderNo = $record->invoice_no;
        } else {
            $record = CustomOrder::with(['customer'])->findOrFail($id);
            $payments = SellPayment::with('receiver')->where('custom_order_id', $id)->orderBy('payment_step', 'asc')->get();
            $invoiceOrOrderNo = $record->order_no;
        }

        return response()->json([
            'status' => 'success',
            'data'   => [
                'type'             => $type,
                'id'               => $record->id,
                'no'               => $invoiceOrOrderNo,
                'customer_name'    => $record->customer->name ?? 'N/A',
                'customer_phone'   => $record->customer->phone ?? 'N/A',
                'grand_total'      => number_format($record->grand_total, 2),
                'paid_amount'      => number_format($record->paid_amount, 2),
                'due_amount'       => number_format($record->due_amount, 2),
                'raw_due'          => (float) $record->due_amount,
                'payment_status'   => $record->payment_status,
                'payments'         => $payments->map(function ($p) {
                    return [
                        'step'        => $p->payment_step,
                        'amount'      => number_format($p->amount, 2),
                        'method'      => strtoupper($p->payment_method),
                        'reference'   => $p->transaction_reference ?? '-',
                        'date'        => $p->payment_date->format('d M, Y h:i A'),
                        'receiver'    => $p->receiver->name ?? 'Admin',
                        'note'        => $p->note ?? '-',
                    ];
                }),
            ]
        ]);
    }

    /**
     * Collect another installment / partial payment.
     */
    public function collectPayment(Request $request)
    {
        $request->validate([
            'payment_type'          => 'required|in:instant_sell,custom_order',
            'record_id'             => 'required|integer',
            'amount'                => 'required|numeric|min:1',
            'payment_method'        => 'required|string',
            'transaction_reference' => 'nullable|string|max:255',
            'note'                  => 'nullable|string|max:255',
        ]);

        $type = $request->payment_type;
        $id = $request->record_id;
        $amount = (float) $request->amount;

        DB::beginTransaction();
        try {
            if ($type === 'instant_sell') {
                $record = InstantSell::lockForUpdate()->findOrFail($id);
                $stepCount = SellPayment::where('instant_sell_id', $id)->count();

                if ($amount > $record->due_amount) {
                    throw new \Exception("পরিশোধের পরিমাণ বকেয়ার পরিমাণ ({$record->due_amount} ৳) এর চেয়ে বেশি হতে পারে না।");
                }

                $newPaid = $record->paid_amount + $amount;
                $newDue = max(0, $record->due_amount - $amount);
                $record->paid_amount = $newPaid;
                $record->due_amount = $newDue;
                $record->payment_status = ($newDue <= 0) ? 'paid' : 'partial';
                $record->save();

                SellPayment::create([
                    'payment_type'          => 'instant_sell',
                    'instant_sell_id'       => $record->id,
                    'payment_step'          => $stepCount + 1,
                    'amount'                => $amount,
                    'payment_method'        => $request->payment_method,
                    'transaction_reference' => $request->transaction_reference,
                    'payment_date'          => now(),
                    'received_by'           => auth()->id(),
                    'note'                  => $request->note ?? ("ধাপ #" . ($stepCount + 1) . " কিস্তি পরিশোধ"),
                ]);
            } else {
                $record = CustomOrder::lockForUpdate()->findOrFail($id);
                $stepCount = SellPayment::where('custom_order_id', $id)->count();

                if ($amount > $record->due_amount) {
                    throw new \Exception("পরিশোধের পরিমাণ বকেয়ার পরিমাণ ({$record->due_amount} ৳) এর চেয়ে বেশি হতে পারে না।");
                }

                $newPaid = $record->paid_amount + $amount;
                $newDue = max(0, $record->due_amount - $amount);
                $record->paid_amount = $newPaid;
                $record->due_amount = $newDue;
                $record->payment_status = ($newDue <= 0) ? 'paid' : 'partial';
                $record->save();

                SellPayment::create([
                    'payment_type'          => 'custom_order',
                    'custom_order_id'       => $record->id,
                    'payment_step'          => $stepCount + 1,
                    'amount'                => $amount,
                    'payment_method'        => $request->payment_method,
                    'transaction_reference' => $request->transaction_reference,
                    'payment_date'          => now(),
                    'received_by'           => auth()->id(),
                    'note'                  => $request->note ?? ("ধাপ #" . ($stepCount + 1) . " কিস্তি পরিশোধ"),
                ]);
            }

            DB::commit();

            return redirect()->back()->with('success', 'কিস্তি পেমেন্ট সফলভাবে গ্রহণ করা হয়েছে।');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'পেমেন্ট সংরক্ষণে ত্রুটি: ' . $e->getMessage());
        }
    }
}
