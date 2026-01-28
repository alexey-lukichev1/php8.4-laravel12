<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // Создать 8 категорий
        Category::factory()->count(8)->create();

        // Создать 13 тегов
        Tag::factory()->count(13)->create();

        // Создать 20 книг
        Book::factory()->count(20)->create();

        // Создать 5 опубликованных книг
        Book::factory()->count(5)->published()->create();

        // Создать 3 популярные книги
        Book::factory()->count(3)->popular()->create();

        // Создать книгу с конкретными данными
        Book::factory()->create([
            'title' => 'Laravel 12: Полное руководство',
            'progress' => '100',
            'views' => 15000,
        ]);

        // Заполнить todos
        $this->call([
            TodoSeeder::class,
        ]);
    }
}
