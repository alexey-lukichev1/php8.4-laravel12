<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookTag;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index() {
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

    public function filter($typeFilter)
    {
        switch ($typeFilter) {
            case 'best':
                $books = Book::where('views', '>', 9)->get();
                break;
            case 'popular':
                $books = Book::where('progress', 5)->get();
                break;
            case 'new':
                $books = Book::where('release_date', '>=', now()->subDays(30))->get();
                break;
            default;
                $books = Book::all();
        }

        foreach ($books as $book) {
            dump($book->title);
        }
    }

    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();

        return view('book.create', ['categories' => $categories, 'tags' => $tags]);
    }

    public function store()
    {
        $data = request()->validate([
            'title' => 'required|string',
            'content' => 'required|string',
            'published_at' => 'required|date',
            'progress' => 'required|string',
            'views' => 'required|integer',
            'release_date' => 'required|date',
            'category_id' => '',
            'tags' => '',
        ]);
        $tags = $data['tags'];
        unset($data['tags']);
        // dd($tags, $data);
        $book = Book::create($data);

        $book->tags()->attach($tags);

        return redirect()->route('book.index');
    }

    public function show(Book $book)
    {
        return view('book.show', compact('book'));
    }

    public function edit(Book $book)
    {
        $categories = Category::all();
        $tags = Tag::all();

        return view('book.edit', ['categories' => $categories, 'tags' => $tags, 'book' => $book]);
    }

    public function update(Book $book)
    {
        $data = request()->validate([
            'title' => 'string',
            'content' => 'string',
            'published_at' => 'date',
            'progress' => 'string',
            'views' => 'integer',
            'release_date' => 'date',
            'category_id' => '',
            'tags' => '',
        ]);
        $tags = $data['tags'];
        unset($data['tags']);

        $book->update($data);
        $book->tags()->sync($tags);

        return redirect()->route('book.show', $book->id);
    }

    public function delete()
    {
        $book = Book::withTrashed()->find(2);
        // $book->delete();
        $book->restore();
        dd('done_delete');
    }

    public function destroy(Book $book)
    {
        $book->delete();
        return redirect()->route('book.index');
    }

    public function firstOrCreate()
    {
        $anotherBook = [
            'title' => 'some test title',
            'content' => 'some test content',
            'published_at' => '2025-01-20 14:20:00',
            'progress' => '6',
            'views' => 7,
            'release_date' => '2025-06-11',
        ];

        $book = Book::firstOrCreate([
            'title' => 'some 2 test title',
        ], [
            'title' => 'some 2 test title',
            'content' => 'some 2 some 2 some 2',
            'published_at' => '2025-01-20 14:20:00',
            'progress' => '6',
            'views' => 7,
            'release_date' => '2025-06-11',
        ]);

        dump($book->content);
        dd('finished');
    }

    public function updateOrCreate()
    {
        $anotherBook = [
            'title' => 'updateOrCreate test title',
            'content' => 'updateOrCreate test content',
            'published_at' => '2025-01-20 14:20:00',
            'progress' => '5',
            'views' => 6,
            'release_date' => '2025-06-11',
        ];

        $book = Book::updateOrCreate([
            'title' => 'test test test title',
        ], [
            'title' => 'test test test title',
            'content' => 'another test test content',
            'published_at' => '2025-01-20 14:20:00',
            'progress' => '5',
            'views' => 6,
            'release_date' => '2025-06-11',
        ]);

        dump($book->content);
        dd('2222');
    }
}
