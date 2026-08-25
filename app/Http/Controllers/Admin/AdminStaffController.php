<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminStaffController extends Controller
{
    public function index()
    {
        $staffMembers = User::whereIn('role', ['admin', 'manager', 'staff'])->latest()->paginate(10);
        $totalStaff = User::whereIn('role', ['admin', 'manager', 'staff'])->count();
        
        $availablePermissions = [
            'orders' => 'অর্ডার ও সেলস প্রসেসিং',
            'products' => 'প্রোডাক্ট ও ইনভেন্টরি স্টক',
            'ai_automation' => 'AI অটোমেশন ও লাইভ চ্যাট',
            'marketing' => 'মার্কেটিং, কুপন ও সিআরএম',
            'settings' => 'সিস্টেম সেটিংস ও পেমেন্ট',
            'analytics' => 'লাভ-ক্ষতি ও ফিন্যান্সিয়াল রিপোর্ট',
        ];

        return view('admin.staff.index', compact('staffMembers', 'totalStaff', 'availablePermissions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:50',
            'role' => 'required|in:admin,manager,staff',
            'password' => 'required|string|min:6',
            'permissions' => 'nullable|array',
        ]);

        $perms = $validated['role'] === 'admin' ? ['*'] : ($request->input('permissions') ?: ['orders']);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'role' => $validated['role'],
            'status' => 'active',
            'permissions' => $perms,
            'password' => Hash::make($validated['password']),
            'avatar' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&auto=format&fit=crop&q=80',
        ]);

        return redirect()->route('admin.staff.index')->with('success', 'নতুন স্টাফ ও পারমিশন সফলভাবে তৈরি হয়েছে! 👥');
    }

    public function update(Request $request, $id)
    {
        $staff = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'nullable|string|max:50',
            'role' => 'required|in:admin,manager,staff',
            'status' => 'nullable|in:active,blocked',
            'password' => 'nullable|string|min:6',
            'permissions' => 'nullable|array',
        ]);

        $staff->name = $validated['name'];
        $staff->email = $validated['email'];
        $staff->phone = $validated['phone'];
        $staff->role = $validated['role'];
        $staff->status = $validated['status'] ?? 'active';

        if ($validated['role'] === 'admin') {
            $staff->permissions = ['*'];
        } else {
            $staff->permissions = $request->input('permissions') ?: [];
        }

        if (!empty($validated['password'])) {
            $staff->password = Hash::make($validated['password']);
        }

        $staff->save();

        return redirect()->route('admin.staff.index')->with('success', 'স্টাফ তথ্য ও অ্যাক্সেস পারমিশন আপডেট হয়েছে!');
    }

    public function destroy($id)
    {
        $staff = User::findOrFail($id);
        if ($staff->id === auth()->id()) {
            return redirect()->back()->with('error', 'আপনি নিজের একাউন্ট ডিলিট করতে পারবেন না!');
        }

        $staff->delete();
        return redirect()->route('admin.staff.index')->with('success', 'স্টাফ একাউন্ট রিমুভ করা হয়েছে!');
    }
}
