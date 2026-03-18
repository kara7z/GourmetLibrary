<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;

class AdminController extends Controller
{
    public function stats()
    {
        $totalBooks = Book::count();
        $totalCategories = Category::count();
        $totalDamaged = Book::sum('damaged_quantity');

        $mostConsulted = Book::with('category')
            ->orderBy('consultation_count', 'desc')
            ->limit(5)
            ->get(['id', 'title', 'author', 'category_id', 'consultation_count', 'borrow_count']);

        $mostBorrowed = Book::with('category')
            ->orderBy('borrow_count', 'desc')
            ->limit(5)
            ->get(['id', 'title', 'author', 'category_id', 'consultation_count', 'borrow_count']);

        $categoriesWithCount = Category::withCount('books')
            ->orderBy('books_count', 'desc')
            ->get(['id', 'name']);

        $damagedBooks = Book::where('damaged_quantity', '>', 0)
            ->with('category')
            ->orderBy('damaged_quantity', 'desc')
            ->get(['id', 'title', 'author', 'category_id', 'damaged_quantity']);

        return response()->json([
            'overview' => [
                'total_books' => $totalBooks,
                'total_categories' => $totalCategories,
                'total_damaged_copies' => $totalDamaged,
            ],
            'most_consulted_books' => $mostConsulted,
            'most_borrowed_books' => $mostBorrowed,
            'categories_by_book_count' => $categoriesWithCount,
            'damaged_books' => $damagedBooks,
        ]);
    }
}
