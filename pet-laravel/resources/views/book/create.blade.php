@extends('layouts.main')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <form action="{{ route('book.store') }}" method="post">
                    @csrf
                    <!-- Title -->
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input
                            value="{{ old('title') }}"
                            name="title" type="text" class="form-control" id="title" placeholder="Title">
                        @error('title')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Content -->
                    <div class="mb-3">
                        <label for="content" class="form-label">Content</label>
                        <textarea name="content" class="form-control" id="content" placeholder="Content">{{ old('content') }}</textarea>
                        @error('content')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Published At -->
                    <div class="mb-3">
                        <label for="published_at" class="form-label">Published At</label>
                        <input
                            value="{{ old('published_at') }}"
                            name="published_at" type="datetime-local" class="form-control" id="published_at">
                        @error('published_at')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Progress -->
                    <div class="mb-3">
                        <label for="progress" class="form-label">Progress</label>
                        <input
                            value="{{ old('progress') }}"
                            name="progress" type="text" class="form-control" id="progress" placeholder="Progress">
                        @error('progress')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Views -->
                    <div class="mb-3">
                        <label for="views" class="form-label">Views</label>
                        <input
                            value="{{ old('views') }}"
                            name="views" type="number" class="form-control" id="views" placeholder="Views">
                        @error('views')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Release Date -->
                    <div class="mb-3">
                        <label for="release_date" class="form-label">Release Date</label>
                        <input
                            value="{{ old('release_date') }}"
                            name="release_date" type="date" class="form-control" id="release_date">
                        @error('release_date')
                            <p class="text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Category -->
                    <div class="mb-3">
                        <label for="category" class="form-label">Category</label>
                        <select class="form-select" id="category" name="category_id" aria-label="Пример выбора по умолчанию">
                            <option selected>Выберите категорию</option>
                            @foreach ($categories as $category)
                                <option
                                    {{ old('category_id') == $category->id ? 'selected' : '' }}
                                    value="{{ $category->id }}">{{ $category->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tags -->
                    <div class="form-group mb-3">
                        <label for="tags">Tags</label>
                        <select multiple class="form-control" id="tags" name="tags[]">
                            @foreach ($tags as $tag)
                                <option value="{{ $tag->id }}">{{ $tag->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Отправить</button>
                </form>
            </div>
        </div>
    </div>
@endsection
