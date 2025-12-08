@extends('layouts.main')
@section('content')
    <div>
        <form action="{{ route('book.update', $book->id) }}" method="post">
            @csrf
            @method('patch')
            <!-- Title -->
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input name="title" type="text" class="form-control" id="title" placeholder="Title" value="{{ $book->title }}">
            </div>

            <!-- Content -->
            <div class="mb-3">
                <label for="content" class="form-label">Content</label>
                <textarea name="content" class="form-control" id="content" placeholder="Content">{{ $book->content }}</textarea>
            </div>

            <!-- Published At -->
            <div class="mb-3">
                <label for="published_at" class="form-label">Published At</label>
                <input name="published_at" type="datetime-local" class="form-control" id="published_at" value="{{ $book->published_at }}">
            </div>

            <!-- Progress -->
            <div class="mb-3">
                <label for="progress" class="form-label">Progress</label>
                <input name="progress" type="text" class="form-control" id="progress" placeholder="Progress" value="{{ $book->progress }}">
            </div>

            <!-- Views -->
            <div class="mb-3">
                <label for="views" class="form-label">Views</label>
                <input name="views" type="number" class="form-control" id="views" placeholder="Views" value="{{ $book->views }}">
            </div>

            <!-- Release Date -->
            <div class="mb-3">
                <label for="release_date" class="form-label">Release Date</label>
                <input name="release_date" type="date" class="form-control" id="release_date" value="{{ $book->release_date }}">
            </div>

            <!-- Category -->
            <div class="mb-3">
                <label for="category" class="form-label">Category</label>
                <select class="form-select" id="category" name="category_id" aria-label="Пример выбора по умолчанию">
                    <option selected>Выберите категорию</option>
                    @foreach ($categories as $category)
                        <option
                            {{ $category->id === $book->category->id ? 'selected' : '' }}
                            value="{{ $category->id }}">{{ $category->title }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Tags -->
            <div class="form-group mb-3">
                <label for="tags">Tags</label>
                <select multiple class="form-control" id="tags" name="tags[]">
                    @foreach ($tags as $tag)
                        <option
                            @foreach ($book->tags as $bookTag)
                                {{ $tag->id === $bookTag->id ? 'selected' : '' }}
                            @endforeach
                            value="{{ $tag->id }}">{{ $tag->title }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <a href="{{ route('book.show', $book->id) }}" style="margin-right: 10px">Назад</a>
                <button type="submit" class="btn btn-primary">Сохранить</button>
            </div>
        </form>
    </div>
@endsection
