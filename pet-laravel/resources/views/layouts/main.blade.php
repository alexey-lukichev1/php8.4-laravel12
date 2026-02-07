<!DOCTYPE html>
<html lang="en">
<head>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Document</title>
</head>
<body>
    <div class="container">
        <div class="row">
            <nav class="nav nav-pills flex-column flex-sm-row">
                <a class="flex-sm-fill text-sm-center nav-link" href="{{ route('book.index') }}">Books</a>
                <a class="flex-sm-fill text-sm-center nav-link" href="{{ route('main.index') }}">Main</a>
                <a class="flex-sm-fill text-sm-center nav-link" href="{{ route('about.index') }}">About</a>
                <a class="flex-sm-fill text-sm-center nav-link" href="{{ route('contact.index') }}">Contacts</a>
            </nav>
        </div>
        @yield('content')
    </div>
</body>
</html>
