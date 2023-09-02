<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ env('APP_NAME', 'Laravel') }}</title>
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.png')}}">
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <!-- Styles -->
        <link href="{{ asset('singularity-crud::app.css') }}" rel="stylesheet">

    </head>
    <body class="antialiased min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center sm:pt-0 flex flex-col">
        @include('singularity-crud::includes.header')

        @yield('content')

        @include('singularity-crud::includes.footer')
    </body>
</html>
