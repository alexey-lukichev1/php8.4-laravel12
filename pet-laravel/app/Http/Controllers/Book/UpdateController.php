<?php

namespace App\Http\Controllers\Book;

use App\Http\Controllers\Controller;
use App\Http\Requests\Book\UpdateRequest;
use App\Models\Book;

class UpdateController extends BaseController
{
    public function __invoke(UpdateRequest $request, Book $book)
    {
        $data = $request->validated();

        $this->service->update($book, $data);

        return redirect()->route('book.show', $book->id);
    }
}
