<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Google Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css?v={{ time() }}" rel="stylesheet" onerror="this.onerror=null;this.href='https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css'">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
        
        <!-- Custom CSS -->
        <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    </head>
    <body>
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <div class="bg-white shadow-sm">
                <div class="container py-4">
                    {{ $header }}
                </div>
            </div>
        @endisset

        <!-- Page Content -->
        <main>
            @yield('content')
        </main>
        
        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" onerror="this.onerror=null;this.src='https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js'"></script>
        
        <!-- Custom JS -->
        <script src="{{ asset('js/app.js') }}"></script>
        
        @stack('scripts')
    </body>
</html>
