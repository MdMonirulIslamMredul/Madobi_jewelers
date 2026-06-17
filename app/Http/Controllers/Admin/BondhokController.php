<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BProduct;
use App\Models\Bondhok;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Invoice;

require_once app_path('helpers.php');

class BondhokController extends Controller
{
    public function b_customer_index()
    {
        $b_customers=User::with('bondhok')->whereHas('bondhok')->latest()->get();
        return view('admin.bondhok.b_customer.index',compact('b_customers'));
    }

    public function b_product_index()
    {
        $b_products=BProduct::latest()->get();
        return view('admin.bondhok.b_product.index',compact('b_products'));
    }

    public function b_product_edit($id)
    {
        $b_product=BProduct::find($id);
        return view('admin.bondhok.b_product.edit',compact('b_product'));
    }

    public function b_customer_edit($id)
    {
        $b_customer=User::find($id);
        return view('admin.bondhok.b_customer.edit',compact('b_customer'));
    }


    public function b_product_store(Request $request)
    {
        $b_products=new BProduct;
        $b_products->name= $request->name ?? null ;
        $b_products->save();

        return redirect()->route('bondhok.product')->with('message','Product Created Successfully');
    }


    public function b_product_update(Request $request)
    {
        $b_products= BProduct::find($request->p_id);
        $b_products->name= $request->name ?? null ;
        $b_products->save();

        return redirect()->route('bondhok.product')->with('message','Product Updated Successfully');
    }

    public function b_product_delete(Request $request)
    {
        $b_products = BProduct::find($request->p_id);
        if ($b_products) {
            $b_products->delete();
            return redirect()->route('bondhok.product')->with('message', 'Product Deleted Successfully');
        } else {
            return redirect()->route('bondhok.product')->with('error', 'Product Not Found');
        }
    }
    public function b_customer_delete(Request $request)
    {
        $b_customer = User::find($request->c_id);
        if ($b_customer) {
            $b_customer->delete();
            return redirect()->route('bondhok.customer')->with('message', 'Customer Deleted Successfully');
        } else {
            return redirect()->route('bondhok.customer')->with('error', 'Customer Not Found');
        }
    }

    public function bondhok_index()
    {
        $transactions = Transaction::with('bondhoks')->whereHas('bondhoks', function ($query) {
            $query->whereNotNull('transaction_id');
        })->get();
        // $bondhok=Bondhok::with('user')->with('product')->latest()->get();
        $customer=User::latest()->get();
        $product=BProduct::latest()->get();
        return view('admin.bondhok.bondhok',compact('transactions','customer','product'));
    }

    public function bondhok_store(Request $request)
    {
        // dd($request);

        $transaction = Transaction::create([
            'user_id' => $request->user_id,
            'category_id' => $request->type_id,
            'product_id' => $request->type_id,
            'details' => $request->details,
            'total_price' => $request->payable_amount,
            'adv_payment' => 0,
            'due_payment' => $request->payable_amount,
            'due_payment_date' => $request->end_time,
            'total_payment' => 0,
        ]);

        $invoice = Invoice::create([
            'user_id' => $request->user_id,
            'category_id' => $request->type_id,
            'product_id' => $request->type_id,
            'transaction_id' => $transaction->id,
            'invoice_no' => 'INV-' . rand(),
            'details' => $request->details,
            'total_price' => $request->payable_amount,
            'adv_payment' => 0,
            'due_payment' => $request->payable_amount,
            'due_payment_date' => $request->end_time,
            'total_payment' => 0,
        ]);

        if ($request->photo) {
            $imageName = 'bondhok'. time() . '.' . $request->photo->extension();
            $image = $request->photo->move(public_path('user/bondhok'), $imageName);
        } else {
            $imageName = 'default-cover.jpg'; // default image
        }

        for ($i = 0; $i < count($request->bhori); $i++) {
            $request_gold = [
                'vori' => $request->bhori[$i],
                'ana' => $request->ana[$i],
                'roti' => $request->roti[$i],
                'point' => $request->point[$i],
            ];
        $total=calculateTotalGold($request_gold);

        $bondhok=new Bondhok;
        $bondhok->user_id= $request->user_id;
        $bondhok->product_type_id= $request->type_id ?? null ;
        $bondhok->transaction_id= $transaction->id ?? null ;
        $bondhok->invoice_id= $invoice->id ?? null ;
        $bondhok->karat= $request->karat[$i] ?? null ;
        $bondhok->bhori= $total['vori'] ?? null ;
        $bondhok->ana= $total['ana'] ?? null ;
        $bondhok->roti= $total['roti'] ?? null ;
        $bondhok->point= $total['point'] ?? null ;
        $bondhok->gram= $request->gram[$i] ?? null ;
        $bondhok->details= $request->details ?? null ;
        $bondhok->unit_price= $request->price[$i] ?? null ;
        $bondhok->qty= 1 ;
        $bondhok->interest_rate= $request->interest_rate ?? null ;
        $bondhok->base_amount= $request->base_amount ?? null ;
        $bondhok->due= $request->payable_amount ?? null ;
        $bondhok->paid= 0 ;
        $bondhok->photo= $imageName ;
        $bondhok->payable_amount= $request->payable_amount ?? null ;
        $bondhok->start_time= $request->start_time ?? null ;
        $bondhok->end_time= $request->end_time ?? null ;
        $bondhok->save();
        }
        return redirect()->route('bondhok')->with('message', 'Bondhok Created Successfully');
    }
    public function bondhok_delete(Request $request)
    {
        $transaction = Transaction::find($request->transaction_id);
        if ($transaction) {
            $invoice = Invoice::where('transaction_id',$transaction->id)->latest()->first();

            $transaction->delete();
            $invoice->delete();
            return redirect()->route('bondhok')->with('message', 'Bondhok Transaction Deleted Successfully');
        } else {
            return redirect()->route('bondhok')->with('error', 'Bondhok Not Found');
        }
    }

    public function bondhok_paid(Request $request)
    {
        // dd($request);
        $transaction = Transaction::find($request->transaction_id);
        if ($transaction) {
            $invoice = Invoice::where('transaction_id',$transaction->id)->latest()->first();
            $paid = $request->paid + $transaction->total_payment;
            $due = $transaction->due_payment - $request->paid;

            $transaction->total_payment= $paid;
            $transaction->due_payment= $due;

            $invoice->total_payment= $paid;
            $invoice->due_payment= $due;

            $transaction->save();
            $invoice->save();

            return redirect()->route('bondhok')->with('message', 'Bondhok Payment Updated Successfully');
        } else {
            return redirect()->route('bondhok')->with('error', 'Bondhok Not Found');
        }
    }

}
