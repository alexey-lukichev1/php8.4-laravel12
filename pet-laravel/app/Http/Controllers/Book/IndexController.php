<?php

namespace App\Http\Controllers\Book;

use App\Http\Controllers\Controller;
use App\Http\Filters\BookFilter;
use App\Http\Requests\Book\FilterRequest;
use App\Models\Book;

class IndexController extends BaseController
{
    public function __invoke(FilterRequest $request)
    {
        $data = $request->validated();

        $filter = app()->make(BookFilter::class, ['queryParams' => array_filter($data)]);

        $books = Book::filter($filter)->paginate(5);

        // $query = Book::query();

        // if (isset($data['category_id'])) {
        //     $query->where('category_id', $data['category_id']);
        // }

        // if (isset($data['title'])) {
        //     $query->where('title', 'like', "%{$data['title']}%");
        // }

        // if (isset($data['content'])) {
        //     $query->where('content', 'like', "%{$data['content']}%");
        // }

        // $books = $query->get();
        // dd($books);

        // $books = Book::paginate(5);
        return view('book.index', ['books' => $books]);
    }
}
