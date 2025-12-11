<?php

namespace App\Http\Controllers\Book;

use App\Http\Controllers\Controller;
use App\Http\Requests\Book\StoreRequest;
use App\Models\Book;

class StoreController extends Controller
{
    public function __invoke(StoreRequest $request)
    {
        $data = $request->validated();
        $tags = $data['tags'];
        unset($data['tags']);
        // dd($tags, $data);
        $book = Book::create($data);

        $book->tags()->attach($tags);

        return redirect()->route('book.index');
    }
}
