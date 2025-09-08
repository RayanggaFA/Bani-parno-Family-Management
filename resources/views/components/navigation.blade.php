<!-- resources/views/components/navigation.blade.php - UPDATE EXISTING FILE -->
<nav class="bg-white shadow-lg sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex justify-between items-center py-4">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="text-2xl font-bold text-blue-600">
                    Bani Parno
                </a>
            </div>

            <!-- Navigation Links -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="{{ route('home') }}" class="text-gray-700 hover:text-blue-600 transition">
                    <i class="fas fa-home mr-2"></i>Beranda
                </a>
                <a href="{{ route('families.index') }}" class="text-gray-700 hover:text-blue-600 transition">
                    <i class="fas fa-users mr-2"></i>Keluarga
                </a>
                <a href="{{ route('members.index') }}" class="text-gray-700 hover:text-blue-600 transition">
                    <i class="fas fa-user-friends mr-2"></i>Anggota
                </a>
                <a href="{{ route('activity.history') }}" class="text-gray-700 hover:text-blue-600 transition">
                    <i class="fas fa-history mr-2"></i>Riwayat
                </a>
            </div>

            <!-- Auth Section -->
            <div class="flex items-center space-x-4">
                @auth('family')
                    <!-- Logged in family admin -->
                    <div class="relative group">
                        <button class="flex items-center space-x-2 text-gray-700 hover:text-blue-600 transition">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-user-shield text-blue-600 text-sm"></i>
                            </div>
                            <span class="font-medium">{{ Auth::guard('family')->user()->name }}</span>
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                        
                        <!-- Dropdown Menu -->
                        <div class="absolute right-0 mt-2 w-64 bg-white rounded-lg shadow-lg border opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                            <div class="py-2">
                                <div class="px-4 py-2 border-b border-gray-100">
                                    <p class="text-sm font-medium text-gray-900">Admin Keluarga</p>
                                    <p class="text-xs text-gray-500">{{ Auth::guard('family')->user()->username }}</p>
                                </div>
                                
                                <a href="{{ route('families.show', Auth::guard('family')->user()) }}" 
                                   class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition">
                                    <i class="fas fa-home mr-3 w-4"></i>Detail Keluarga
                                </a>
                                
                                <a href="{{ route('families.edit', Auth::guard('family')->user()) }}" 
                                   class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition">
                                    <i class="fas fa-edit mr-3 w-4"></i>Edit Keluarga
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
                @else
                    <!-- Not logged in -->
                    <a href="{{ route('auth.login') }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
                        <i class="fas fa-sign-in-alt mr-2"></i>Login
                    </a>
                    <a href="{{ route('families.create') }}" 
                       class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition">
                        <i class="fas fa-user-plus mr-2"></i>Daftar
                    </a>
                @endauth
            </div>

            <!-- Mobile menu button -->
            <div class="md:hidden">
                <button onclick="toggleMobileMenu()" class="text-gray-700 hover:text-blue-600">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobileMenu" class="hidden md:hidden bg-white border-t border-gray-200">
        <div class="px-4 py-4 space-y-4">
            <a href="{{ route('home') }}" class="flex items-center text-gray-700 hover:text-blue-600 transition">
                <i class="fas fa-home mr-3 w-5"></i>Beranda
            </a>
            <a href="{{ route('families.index') }}" class="flex items-center text-gray-700 hover:text-blue-600 transition">
                <i class="fas fa-users mr-3 w-5"></i>Keluarga
            </a>
            <a href="{{ route('members.index') }}" class="flex items-center text-gray-700 hover:text-blue-600 transition">
                <i class="fas fa-user-friends mr-3 w-5"></i>Anggota
            </a>
            <a href="{{ route('activity.history') }}" class="flex items-center text-gray-700 hover:text-blue-600 transition">
                <i class="fas fa-history mr-3 w-5"></i>Riwayat
            </a>
            
            @auth('family')
                <div class="border-t border-gray-200 pt-4 space-y-4">
                    <div class="text-sm font-medium text-gray-900">{{ Auth::guard('family')->user()->name }}</div>
                    <a href="{{ route('families.show', Auth::guard('family')->user()) }}" 
                       class="flex items-center text-gray-700 hover:text-blue-600 transition">
                        <i class="fas fa-home mr-3 w-5"></i>Detail Keluarga
                    </a>
                    <a href="{{ route('members.create') }}" 
                       class="flex items-center text-gray-700 hover:text-blue-600 transition">
                        <i class="fas fa-user-plus mr-3 w-5"></i>Tambah Anggota
                    </a>
                    <form method="POST" action="{{ route('auth.logout') }}">
                        @csrf
                        <button type="submit" 
                                class="flex items-center text-red-600 hover:text-red-700 transition">
                            <i class="fas fa-sign-out-alt mr-3 w-5"></i>Logout
                        </button>
                    </form>
                </div>
            @else
                <div class="border-t border-gray-200 pt-4 space-y-4">
                    <a href="{{ route('auth.login') }}" 
                       class="flex items-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
                        <i class="fas fa-sign-in-alt mr-2"></i>Login
                    </a>
                    <a href="{{ route('families.create') }}" 
                       class="flex items-center bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition">
                        <i class="fas fa-user-plus mr-2"></i>Daftar
                    </a>
                </div>
            @endauth
        </div>
    </div>
</nav>

<script>
function toggleMobileMenu() {
    const menu = document.getElementById('mobileMenu');
    menu.classList.toggle('hidden');
}
</script>