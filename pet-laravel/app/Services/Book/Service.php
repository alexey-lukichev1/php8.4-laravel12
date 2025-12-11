<?php

namespace App\Services\Book;

use App\Models\Book;

class Service
{
    public function store($data)
    {
        $tags = $data['tags'];
        unset($data['tags']);

        // dd($tags, $data);
        $book = Book::create($data);

        $book->tags()->attach($tags);
    }

    public function update(Book $book, $data)
    {
        $tags = $data['tags'];
        unset($data['tags']);

        $book->update($data);
        $book->tags()->sync($tags);
    }
}
