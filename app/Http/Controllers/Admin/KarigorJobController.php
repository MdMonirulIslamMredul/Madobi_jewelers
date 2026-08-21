<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KarigorJob;
use App\Models\Purchase;
use App\Models\PurchaseLocationHistory;
use Illuminate\Http\Request;

class KarigorJobController extends Controller
{
    /**
     * Assign a job to a Karigor.
     */
    public function assignJob(Request $request)
    {
        $request->validate([
            'purchase_id' => 'required|exists:purchases,id',
            'karigor_id'  => 'required|exists:users,id',
            'task_type'   => 'required|in:Repair,Raw Gold(Paka kora)',
            'extra_raw_gold' => 'nullable|numeric|min:0',
        ]);

        $purchase = Purchase::findOrFail($request->purchase_id);
        $fromLocation = $purchase->location;

        // Check if there is already an active job in progress for this purchase
        $existingJob = KarigorJob::where('purchase_id', $purchase->id)
            ->where('status', 'in_progress')
            ->first();

        if ($existingJob) {
            $existingJob->update([
                'status' => 'cancelled',
                'notes'  => 'Cancelled due to new job assignment.',
            ]);
        }

        // Create new Karigor Job
        $job = KarigorJob::create([
            'purchase_id'             => $purchase->id,
            'karigor_id'              => $request->karigor_id,
            'assigned_by'             => auth()->id(),
            'task_type'               => $request->task_type,
            'status'                  => 'in_progress',
            'given_gross_weight'      => $purchase->gram ?? 0,
            'given_purity_weight'     => $purchase->raw_gold ?? 0,
            'assigned_extra_raw_gold' => $request->extra_raw_gold ?? 0,
            'assigned_at'             => now(),
            'notes'                   => $request->notes ?? 'Assigned to Karigor',
        ]);

        // Update purchase location & set active karigor_job_id
        $purchase->location = 'in_progress';
        $purchase->karigor_job_id = $job->id;
        $purchase->save();

        // Record Location History with karigor_job_id
        PurchaseLocationHistory::create([
            'purchase_id'         => $purchase->id,
            'karigor_job_id'      => $job->id,
            'from_location'       => $fromLocation,
            'to_location'         => 'in_progress',
            'transferred_by'      => auth()->id(),
            'assigned_karigor_id' => $request->karigor_id,
            'task_type'           => $request->task_type,
            'extra_raw_gold'      => $request->extra_raw_gold,
            'note'                => 'Sent to Karigor Job (In Progress)',
        ]);

        return redirect()->back()->with('message', 'কারিগরকে কাজ সফলভাবে অর্পণ করা হয়েছে 🔨');
    }

    /**
     * Complete and receive job from Karigor.
     */
    public function completeJob(Request $request)
    {
        $request->validate([
            'job_id'                => 'required|exists:karigor_jobs,id',
            'returned_raw_gold'     => 'required|numeric|min:0',
            'returned_gross_weight' => 'nullable|numeric|min:0',
            'used_extra_raw_gold'   => 'nullable|numeric|min:0',
            'notes'                 => 'nullable|string',
        ]);

        $job = KarigorJob::findOrFail($request->job_id);
        $purchase = $job->purchase;

        $givenPurity = floatval($job->given_purity_weight ?? 0);
        $returnedRawGold = floatval($request->returned_raw_gold);
        $usedExtraGold = floatval($request->used_extra_raw_gold ?? 0);

        // Calculations
        $totalAvailableGold = $givenPurity + $usedExtraGold;
        $wastageGold = $totalAvailableGold - $returnedRawGold;
        
        $conversionPct = 100.00;
        if ($givenPurity > 0) {
            $conversionPct = ($returnedRawGold / $givenPurity) * 100;
        }

        // Update Karigor Job Record
        $job->update([
            'returned_gross_weight' => $request->returned_gross_weight ?? $job->given_gross_weight,
            'returned_raw_gold'     => $returnedRawGold,
            'used_extra_raw_gold'   => $usedExtraGold,
            'wastage_gold'          => number_format($wastageGold, 3, '.', ''),
            'conversion_percentage' => number_format($conversionPct, 2, '.', ''),
            'status'                => 'completed',
            'completed_at'          => now(),
            'notes'                 => $request->notes,
        ]);

        // Update purchase location, raw_gold and gram
        $fromLoc = $purchase->location ?: 'in_progress';
        $purchase->location = 'is_karigor';
        if ($returnedRawGold > 0) {
            $purchase->raw_gold = $returnedRawGold;
        }
        if ($request->filled('returned_gross_weight')) {
            $purchase->gram = $request->returned_gross_weight;
        }
        $purchase->save();

        // Record Location History
        PurchaseLocationHistory::create([
            'purchase_id'         => $purchase->id,
            'karigor_job_id'      => $job->id,
            'from_location'       => $fromLoc,
            'to_location'         => 'is_karigor',
            'transferred_by'      => auth()->id(),
            'assigned_karigor_id' => $job->karigor_id,
            'task_type'           => $job->task_type,
            'extra_raw_gold'      => $usedExtraGold,
            'note'                => 'Karigor Job Completed (' . $job->task_type . ' - ' . number_format($conversionPct, 2) . '% conversion)',
        ]);

        return redirect()->back()->with('message', 'কারিগরের কাজ সফলভাবে সম্পন্ন ও স্টক আপডেট হয়েছে ✅ (Conversion: ' . number_format($conversionPct, 2) . '%)');
    }

    /**
     * Display a listing of assigned Karigor jobs with progress status.
     */
    public function karigor_job_index(Request $request)
    {
        $status = $request->get('status', 'all');
        $taskType = $request->get('task_type', 'all');
        $karigorId = $request->get('karigor_id', 'all');

        $query = KarigorJob::with(['purchase.productCategory', 'purchase.product', 'karigor', 'assignedBy'])
            ->latest();

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if ($taskType !== 'all') {
            $query->where('task_type', $taskType);
        }

        if ($karigorId !== 'all') {
            $query->where('karigor_id', $karigorId);
        }

        $jobs = $query->get();

        // Statistical Summaries
        $totalJobs = KarigorJob::count();
        $inProgressJobs = KarigorJob::where('status', 'in_progress')->count();
        $completedJobs = KarigorJob::where('status', 'completed')->count();
        $totalGivenRawGold = KarigorJob::sum('given_purity_weight');
        $totalReturnedRawGold = KarigorJob::where('status', 'completed')->sum('returned_raw_gold');

        $karigors = \App\Models\User::whereHas('role', function($q) {
            $q->where('role_name', 'karigor');
        })->orWhere('role_id', 5)->get();

        return view('admin.karigor.karigorJobIndex', compact(
            'jobs',
            'status',
            'taskType',
            'karigorId',
            'totalJobs',
            'inProgressJobs',
            'completedJobs',
            'totalGivenRawGold',
            'totalReturnedRawGold',
            'karigors'
        ));
    }
}
