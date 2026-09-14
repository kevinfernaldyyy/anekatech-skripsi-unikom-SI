<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - Diablo Admin</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('img/logo.jpeg') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] { display: none !important; }
        @font-face {
            font-family: 'Plus Jakarta Sans';
            src: url('{{ asset('fonts/PlusJakartaSans-Light.ttf') }}') format('truetype');
            font-weight: 300;
            font-display: swap;
        }
        @font-face {
            font-family: 'Plus Jakarta Sans';
            src: url('{{ asset('fonts/PlusJakartaSans-Regular.ttf') }}') format('truetype');
            font-weight: 400;
            font-display: swap;
        }
        @font-face {
            font-family: 'Plus Jakarta Sans';
            src: url('{{ asset('fonts/PlusJakartaSans-Medium.ttf') }}') format('truetype');
            font-weight: 500;
            font-display: swap;
        }
        @font-face {
            font-family: 'Plus Jakarta Sans';
            src: url('{{ asset('fonts/PlusJakartaSans-SemiBold.ttf') }}') format('truetype');
            font-weight: 600;
            font-display: swap;
        }
        @font-face {
            font-family: 'Plus Jakarta Sans';
            src: url('{{ asset('fonts/PlusJakartaSans-Bold.ttf') }}') format('truetype');
            font-weight: 700;
            font-display: swap;
        }
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 text-slate-800 antialiased" x-data="{ sidebarOpen: false }">

    <div class="flex h-screen overflow-hidden">
        
        @include('layouts.partials.sidebar')

        <div class="relative flex flex-1 flex-col overflow-y-auto overflow-x-hidden">
            
            @include('layouts.partials.header')

            <main class="w-full flex-grow p-6 lg:p-10">
                @yield('content')
            </main>

            @include('layouts.partials.footer')
            
        </div>
    </div>

</body>
</html>