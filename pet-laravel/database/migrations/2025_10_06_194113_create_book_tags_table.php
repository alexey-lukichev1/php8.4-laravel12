<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('book_tags', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('book_id')->nullable();
            $table->unsignedBigInteger('tag_id')->nullable();

            $table->index('book_id', 'book_tag_book_idx');
            $table->index('tag_id', 'book_tag_tag_idx');

            $table->foreign('book_id', 'book_tag_book_fk')->on('books')->references('id');
            $table->foreign('tag_id', 'book_tag_tag_fk')->on('tags')->references('id');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_tags');
    }
};
