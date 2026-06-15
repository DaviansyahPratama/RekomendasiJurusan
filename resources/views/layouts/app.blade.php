<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Sistem Rekomendasi Jurusan' }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-slate-50 text-slate-900">
    <div class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-purple-50">
        <div class="flex min-h-screen">
            <aside class="hidden md:flex w-64 flex-col border-r border-slate-200/80 bg-white/80 backdrop-blur-xl shadow-sm">
                <div class="p-6 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="h-11 w-11 rounded-2xl bg-gradient-to-br from-indigo-600 to-purple-600 text-white flex items-center justify-center font-bold shadow-lg shadow-indigo-200">
                            SJ
                        </div>
                        <div>
                            <div class="text-sm font-bold text-slate-900">Rekomendasi Jurusan</div>
                            <div class="text-xs text-slate-500">Relasi & Kombinatorika</div>
                        </div>
                    </div>
                </div>

                @php
                    $nav = [
                        ['route' => 'dashboard', 'label' => 'Dashboard', 'icon' => '📊'],
                        ['route' => 'students.index', 'label' => 'Mahasiswa', 'icon' => '🎓'],
                        ['route' => 'scores.index', 'label' => 'Nilai', 'icon' => '📝'],
                        ['route' => 'recommendations.index', 'label' => 'Rekomendasi', 'icon' => '🏆'],
                        ['route' => 'groups.combinations', 'label' => 'Kombinasi', 'icon' => '∑'],
                    ];
                @endphp

                <nav class="flex-1 p-4 space-y-1">
                    @foreach($nav as $item)
                        @php
                            $active = request()->routeIs($item['route'])
                                || ($item['route'] === 'recommendations.index' && request()->routeIs('recommendation.show'));
                        @endphp
                        <a href="{{ route($item['route']) }}"
                           class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
                           {{ $active ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-md shadow-indigo-200' : 'text-slate-600 hover:bg-indigo-50 hover:text-indigo-700' }}">
                            <span class="text-base">{{ $item['icon'] }}</span>
                            <span>{{ $item['label'] }}</span>
                        </a>
                    @endforeach
                </nav>

                <div class="p-4 m-4 rounded-2xl bg-gradient-to-br from-indigo-600 to-purple-600 text-white text-xs">
                    <div class="font-semibold">Laravel 11</div>
                    <div class="mt-1 text-white/80">Scoring · Decision Tree · Kombinatorika</div>
                </div>
            </aside>

            <main class="flex-1 min-w-0">
                <header class="md:hidden sticky top-0 z-20 bg-white/90 backdrop-blur border-b border-slate-200 px-4 py-3">
                    <div class="flex items-center justify-between">
                        <div class="font-bold text-sm">Rekomendasi Jurusan</div>
                        <a href="{{ route('students.create') }}" class="text-xs font-semibold px-3 py-2 rounded-lg bg-indigo-600 text-white">+ Mahasiswa</a>
                    </div>
                    <div class="mt-2 flex gap-2 overflow-x-auto pb-1">
                        @foreach($nav as $item)
                            <a href="{{ route($item['route']) }}" class="shrink-0 text-xs px-3 py-1.5 rounded-full border border-slate-200 bg-white">{{ $item['label'] }}</a>
                        @endforeach
                    </div>
                </header>

                <div class="px-4 md:px-8 py-8">
                    @if(session('success'))
                        <div class="mb-6 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 text-sm">
                            {{ session('success') }}
                        </div>
                    @endif
                    @yield('content')
                </div>
            </main>
        </div>
    </div>
</body>
</html>
