<?php

namespace App\Http\Controllers\Book;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use App\Models\Tag;

class EditController extends BaseController
{
    public function __invoke(Book $book)
    {
        $categories = Category::all();
        $tags = Tag::all();

        return view('book.edit', ['categories' => $categories, 'tags' => $tags, 'book' => $book]);
    }
}
