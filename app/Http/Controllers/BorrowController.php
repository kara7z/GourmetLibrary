<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrow;
use Illuminate\Http\Request;

class BorrowController extends Controller
{
    public function index(Request $request)
    {
        $borrows = Borrow::with('book.category')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate(10);

        return response()->json($borrows);
    }

    public function borrow(Request $request, Book $book)
    {
        $userId = $request->user()->id;

        $alreadyBorrowed = Borrow::where('user_id', $userId)
            ->where('book_id', $book->id)
            ->where('status', 'borrowed')
            ->exists();

        if ($alreadyBorrowed) {
            return response()->json(['message' => 'You already have this book borrowed.'], 409);
        }

        $borrow = Borrow::create([
            'user_id' => $userId,
            'book_id' => $book->id,
            'status'  => 'borrowed',
        ]);

        $book->increment('borrow_count');

        return response()->json([
            'message' => 'Book borrowed successfully.',
            'borrow'  => $borrow->load('book.category'),
        ], 201);
    }

    public function return(Request $request, Borrow $borrow)
    {
        if ($borrow->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Forbidden.'], 403);
        }

        if ($borrow->status === 'returned') {
            return response()->json(['message' => 'This book has already been returned.'], 409);
        }

        $borrow->update([
            'status'      => 'returned',
            'returned_at' => now(),
        ]);

        return response()->json([
            'message' => 'Book returned successfully.',
            'borrow'  => $borrow->load('book.category'),
        ]);
    }
}
