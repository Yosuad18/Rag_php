<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name'))</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=bricolage-grotesque:400,500,600,700|dm-sans:400,500,600,700" rel="stylesheet" />
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif
</head>
<body class="bg-[#F8FAF9] text-gray-900 antialiased font-['DM_Sans',sans-serif]">
    <nav class="bg-white border-b border-gray-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ url('/') }}" class="text-xl font-bold font-['Bricolage_Grotesque',sans-serif] text-emerald-600 tracking-tight">{{ config('app.name') }}</a>
                    <div class="ml-10 hidden md:flex items-center space-x-1">
                        <a href="{{ route('chat.index') }}" class="px-3 py-2 rounded-md text-sm font-medium text-gray-600 hover:text-emerald-600 hover:bg-emerald-50 transition-colors">Chat</a>
                        <a href="{{ route('productos.index') }}" class="px-3 py-2 rounded-md text-sm font-medium text-gray-600 hover:text-emerald-600 hover:bg-emerald-50 transition-colors">Productos</a>
                        <a href="{{ route('usuarios.index') }}" class="px-3 py-2 rounded-md text-sm font-medium text-gray-600 hover:text-emerald-600 hover:bg-emerald-50 transition-colors">Usuarios</a>
                        <a href="{{ route('repartidores.index') }}" class="px-3 py-2 rounded-md text-sm font-medium text-gray-600 hover:text-emerald-600 hover:bg-emerald-50 transition-colors">Repartidores</a>
                        <a href="{{ route('pagos.index') }}" class="px-3 py-2 rounded-md text-sm font-medium text-gray-600 hover:text-emerald-600 hover:bg-emerald-50 transition-colors">Pagos</a>
                    </div>
                </div>
                <div class="md:hidden flex items-center">
                    <button id="mobile-menu-btn" class="p-2 rounded-md text-gray-600 hover:text-emerald-600 hover:bg-emerald-50">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        <div id="mobile-menu" class="hidden md:hidden border-t border-gray-100">
            <div class="px-4 py-2 space-y-1">
                <a href="{{ route('chat.index') }}" class="block px-3 py-2 rounded-md text-sm font-medium text-gray-600 hover:text-emerald-600 hover:bg-emerald-50">Chat</a>
                <a href="{{ route('productos.index') }}" class="block px-3 py-2 rounded-md text-sm font-medium text-gray-600 hover:text-emerald-600 hover:bg-emerald-50">Productos</a>
                <a href="{{ route('usuarios.index') }}" class="block px-3 py-2 rounded-md text-sm font-medium text-gray-600 hover:text-emerald-600 hover:bg-emerald-50">Usuarios</a>
                <a href="{{ route('repartidores.index') }}" class="block px-3 py-2 rounded-md text-sm font-medium text-gray-600 hover:text-emerald-600 hover:bg-emerald-50">Repartidores</a>
                <a href="{{ route('pagos.index') }}" class="block px-3 py-2 rounded-md text-sm font-medium text-gray-600 hover:text-emerald-600 hover:bg-emerald-50">Pagos</a>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        @if (session('success'))
            <div class="mb-4 px-4 py-3 rounded-md bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 px-4 py-3 rounded-md bg-red-50 border border-red-200 text-red-800 text-sm">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="border-t border-gray-200 bg-white mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                <p class="text-sm text-gray-500">{{ config('app.name') }} &mdash; Gestiona tu negocio con inteligencia</p>
                <p class="text-xs text-gray-400">MongoDB &middot; Laravel &middot; OpenAI</p>
            </div>
        </div>
    </footer>

    <script>
        document.getElementById('mobile-menu-btn')?.addEventListener('click', () => {
            document.getElementById('mobile-menu')?.classList.toggle('hidden');
        });
    </script>
</body>
</html>
