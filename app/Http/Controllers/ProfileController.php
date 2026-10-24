<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = auth()->user();
        return view('admin.profile.index', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'dob' => 'nullable|date',
            'gender' => 'nullable|string|in:male,female,other',
            'current_password' => 'nullable|string',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $data = $request->only(['name', 'phone', 'address', 'dob', 'gender']);

        if ($request->filled('password')) {
            if (!Hash::check($request->current_password ?? '', $user->password)) {
                return redirect()->back()->withErrors(['current_password' => __('marks.pf_wrong_current')])->withInput();
            }
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);

        return redirect()->back()->with('success', __('marks.pf_updated'));
    }
}
