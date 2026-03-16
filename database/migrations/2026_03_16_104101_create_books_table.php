<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('author')->nullable();
            $table->text('description')->nullable();
            $table->date('publication_date')->nullable();
            $table->boolean('is_new_arrival')->default(false);
            $table->integer('consultation_count')->default(0);
            $table->integer('borrow_count')->default(0);
            $table->integer('damaged_quantity')->default(0);
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
