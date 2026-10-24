<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;

class BookController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $books = Book::latest()->paginate(10);
        return view('admin.books.index', compact('books'));
    }

    public function create()
    {
        return view('admin.books.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:100',
            'publisher' => 'nullable|string|max:100',
            'isbn' => 'required|string|unique:books,isbn',
            'quantity' => 'required|integer|min:0',
            'available_quantity' => 'required|integer|min:0',
            'description' => 'nullable|string',
        ]);

        Book::create($validated);

        return redirect()->route('admin.books.index')->with('success', 'Book created successfully.');
    }

    public function edit($id)
    {
        $book = Book::findOrFail($id);
        return view('admin.books.edit', compact('book'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:100',
            'publisher' => 'nullable|string|max:100',
            'isbn' => 'required|string|unique:books,isbn,' . $id,
            'quantity' => 'required|integer|min:0',
            'available_quantity' => 'required|integer|min:0',
            'description' => 'nullable|string',
        ]);

        $book = Book::findOrFail($id);
        $book->update($validated);

        return redirect()->route('admin.books.index')->with('success', 'Book updated successfully.');
    }

    public function destroy($id)
    {
        $book = Book::findOrFail($id);
        $book->delete();

        return redirect()->route('admin.books.index')->with('success', 'Book deleted successfully.');
    }
}
