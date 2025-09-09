<nav class="bg-white shadow-lg sticky top-0 z-40 border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex justify-between items-center h-16">
            <!-- Logo -->
            <div class="flex items-center space-x-4">
                <a href="{{ route('home') }}" class="flex items-center space-x-2">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-purple-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-home text-white text-lg"></i>
                    </div>
                    <span class="text-xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                        Bani Parno
                    </span>
                </a>
            </div>

            <!-- Desktop Navigation -->
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
                <a href="{{ route('activity.history') }}" class="nav-link {{ request()->routeIs('activity.*') ? 'active' : '' }}">
                    <i class="fas fa-history mr-1"></i> Riwayat Perubahan
                </a>
                
                <!-- Auth Section -->
                @guest('family')
                    <div class="flex items-center space-x-3 ml-4 border-l border-gray-300 pl-6">
                        <a href="{{ route('auth.login') }}" class="nav-link">
                            <i class="fas fa-sign-in-alt mr-1"></i> Login
                        </a>
                        <a href="{{ route('families.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition duration-200">
                            <i class="fas fa-user-plus mr-1"></i> Daftar
                        </a>
                    </div>
                @else
                    <div class="relative group ml-4 border-l border-gray-300 pl-6">
                        <button class="flex items-center space-x-2 bg-blue-50 hover:bg-blue-100 px-3 py-2 rounded-lg transition">
                            <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                                <i class="fas fa-user-shield text-white text-sm"></i>
                            </div>
                            <div class="text-left hidden lg:block">
                                <div class="text-sm font-medium text-gray-900">{{ Auth::guard('family')->user()->name }}</div>
                                <div class="text-xs text-gray-500">Admin Keluarga</div>
                            </div>
                            <i class="fas fa-chevron-down text-xs text-gray-500"></i>
                        </button>
                        
                        <div class="absolute right-0 mt-2 w-64 bg-white rounded-lg shadow-xl border opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                            <div class="py-2">
                                <div class="px-4 py-3 border-b border-gray-100">
                                    <p class="text-sm font-medium text-gray-900">{{ Auth::guard('family')->user()->name }}</p>
                                    <p class="text-xs text-gray-500">@{{ Auth::guard('family')->user()->username }}</p>
                                </div>
                                
                                <a href="{{ route('families.show', Auth::guard('family')->user()) }}" 
                                   class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition">
                                    <i class="fas fa-tachometer-alt mr-3 w-4"></i>Dashboard
                                </a>
                                
                                <a href="{{ route('families.edit', Auth::guard('family')->user()) }}" 
                                   class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition">
                                    <i class="fas fa-edit mr-3 w-4"></i>Edit Profil
                                </a>
                                
                                <a href="{{ route('members.create') }}" 
                                   class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition">
                                    <i class="fas fa-user-plus mr-3 w-4"></i>Tambah Anggota
                                </a>
                                
                                <div class="border-t border-gray-100 mt-2 pt-2">
                                    <form method="POST" action="{{ route('auth.logout') }}">
                                        @csrf
                                        <button type="submit" 
                                                class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition">
                                            <i class="fas fa-sign-out-alt mr-3 w-4"></i>Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endguest
            </div>
                        
                        <!-- Dropdown Menu -->
                        <div class="absolute right-0 mt-2 w-64 bg-white rounded-lg shadow-xl border opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                            <div class="py-2">
                                <div class="px-4 py-3 border-b border-gray-100">
                                    <p class="text-sm font-medium text-gray-900">{{ Auth::guard('family')->user()->name }}</p>
                                    <p class="text-xs text-gray-500">@{{ Auth::guard('family')->user()->username }}</p>
                                </div>
                                
                                <a href="{{ route('families.show', Auth::guard('family')->user()) }}" 
                                   class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition">
                                    <i class="fas fa-tachometer-alt mr-3 w-4"></i>Dashboard
                                </a>
                                
                                <a href="{{ route('families.edit', Auth::guard('family')->user()) }}" 
                                   class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition">
                                    <i class="fas fa-edit mr-3 w-4"></i>Edit Profil
                                </a>
                                
                                <a href="{{ route('members.create') }}" 
                                   class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition">
                                    <i class="fas fa-user-plus mr-3 w-4"></i>Tambah Anggota
                                </a>
                                
                                <div class="border-t border-gray-100 mt-2 pt-2">
                                    <form method="POST" action="{{ route('auth.logout') }}">
                                        @csrf
                                        <button type="submit" 
                                                class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition">
                                            <i class="fas fa-sign-out-alt mr-3 w-4"></i>Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endguest
            </div>

            <!-- Mobile menu button -->
            <button id="mobile-menu-btn" class="md:hidden text-gray-700 hover:text-blue-600">
                <i class="fas fa-bars text-xl"></i>
            </button>
        </div>
    </div>
</nav>

<style>
.nav-link {
    @apply text-gray-700 hover:text-blue-600 transition duration-200 px-3 py-2 rounded-lg font-medium;
}

.nav-link.active {
    @apply text-blue-600 bg-blue-50;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');
    
    if (mobileMenuBtn && mobileMenu) {
        mobileMenuBtn.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');
        });
    }
});
</script>
