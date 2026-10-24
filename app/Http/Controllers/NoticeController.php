<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notice;

class NoticeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $notices = Notice::with('postedBy')->latest()->paginate(10);
        return view('admin.notices.index', compact('notices'));
    }

    public function create()
    {
        return view('admin.notices.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'notice_date' => 'required|date',
        ]);

        Notice::create([
            'title' => $request->title,
            'content' => $request->content,
            'notice_date' => $request->notice_date,
            'posted_by' => auth()->id(),
        ]);

        return redirect()->route('admin.notices.index')->with('success', 'Notice created successfully.');
    }

    public function edit($id)
    {
        $notice = Notice::findOrFail($id);
        return view('admin.notices.edit', compact('notice'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'notice_date' => 'required|date',
        ]);

        $notice = Notice::findOrFail($id);
        $notice->update([
            'title' => $request->title,
            'content' => $request->content,
            'notice_date' => $request->notice_date,
        ]);

        return redirect()->route('admin.notices.index')->with('success', 'Notice updated successfully.');
    }

    public function destroy($id)
    {
        $notice = Notice::findOrFail($id);
        $notice->delete();

        return redirect()->route('admin.notices.index')->with('success', 'Notice deleted successfully.');
    }
}
