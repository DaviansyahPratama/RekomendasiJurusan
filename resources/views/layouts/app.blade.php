<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Sistem Rekomendasi Mata Kuliah' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .sidebar { min-height: 100vh; background: linear-gradient(180deg, #0d6efd 0%, #6610f2 100%); }
        .sidebar .nav-link { color: rgba(255,255,255,.85); border-radius: .5rem; margin-bottom: .25rem; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { background: rgba(255,255,255,.15); color: #fff; }
        .card-stat { border: none; box-shadow: 0 .125rem .25rem rgba(0,0,0,.075); }
    </style>
    @stack('styles')
</head>
<body>
<div class="container-fluid">
    <div class="row">
        @auth
        <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse show p-3">
            <div class="text-white mb-4">
                <h5 class="fw-bold mb-0"><i class="bi bi-mortarboard-fill me-2"></i>Rekomendasi MK</h5>
                <small class="text-white-50">Mata Kuliah Mahasiswa</small>
            </div>
            <ul class="nav flex-column">
                @if(auth()->user()->isAdmin())
                    @foreach([
                        ['route' => 'admin.dashboard', 'label' => 'Dashboard', 'icon' => 'speedometer2'],
                        ['route' => 'admin.mata-kuliah.index', 'label' => 'Mata Kuliah', 'icon' => 'book'],
                        ['route' => 'admin.minat.index', 'label' => 'Minat', 'icon' => 'heart'],
                        ['route' => 'admin.prasyarat.index', 'label' => 'Prasyarat', 'icon' => 'diagram-3'],
                        ['route' => 'admin.nilai.index', 'label' => 'Nilai Mahasiswa', 'icon' => 'clipboard-data'],
                        ['route' => 'admin.mahasiswa.index', 'label' => 'Data Mahasiswa', 'icon' => 'people'],
                    ] as $item)
                        <li class="nav-item">
                            <a href="{{ route($item['route']) }}" class="nav-link {{ request()->routeIs(str_replace('.index', '.*', $item['route'])) || request()->routeIs($item['route']) ? 'active' : '' }}">
                                <i class="bi bi-{{ $item['icon'] }} me-2"></i>{{ $item['label'] }}
                            </a>
                        </li>
                    @endforeach
                @else
                    @foreach([
                        ['route' => 'mahasiswa.dashboard', 'label' => 'Dashboard', 'icon' => 'speedometer2'],
                        ['route' => 'mahasiswa.profil', 'label' => 'Profil', 'icon' => 'person'],
                        ['route' => 'mahasiswa.riwayat-nilai', 'label' => 'Riwayat Nilai', 'icon' => 'journal-text'],
                        ['route' => 'mahasiswa.pilih-minat', 'label' => 'Pilih Minat', 'icon' => 'heart'],
                        ['route' => 'mahasiswa.rekomendasi', 'label' => 'Rekomendasi', 'icon' => 'stars'],
                        ['route' => 'mahasiswa.simulasi', 'label' => 'Simulasi MK', 'icon' => 'calculator'],
                        ['route' => 'mahasiswa.grafik', 'label' => 'Grafik', 'icon' => 'bar-chart'],
                    ] as $item)
                        <li class="nav-item">
                            <a href="{{ route($item['route']) }}" class="nav-link {{ request()->routeIs($item['route']) ? 'active' : '' }}">
                                <i class="bi bi-{{ $item['icon'] }} me-2"></i>{{ $item['label'] }}
                            </a>
                        </li>
                    @endforeach
                @endif
            </ul>
            <div class="mt-4 pt-3 border-top border-white border-opacity-25">
                <div class="text-white-50 small mb-2">{{ auth()->user()->name }}</div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline-light btn-sm w-100"><i class="bi bi-box-arrow-right me-1"></i>Logout</button>
                </form>
            </div>
        </nav>
        @endauth

        <main class="@auth col-md-9 ms-sm-auto col-lg-10 @else col-12 @endauth px-md-4 py-4">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @yield('content')
        </main>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
@stack('scripts')
</body>
</html>
