<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'title',
        'author',
        'description',
        'publication_date',
        'category_id',
        'is_new_arrival',
        'consultation_count',
        'borrow_count',
        'damaged_quantity'
    ];

    protected $casts = [
        'publication_date' => 'date',
        'is_new_arrival' => 'boolean',
    ];
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
