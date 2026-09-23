<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    /**
     * Search customers by name, last name, phone, or phone2 via AJAX.
     */
    public function search(Request $request)
    {
        $query = trim($request->get('query', ''));

        if (empty($query)) {
            $customers = User::whereHas('role', function ($q) {
                $q->whereIn('role_slug', ['customer', 'bondhok-customer']);
            })
            ->latest()
            ->take(10)
            ->get();
        } else {
            $customers = User::whereHas('role', function ($q) {
                $q->whereIn('role_slug', ['customer', 'bondhok-customer']);
            })
            ->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('last_name', 'LIKE', "%{$query}%")
                  ->orWhere('phone', 'LIKE', "%{$query}%")
                  ->orWhere('phone2', 'LIKE', "%{$query}%")
                  ->orWhere('email', 'LIKE', "%{$query}%");
            })
            ->take(20)
            ->get();
        }

        $formatted = $customers->map(function ($c) {
            $fullName = trim($c->name . ' ' . ($c->last_name ?? ''));
            return [
                'id'         => $c->id,
                'name'       => $c->name,
                'last_name'  => $c->last_name,
                'full_name'  => $fullName,
                'phone'      => $c->phone,
                'phone2'     => $c->phone2,
                'email'      => $c->email,
                'address'    => $c->address,
                'extra_info' => $c->extra_info,
                'text'       => "{$fullName} ({$c->phone})" . ($c->address ? " - {$c->address}" : ""),
            ];
        });

        return response()->json([
            'status' => 'success',
            'data'   => $formatted
        ]);
    }

    /**
     * Store new customer via AJAX quick-add form.
     */
    public function quick_store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'       => 'required|string|max:255',
            'last_name'  => 'nullable|string|max:255',
            'phone'      => 'required|string|max:30',
            'phone2'     => 'nullable|string|max:30',
            'email'      => 'nullable|email|max:255',
            'address'    => 'nullable|string|max:500',
            'extra_info' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $customerRole = Role::where('role_slug', 'customer')->first();
        $roleId = $customerRole ? $customerRole->id : 3;

        // Generate fallback dummy email if empty to satisfy any unique requirements
        $email = $request->email;
        if (empty($email)) {
            $email = 'customer_' . time() . '_' . rand(100, 999) . '@madobi.com';
        }

        $customer = User::create([
            'role_id'    => $roleId,
            'name'       => $request->name,
            'last_name'  => $request->last_name,
            'phone'      => $request->phone,
            'phone2'     => $request->phone2,
            'email'      => $email,
            'address'    => $request->address,
            'extra_info' => $request->extra_info,
            'is_active'  => 1,
            'password'   => bcrypt('12345678'),
        ]);

        $fullName = trim($customer->name . ' ' . ($customer->last_name ?? ''));

        return response()->json([
            'status'  => 'success',
            'message' => 'কাস্টমার সফলভাবে তৈরি করা হয়েছে।',
            'data'    => [
                'id'         => $customer->id,
                'name'       => $customer->name,
                'last_name'  => $customer->last_name,
                'full_name'  => $fullName,
                'phone'      => $customer->phone,
                'phone2'     => $customer->phone2,
                'email'      => $customer->email,
                'address'    => $customer->address,
                'extra_info' => $customer->extra_info,
                'text'       => "{$fullName} ({$customer->phone})",
            ]
        ]);
    }

    /**
     * Get or create quick Walk-in / Cash customer.
     */
    public function walkin_customer()
    {
        $customerRole = Role::where('role_slug', 'customer')->first();
        $roleId = $customerRole ? $customerRole->id : 3;

        $walkin = User::firstOrCreate(
            ['phone' => '01000000000'],
            [
                'role_id'   => $roleId,
                'name'      => 'ক্যাশ গ্রাহক (Walk-in)',
                'last_name' => '',
                'email'     => 'walkin@madobi.com',
                'address'   => 'শপ কাউন্টার',
                'is_active' => 1,
                'password'  => bcrypt('12345678'),
            ]
        );

        return response()->json([
            'status' => 'success',
            'data'   => [
                'id'         => $walkin->id,
                'name'       => $walkin->name,
                'last_name'  => $walkin->last_name,
                'full_name'  => $walkin->name,
                'phone'      => $walkin->phone,
                'phone2'     => null,
                'email'      => null,
                'address'    => $walkin->address,
                'extra_info' => null,
                'text'       => "{$walkin->name} ({$walkin->phone})",
            ]
        ]);
    }
}
