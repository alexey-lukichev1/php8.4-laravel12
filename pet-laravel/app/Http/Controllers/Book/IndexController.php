<?php

namespace App\Http\Controllers\Book;

use App\Http\Controllers\Controller;
use App\Models\Book;

class IndexController extends Controller
{
    public function __invoke()
    {
        $books = Book::all();
        // foreach ($books as $book) {
        //     dump($book->title);
        // }
        // dd('end');
        return view('book.index', ['books' => $books]);
        // return view('books', compact('books'));
        // $book = Book::find(1);
        // $tag = Tag::find(1);
        // dd($tag->books);
    }
}
