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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title')->unique();
            $table->text('content')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->string('progress')->default('0');
            $table->integer('views')->default(0);
            $table->date('release_date');
            $table->timestamps();

            $table->softDeletes();

            $table->unsignedBigInteger('category_id')->nullable(); //создаем колонку category_id в таблице books для связи с таблицей categories

            $table->index('category_id', 'book_category_idx'); //Создает индекс на колонке category_id (специальная структура для ускорения поиска)

            $table->foreign('category_id', 'book_category_fk')->on('categories')->references('id'); //Создает внешний ключ (Связывает books.category_id с categories.id)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropForeign('book_category_fk');
        });

        Schema::dropIfExists('books');
    }
};
