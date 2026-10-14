<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin') | Coopera Trading Admin</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans antialiased bg-gray-50 text-gray-900">
<div class="min-h-screen flex">
    <aside class="hidden md:flex md:w-64 bg-slate-900 text-white flex-col">
        <div class="px-6 py-6 border-b border-slate-800">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                <img src="{{ asset('assets/brand/logo-mark-gradient-white.svg') }}" alt="Coopera Trading" class="h-9 w-auto">
                <span class="font-semibold text-lg">Coopera Trading</span>
            </a>
            <p class="text-xs text-slate-400 mt-1 ml-12">Admin Console</p>
        </div>
        <nav class="flex-1 px-3 py-4 space-y-1 text-sm">
            @php
                $currentUser = auth()->user();
                $navLink = function (string $name, string $label, string $icon) {
                    $active = request()->routeIs($name) || request()->routeIs($name . '.*');
                    $classes = $active
                        ? 'bg-slate-800 text-white font-medium'
                        : 'text-slate-300 hover:bg-slate-800 hover:text-white';
                    return '<a href="' . route($name) . '" class="flex items-center gap-3 px-3 py-2 rounded-md ' . $classes . '"><span class="w-5 h-5 inline-flex items-center justify-center text-base">' . $icon . '</span>' . $label . '</a>';
                };
            @endphp
            {!! $navLink('admin.dashboard', 'Dashboard', '🏠') !!}

            @if ($currentUser && ($currentUser->hasPermission('categories') || $currentUser->hasPermission('products') || $currentUser->hasPermission('partners')))
                <div class="pt-4 pb-1 px-3 text-xs uppercase tracking-wider text-slate-500">Catalog</div>
                @if ($currentUser->hasPermission('categories'))
                    {!! $navLink('admin.categories.index', 'Categories', '📂') !!}
                @endif
                @if ($currentUser->hasPermission('products'))
                    {!! $navLink('admin.products.index', 'Products', '📦') !!}
                @endif
                @if ($currentUser->hasPermission('partners'))
                    {!! $navLink('admin.partners.index', 'Partners', '🤝') !!}
                @endif
                @if ($currentUser->hasPermission('hero-slides'))
                    {!! $navLink('admin.hero-slides.index', 'Hero Slides', '🖼️') !!}
                @endif
            @endif

            @if ($currentUser && ($currentUser->hasPermission('quotes') || $currentUser->hasPermission('templates')))
                <div class="pt-4 pb-1 px-3 text-xs uppercase tracking-wider text-slate-500">Sales</div>
                @if ($currentUser->hasPermission('quotes'))
                    {!! $navLink('admin.quote-requests.index', 'Quote Requests', '📨') !!}
                @endif
                @if ($currentUser->hasPermission('templates'))
                    {!! $navLink('admin.proforma-templates.index', 'Proforma Template', '🎛️') !!}
                @endif
            @endif

            @if ($currentUser && $currentUser->hasPermission('users'))
                <div class="pt-4 pb-1 px-3 text-xs uppercase tracking-wider text-slate-500">Administration</div>
                {!! $navLink('admin.users.index', 'Admin Users', '👥') !!}
            @endif
        </nav>
        <div class="px-3 py-4 border-t border-slate-800">
            <div class="flex items-center justify-between px-3">
                <span class="text-xs text-slate-400">Signed in as</span>
                <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded {{ auth()->user()->isSuperAdmin() ? 'bg-amber-500/20 text-amber-300 border border-amber-500/30' : 'bg-slate-800 text-slate-300' }}">
                    {{ auth()->user()->isSuperAdmin() ? 'Super Admin' : 'Admin' }}
                </span>
            </div>
            <div class="px-3 py-1 text-sm font-medium text-white truncate">{{ auth()->user()->name }}</div>
            <form method="POST" action="{{ route('logout') }}" class="mt-2">
                @csrf
                <button type="submit" class="w-full text-left px-3 py-2 rounded-md text-sm text-slate-300 hover:bg-slate-800 hover:text-white">Sign out</button>
            </form>
            <a href="{{ route('home') }}" class="block mt-1 px-3 py-2 rounded-md text-sm text-indigo-300 hover:bg-slate-800">View site →</a>
        </div>
    </aside>

    <div class="flex-1 flex flex-col min-w-0">
        <header class="bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold text-gray-900">@yield('header', 'Dashboard')</h1>
                @hasSection('subheader')
                    <p class="text-sm text-gray-500">@yield('subheader')</p>
                @endif
            </div>
            <div class="flex items-center gap-3">
                @yield('actions')
            </div>
        </header>

        @if (session('status'))
            <div class="px-6 pt-4">
                <div class="rounded-md bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-800">
                    {{ session('status') }}
                </div>
            </div>
        @endif

        <main class="flex-1 px-6 py-6">
            @yield('content')
        </main>
    </div>
</div>
</body>
</html>
