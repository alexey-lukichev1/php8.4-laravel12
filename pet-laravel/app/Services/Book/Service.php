<?php

namespace App\Services\Book;

use App\Models\Book;
use Exception;
use Illuminate\Support\Facades\DB;

class Service
{
    public function store($data)
    {

        try {
            DB::beginTransaction();

            $tags = $data['tags'];
            unset($data['tags']);

            // dd($tags, $data);
            $book = Book::create($data);

            $book->tags()->attach($tags);

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }

    public function update(Book $book, $data)
    {
        $tags = $data['tags'];
        unset($data['tags']);

        $book->update($data);
        $book->tags()->sync($tags);
    }
}
