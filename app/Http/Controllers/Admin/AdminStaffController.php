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

        return view('admin.staff.index', compact('staffMembers', 'totalStaff'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:50',
            'role' => 'required|in:admin,manager,staff',
            'password' => 'required|string|min:6',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'role' => $validated['role'],
            'password' => Hash::make($validated['password']),
            'avatar' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&auto=format&fit=crop&q=80',
        ]);

        return redirect()->route('admin.staff.index')->with('success', 'নতুন স্টাফ সফলভাবে তৈরি হয়েছে! 👥');
    }

    public function update(Request $request, $id)
    {
        $staff = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'nullable|string|max:50',
            'role' => 'required|in:admin,manager,staff',
            'password' => 'nullable|string|min:6',
        ]);

        $staff->name = $validated['name'];
        $staff->email = $validated['email'];
        $staff->phone = $validated['phone'];
        $staff->role = $validated['role'];

        if (!empty($validated['password'])) {
            $staff->password = Hash::make($validated['password']);
        }

        $staff->save();

        return redirect()->route('admin.staff.index')->with('success', 'স্টাফ তথ্য ও রোল আপডেট হয়েছে!');
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
