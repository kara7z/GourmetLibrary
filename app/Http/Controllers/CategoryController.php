<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::query();

        if ($request->has('q')) {
            $q = $request->q;

            $query->where(function ($query) use ($q) {
                $query->where('name', 'like', "%$q%")
                    ->orWhere('description', 'like', "%$q%");
            });
        }

        if ($request->boolean('with_books_count')) {
            $query->withCount('books');
        }

        if ($request->boolean('with_books')) {
            $query->with('books');
        }

        if ($request->sort === 'name') {
            $query->orderBy('name');
        }

        $categories = $query->paginate(10);

        return response()->json($categories);
    }

    public function show(Category $category, Request $request)
    {
        if ($request->boolean('with_books')) {
            $category->load('books');
        }

        return response()->json($category);
    }

    public function store(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
        ]);

        $category = Category::create($fields);

        return response()->json([
            'message' => 'Category created successfully',
            'category' => $category
        ], 201);
    }

    public function update(Request $request, Category $category)
    {
        $fields = $request->validate([
            'name' => 'sometimes|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
        ]);

        $category->update($fields);

        return response()->json([
            'message' => 'Category updated successfully',
            'category' => $category
        ]);
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return response()->json([
            'message' => 'Category deleted successfully'
        ]);
    }
}
