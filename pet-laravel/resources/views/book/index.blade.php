@extends('layouts.main')
@section('content')
    <div>
        <div><a href="{{ route('book.create') }}" class="btn btn-primary mb-3">Создать</a></div>
        @foreach ($books as $book)
          <div><a href="{{ route('book.show', $book->id) }}" class="btn btn-light mb-3">{{ $book->id }}. {{ $book->title }}</a></div>
        @endforeach
        <div>
            {{ $books->links() }}
        </div>
    </div>
@endsection
