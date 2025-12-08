@extends('layouts.main')
@section('content')
    <div>
        <div>{{ $book->id }}. {{ $book->title }}</div>
        <div>{{ $book->content }}</div>
    </div>
    <div>
        <a href="{{ route('book.edit', $book->id) }}">Редактировать</a>
    </div>
    <div>
        <form action="{{ route('book.destroy', $book->id) }}" method="post">
            @csrf
            @method('delete')
            <input type="submit" value="Удалить">
        </form>
    </div>
    <div>
        <a href="{{ route('book.index') }}">Назад</a>
    </div>
@endsection
