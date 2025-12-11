<?php

namespace App\Http\Controllers\Book;

use App\Http\Controllers\Controller;
use App\Http\Requests\Book\UpdateRequest;
use App\Models\Book;

class UpdateController extends Controller
{
    public function __invoke(UpdateRequest $request, Book $book)
    {
        $data = $request->validated();
        $tags = $data['tags'];
        unset($data['tags']);

        $book->update($data);
        $book->tags()->sync($tags);

        return redirect()->route('book.show', $book->id);
    }
}
