<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sell;
use App\Models\ProductCategory;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Bondhok;
use App\Models\BProduct;

use PDF;
use Carbon\Carbon;

require_once app_path('helpers.php');

class ReportController extends Controller
{
    public function sells_report_index(Request $request){
        $filter = $request->input('filter');
        $query = Sell::query();
        if ($request->has('filter')) {
            switch ($filter) {
                case 'daily':
                    $query->whereDate('order_date', Carbon::today());
                    break;
                case 'weekly':
                    $query->whereBetween('order_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                    break;
                case 'monthly':
                    $query->whereMonth('order_date', Carbon::now()->month);
                    break;
                case '3-months':
                    $query->whereBetween('order_date', [Carbon::now()->subMonths(3), Carbon::now()]);
                    break;
                case '6-months':
                    $query->whereBetween('order_date', [Carbon::now()->subMonths(6), Carbon::now()]);
                    break;
                case 'yearly':
                    $query->whereYear('order_date', Carbon::now()->year);
                    break;
                default:
                    // Handle default case if needed
                    break;
            }
        }
        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('order_date', [$request->input('from_date'), $request->input('to_date')]);
        }
    
        $sells = $query->get();
        $products = Product::all();
        $categories = ProductCategory::all();
    
        // Calculate totals only if generating PDF
        $totalBhori = $totalAna = $totalRoti = $totalPrice = $totalAdvPayment = $totalDuePayment = 0;
        $gold=[
            'vori' => $sells->sum('bhori'),
            'ana' => $sells->sum('ana'),
            'roti' => $sells->sum('roti'),
            'point' => $sells->sum('point'),
        ];
      
            $total=calculateTotalGold($gold);
            $totalBhori = $total['vori'];
            $totalAna = $total['ana'];
            $totalRoti = $total['roti'];
            $totalPoint = $total['roti'];
            $totalPrice = $sells->sum('total_price');
            $totalDuePayment = $sells->sum('due_payment');
            $totalAdvPayment = $sells->sum('adv_payment');
  
    
        if ($request->has('pdf')) {
            $pdf = PDF::loadView('admin.report.sells.report_pdf', compact('sells', 'products', 'categories', 'totalBhori', 'totalAna', 'totalRoti', 'totalPrice', 'totalDuePayment', 'totalAdvPayment','totalPoint'));
            return $pdf->download(Carbon::now()->format('d-M-Y').'-sells_report.pdf');
        }
    
        return view('admin.report.sells.index',compact('sells','categories','products', 'totalBhori', 'totalAna', 'totalRoti', 'totalPrice', 'totalDuePayment', 'totalAdvPayment','totalPoint'));
    }
    public function purchases_report_index(Request $request){
        $filter = $request->input('filter');
        $query = Purchase::query();
        if ($request->has('filter')) {
            switch ($filter) {
                case 'daily':
                    $query->whereDate('order_date', Carbon::today());
                    break;
                case 'weekly':
                    $query->whereBetween('order_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                    break;
                case 'monthly':
                    $query->whereMonth('order_date', Carbon::now()->month);
                    break;
                case '3-months':
                    $query->whereBetween('order_date', [Carbon::now()->subMonths(3), Carbon::now()]);
                    break;
                case '6-months':
                    $query->whereBetween('order_date', [Carbon::now()->subMonths(6), Carbon::now()]);
                    break;
                case 'yearly':
                    $query->whereYear('order_date', Carbon::now()->year);
                    break;
                default:
                    // Handle default case if needed
                    break;
            }
        }
        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('order_date', [$request->input('from_date'), $request->input('to_date')]);
        }
    
        $purchases = $query->get();
        $products = Product::all();
        $categories = ProductCategory::all();

    
        // Calculate totals only if generating PDF
        $totalBhori = $totalAna = $totalRoti = $totalPrice = $totalAdvPayment = $totalDuePayment = 0;
        $gold=[
            'vori' => $purchases->sum('bhori'),
            'ana' => $purchases->sum('ana'),
            'roti' => $purchases->sum('roti'),
            'point' => $purchases->sum('point'),
        ];
      
            $total=calculateTotalGold($gold);
            $totalBhori = $total['vori'];
            $totalAna = $total['ana'];
            $totalRoti = $total['roti'];
            $totalPoint = $total['point'];
            $totalPrice = $purchases->sum('total_price');
            $totalDuePayment = $purchases->sum('due_payment');
            $totalAdvPayment = $purchases->sum('adv_payment');
      
    
        if ($request->has('pdf')) {
            $pdf = PDF::loadView('admin.report.purchases.report_pdf', compact('purchases', 'products', 'categories', 'totalBhori', 'totalAna', 'totalRoti', 'totalPrice', 'totalDuePayment', 'totalAdvPayment','totalPoint'));
            return $pdf->download(Carbon::now()->format('d-M-Y').'-purchases_report.pdf');
        }
    
        return view('admin.report.purchases.index',compact('purchases','categories','products', 'totalBhori', 'totalAna', 'totalRoti', 'totalPrice', 'totalDuePayment', 'totalAdvPayment','totalPoint'));
    }



    public function bondhok_report_index(Request $request){
        $filter = $request->input('filter');
        $query = Bondhok::query()->with('user');
        if ($request->has('filter')) {
            switch ($filter) {
                case 'daily':
                    $query->whereDate('start_time', Carbon::today());
                    break;
                case 'weekly':
                    $query->whereBetween('start_time', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                    break;
                case 'monthly':
                    $query->whereMonth('start_time', Carbon::now()->month);
                    break;
                case '3-months':
                    $query->whereBetween('start_time', [Carbon::now()->subMonths(3), Carbon::now()]);
                    break;
                case '6-months':
                    $query->whereBetween('start_time', [Carbon::now()->subMonths(6), Carbon::now()]);
                    break;
                case 'yearly':
                    $query->whereYear('start_time', Carbon::now()->year);
                    break;
                default:
                    // Handle default case if needed
                    break;
            }
        }
        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('start_time', [$request->input('from_date'), $request->input('to_date')]);
        }
    
        $bondhoks = $query->get();
       
        $products = BProduct::all();

    
        // Calculate totals only if generating PDF
        $totalBhori = $totalAna = $totalRoti = $totalPrice = $totalAdvPayment = $totalDuePayment = 0;
        $gold=[
            'vori' => $bondhoks->sum('bhori'),
            'ana' => $bondhoks->sum('ana'),
            'roti' => $bondhoks->sum('roti'),
            'point' => $bondhoks->sum('point'),
        ];
            $total=calculateTotalGold($gold);
            $totalBhori = $total['vori'];
            $totalAna = $total['ana'];
            $totalRoti = $total['roti'];
            $totalPoint = $total['point'];
            $totalPrice = $bondhoks->sum('payable_amount');
            $totalDuePayment = $bondhoks->sum('due');
            $totalAdvPayment = $bondhoks->sum('paid');
      
    
        if ($request->has('pdf')) {
            $pdf = PDF::loadView('admin.report.bondhok.report_pdf', compact('bondhoks', 'products', 'totalBhori', 'totalAna', 'totalRoti', 'totalPrice', 'totalDuePayment', 'totalAdvPayment','totalPoint'));
            return $pdf->download(Carbon::now()->format('d-M-Y').'-bondhok_report.pdf');
        }
    
        return view('admin.report.bondhok.index',compact('bondhoks','products', 'totalBhori', 'totalAna', 'totalRoti', 'totalPrice', 'totalDuePayment', 'totalAdvPayment','totalPoint'));  
    }
}
