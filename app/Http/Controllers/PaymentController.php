<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\User;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = Payment::with('student');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        $payments = $query->latest()->paginate(10);
        $students = User::where('role_id', 6)->get();

        return view('admin.payments.index', compact('payments', 'students'));
    }

    public function create()
    {
        $students = User::where('role_id', 6)->get();
        return view('admin.payments.create', compact('students'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0',
            'fee_type' => 'required|string|max:255',
        ]);

        Payment::create($request->all());

        return redirect()->route('admin.payments.index')->with('success', 'Payment created successfully.');
    }

    public function edit($id)
    {
        $payment = Payment::findOrFail($id);
        $students = User::where('role_id', 6)->get();
        return view('admin.payments.edit', compact('payment', 'students'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'student_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0',
            'fee_type' => 'required|string|max:255',
        ]);

        $payment = Payment::findOrFail($id);
        $payment->update($request->all());

        return redirect()->route('admin.payments.index')->with('success', 'Payment updated successfully.');
    }

    public function destroy($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->delete();

        return redirect()->route('admin.payments.index')->with('success', 'Payment deleted successfully.');
    }

    public function receipt($id)
    {
        $payment = Payment::with('student')->findOrFail($id);
        return view('admin.payments.receipt', compact('payment'));
    }
}
