<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::with('category');

        if ($request->has('q')) {
            $q = $request->q;

            $query->where(function ($query) use ($q) {
                $query->where('title', 'like', "%$q%")
                    ->orWhere('author', 'like', "%$q%")
                    ->orWhereHas('category', function ($q2) use ($q) {
                        $q2->where('name', 'like', "%$q%");
                    });
            });
        }

        
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        
        if ($request->sort === 'popular') {
            $query->orderBy('borrow_count', 'desc');
        } elseif ($request->sort === 'new') {
            $query->orderBy('publication_date', 'desc');
        }

        $books = $query->paginate(10);

        return response()->json($books);
    }


    public function show(Book $book)
    {
        $book->load('category');


        $book->increment('consultation_count');

        return response()->json($book);
    }


    public function store(Request $request)
    {
        $fields = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'publication_date' => 'nullable|date',
            'category_id' => 'required|exists:categories,id',
            'is_new_arrival' => 'boolean',
        ]);

        $book = Book::create($fields);

        return response()->json([
            'message' => 'Book created successfully',
            'book' => $book
        ], 201);
    }


    public function update(Request $request, Book $book)
    {
        $fields = $request->validate([
            'title' => 'sometimes|string|max:255',
            'author' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'publication_date' => 'nullable|date',
            'category_id' => 'exists:categories,id',
            'is_new_arrival' => 'boolean',
            'damaged_quantity' => 'integer|min:0'
        ]);

        $book->update($fields);

        return response()->json([
            'message' => 'Book updated successfully',
            'book' => $book
        ]);
    }


    public function destroy(Book $book)
    {
        $book->delete();

        return response()->json([
            'message' => 'Book deleted successfully'
        ]);
    }
}
