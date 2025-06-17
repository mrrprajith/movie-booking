<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <title>Admin Panel : {{ config('app.name', '') }}</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            
            <nav class="bg-white border-b border-gray-100">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <div class="shrink-0 flex items-center">
                                <a href="{{ url('/admin/dashboard') }}">
                                    <h1 class="font-bold text-lg">Admin Panel</h1>
                                </a>
                            </div>
                        </div>

                        <!-- Right Side: Logout -->
                        <div class="hidden sm:flex sm:items-center sm:ml-6">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="text-gray-700 hover:text-gray-900">Logout</button>
                            </form>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <main class="py-6">
                @yield('content')
            </main>
        </div>
    </body>
</html>