<!-- resources/views/layouts/app.blade.php - UPDATE THEME -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Bani Parno - Database Keluarga')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .gradient-bg { 
            background: linear-gradient(135deg, #38bdf8 0%, #f97316 100%); 
        }
        .card-hover { 
            transition: all 0.3s ease; 
        }
        .card-hover:hover { 
            transform: translateY(-5px); 
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1); 
        }
        .nav-link {
            @apply text-gray-600 hover:text-orange-500 font-medium transition-colors duration-200;
        }
        .nav-link.active {
            @apply text-orange-500 border-b-2 border-orange-500 pb-1;
        }
        .mobile-nav-link {
            @apply block px-3 py-2 text-gray-600 hover:text-orange-500 hover:bg-blue-50 rounded-md;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center space-x-2">
                        <i class="fas fa-home text-2xl text-orange-500"></i>
                        <span class="text-xl font-bold text-gray-900">Bani Parno</span>
                    </a>
                </div>
                
                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-6">
                    <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                        <i class="fas fa-home mr-1"></i> Beranda
                    </a>
                    <a href="{{ route('families.index') }}" class="nav-link {{ request()->routeIs('families.*') ? 'active' : '' }}">
                        <i class="fas fa-users mr-1"></i> Keluarga
                    </a>
                    <a href="{{ route('members.index') }}" class="nav-link {{ request()->routeIs('members.*') ? 'active' : '' }}">
                        <i class="fas fa-user-friends mr-1"></i> Anggota
                    </a>
                    <a href="{{ route('public.activity_logs') }}" class="nav-link {{ request()->routeIs('public.activity_logs') ? 'active' : '' }}">
                        <i class="fas fa-history mr-1"></i> Riwayat Perubahan
                    </a>
                    <div class="flex items-center space-x-3 ml-4 border-l border-gray-300 pl-6">
                    <a href="{{ route('auth.login') }}" class="nav-link {{ request()->routeIs('auth.login') ? 'active' : '' }}">
                    <i class="fas fa-sign-in-alt mr-1"></i> Login
                    </a>
                         <a href="{{ route('family.form') }}" 
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition duration-200">
                    <i class="fas fa-user-plus mr-1"></i> Daftar
                        </a>

                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button id="mobile-menu-button" class="text-gray-500 hover:text-orange-500">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <div id="mobile-menu" class="hidden md:hidden bg-white border-t">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="{{ route('home') }}" class="mobile-nav-link">
                    <i class="fas fa-home mr-2"></i>Beranda
                </a>
                <a href="{{ route('families.index') }}" class="mobile-nav-link">
                    <i class="fas fa-users mr-2"></i>Keluarga
                </a>
                <a href="{{ route('members.index') }}" class="mobile-nav-link">
                    <i class="fas fa-user-friends mr-2"></i>Anggota
                </a>
                <a href="{{ route('public.activity_logs') }}" class="mobile-nav-link">
                    <i class="fas fa-history mr-2"></i>Riwayat Perubahan
                </a>
                <a href="/admin" class="mobile-nav-link">
                    <i class="fas fa-cog mr-2"></i>Admin
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-orange-600 text-white py-12 mt-16">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-lg font-semibold mb-4">Bani Parno</h3>
                    <p class="text-orange-100">Sistem manajemen database keluarga yang membantu mengelola dan menyimpan informasi keluarga besar.</p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Menu</h3>
                    <ul class="space-y-2 text-orange-100">
                        <li><a href="{{ route('home') }}" class="hover:text-white transition">Beranda</a></li>
                        <li><a href="{{ route('families.index') }}" class="hover:text-white transition">Keluarga</a></li>
                        <li><a href="{{ route('members.index') }}" class="hover:text-white transition">Anggota</a></li>
                        <li><a href="{{ route('public.activity_logs') }}" class="hover:text-white transition">Riwayat Perubahan</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Statistik</h3>
                    <div class="text-orange-100 space-y-1">
                        <p><i class="fas fa-home mr-2"></i>{{ App\Models\Family::count() }} Keluarga Besar</p>
                        <p><i class="fas fa-users mr-2"></i>{{ App\Models\Member::count() }} Total Anggota</p>
                    </div>
                </div>
            </div>
            <div class="border-t border-orange-400 mt-8 pt-8 text-center text-orange-100">
                <p>&copy; {{ date('Y') }} Bani Parno. Database Keluarga.</p>
            </div>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        });

        // Smooth scrolling
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
</body>
</html>
