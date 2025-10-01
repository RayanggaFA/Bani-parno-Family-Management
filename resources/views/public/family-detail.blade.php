{{-- resources/views/public/families/show.blade.php - ENHANCED WITH CRUD --}}
@extends('layouts.app')

@section('title', $family->name . ' - Dashboard Keluarga')

@section('content')
<!-- Family Header -->
<section class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-16">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-8">
            <div class="flex-1">
                <div class="flex items-center mb-4">
                    <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center mr-4">
                        <i class="fas fa-home text-white text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-4xl font-bold mb-2">{{ $family->name }}</h1>
                        <p class="text-indigo-100 flex items-center">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            {{ $family->domicile }}
                        </p>
                        <p class="text-indigo-200 text-sm mt-1">
                            <i class="fas fa-user mr-2"></i>Admin: {{ $family->username }}
                        </p>
                    </div>
                </div>
                
                @if($family->description)
                    <p class="text-lg text-indigo-100 leading-relaxed mb-6">{{ $family->description }}</p>
                @endif
                
                <!-- Family Stats -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold">{{ $stats['total_members'] ?? 0 }}</div>
                        <div class="text-indigo-100 text-sm">Total Anggota</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold">{{ $stats['male_members'] ?? 0 }}</div>
                        <div class="text-indigo-100 text-sm">Laki-laki</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold">{{ $stats['female_members'] ?? 0 }}</div>
                        <div class="text-indigo-100 text-sm">Perempuan</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold">{{ $stats['married_members'] ?? 0 }}</div>
                        <div class="text-indigo-100 text-sm">Menikah</div>
                    </div>
                </div>
            </div>
</section>

<!-- Success/Error Messages -->
@if(session('success'))
    <div class="bg-green-50 border-l-4 border-green-500 p-4 mx-4 my-4 rounded-r-lg">
        <div class="flex items-center">
            <i class="fas fa-check-circle text-green-500 mr-3"></i>
            <p class="text-green-800 font-medium">{{ session('success') }}</p>
        </div>
    </div>
@endif

@if(session('error'))
    <div class="bg-red-50 border-l-4 border-red-500 p-4 mx-4 my-4 rounded-r-lg">
        <div class="flex items-center">
            <i class="fas fa-exclamation-circle text-red-500 mr-3"></i>
            <p class="text-red-800 font-medium">{{ session('error') }}</p>
        </div>
    </div>
@endif

<!-- Members Section -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex items-center justify-between mb-12">
            <div>
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Anggota Keluarga</h2>
                <p class="text-gray-600">Kelola semua anggota dalam keluarga {{ $family->name }}</p>
            </div>
            
            <!-- FIXED: Tombol Tambah Anggota dengan auth yang benar -->
            @auth('family')
                    <div class="flex justify-center sm:justify-end">
                        <a href="{{ route('members.create') }}" 
                           class="bg-green-600 hover:bg-green-700 text-white font-medium py-3 px-6 rounded-lg transition flex items-center shadow-lg transform hover:scale-105">
                            <i class="fas fa-user-plus mr-2"></i>
                            Tambah Anggota
                        </a>
                    </div>
            @endauth
        </div>

        @if($allMembers->count() > 0)
            <!-- Members Grid with CRUD Actions -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="membersGrid">
                @foreach($allMembers as $member)
                    <div class="member-card bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition duration-300 transform hover:-translate-y-1 border border-gray-100">
                        <!-- Member Avatar/Photo -->
                        <div class="relative">
                            @php
                                // Debug: tampilkan nilai gender (hapus setelah fix)
                                $genderValue = strtolower(trim($member->gender ?? ''));
                                
                                // Deteksi laki-laki dengan berbagai variasi
                                $isLaki = in_array($genderValue, ['male', 'laki-laki', 'laki', 'l', 'm', '1', 'pria']);
                                
                                $bgGradient = $isLaki ? 'from-blue-200 to-indigo-300' : 'from-pink-200 to-purple-300';
                                $iconColor = $isLaki ? 'blue' : 'pink';
                                $badgeColor = $isLaki ? 'bg-blue-500' : 'bg-pink-500';
                                $genderIcon = $isLaki ? 'mars' : 'venus';
                            @endphp
                            
                            @if($member->profile_photo)
                                <!-- User Uploaded Photo -->
                                <div class="w-full h-48 overflow-hidden bg-gray-100">
                                    <img src="{{ $member->profile_photo_url }}" 
                                         alt="{{ $member->full_name }}"
                                         class="w-full h-full object-cover object-center"
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    <!-- Fallback if image fails to load -->
                                    <div class="w-full h-48 bg-gradient-to-br {{ $bgGradient }} flex items-center justify-center" style="display: none;">
                                        <i class="fas fa-user text-{{ $iconColor }}-600 text-6xl opacity-50"></i>
                                    </div>
                                </div>
                            @else
                                <!-- Default Avatar -->
                                <div class="w-full h-48 bg-gradient-to-br {{ $bgGradient }} flex items-center justify-center">
                                    <i class="fas fa-user text-{{ $iconColor }}-600 text-6xl opacity-50"></i>
                                </div>
                            @endif
                            
                            <!-- Gender Badge -->
                            <div class="absolute top-3 right-3 w-8 h-8 {{ $badgeColor }} rounded-full flex items-center justify-center shadow-lg">
                                <i class="fas fa-{{ $genderIcon }} text-white text-sm"></i>
                            </div>
                            
                            <!-- Photo Upload Indicator -->
                            
                            <!-- Status Badge - Only show if actually dead -->
                            @if(isset($member->is_alive) && !$member->is_alive)
                                <div class="absolute bottom-3 left-3 bg-gray-600 text-white text-xs font-bold px-2 py-1 rounded-full">
                                    <i class="fas fa-cross mr-1"></i>Almarhum
                                </div>
                            @elseif(isset($member->death_date) && $member->death_date)
                                <div class="absolute bottom-3 left-3 bg-gray-600 text-white text-xs font-bold px-2 py-1 rounded-full">
                                    <i class="fas fa-cross mr-1"></i>Almarhum
                                </div>
                            @endif
                        </div>
                        
                        <!-- Member Info -->
                        <div class="p-6">
                            <div class="mb-4">
                                <h3 class="text-lg font-bold text-gray-900 mb-1">{{ $member->full_name }}</h3>
                                @if($member->parent)
                                    <p class="text-sm text-blue-600">
                                        <i class="fas fa-link mr-1"></i>Anak dari {{ $member->parent->full_name }}
                                    </p>
                                @endif
                            </div>
                            
                            <div class="space-y-2 text-sm text-gray-600 mb-4">
                                @if($member->birth_date)
                                    <p class="flex items-center">
                                        <i class="fas fa-birthday-cake mr-2 w-4 text-orange-500"></i>
                                        @if($member->birth_place){{ $member->birth_place }}, @endif
                                        {{ \Carbon\Carbon::parse($member->birth_date)->format('d M Y') }}
                                        @if($member->age) ({{ $member->age }} th) @endif
                                    </p>
                                @endif
                                
                                @if($member->occupation)
                                    <p class="flex items-center">
                                        <i class="fas fa-briefcase mr-2 w-4 text-teal-500"></i>
                                        {{ $member->occupation }}
                                    </p>
                                @endif
                                
                                @if($member->phone)
                                    <p class="flex items-center">
                                        <i class="fas fa-phone mr-2 w-4 text-green-500"></i>
                                        {{ $member->phone }}
                                    </p>
                                @endif
                                
                                @if($member->address)
                                    <p class="flex items-center">
                                        <i class="fas fa-map-marker-alt mr-2 w-4 text-red-500"></i>
                                        {{ Str::limit($member->address, 30) }}
                                    </p>
                                @endif
                                
                                @if($member->children && $member->children->count() > 0)
                                    <p class="flex items-center text-indigo-600">
                                        <i class="fas fa-users mr-2 w-4"></i>
                                        {{ $member->children->count() }} anak
                                    </p>
                                @endif
                            </div>
                            
                            <!-- Status Badge -->
                            <div class="mb-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                    @if($member->marital_status === 'married') bg-green-100 text-green-800
                                    @elseif($member->marital_status === 'single') bg-blue-100 text-blue-800
                                    @elseif($member->marital_status === 'widowed') bg-orange-100 text-orange-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    <i class="fas fa-heart mr-1"></i>
                                    @if($member->marital_status === 'married') Menikah
                                    @elseif($member->marital_status === 'single') Belum Menikah
                                    @elseif($member->marital_status === 'widowed') Janda/Duda
                                    @else {{ ucfirst($member->marital_status) }}
                                    @endif
                                </span>
                            </div>
                            
                            <!-- CRUD Actions - FIXED AUTH -->
                            <div class="flex gap-2">
                                <!-- View Detail -->
                                <a href="{{ route('members.show', $member) }}" 
                                   class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white text-center py-2 px-3 rounded-lg transition text-sm font-medium">
                                    <i class="fas fa-eye mr-1"></i>Detail
                                </a>
                                
                                @auth('family')
                                    @if($isAdmin)
                                        <!-- Edit Member -->
                                        <a href="{{ route('members.edit', $member) }}" 
                                           class="bg-green-600 hover:bg-green-700 text-white py-2 px-3 rounded-lg transition text-sm font-medium"
                                           title="Edit {{ $member->full_name }}">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        
                                        <!-- Delete Member -->
                                        <button onclick="confirmDelete('{{ $member->full_name }}', '{{ route('members.destroy', $member) }}')"
                                                class="bg-red-600 hover:bg-red-700 text-white py-2 px-3 rounded-lg transition text-sm font-medium"
                                                title="Hapus {{ $member->full_name }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    @endif
                                @endauth
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Quick Stats - FIXED AUTH -->
            @auth('family')
                @if($isAdmin)
                    <div class="mt-12 bg-gray-50 rounded-2xl p-8">
                        <h3 class="text-xl font-bold text-gray-900 mb-6 text-center">Statistik Detail Keluarga</h3>
                        <div class="grid grid-cols-2 md:grid-cols-5 gap-6">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-blue-600">{{ $stats['male_members'] }}</div>
                                <div class="text-sm text-gray-600">Laki-laki</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-pink-600">{{ $stats['female_members'] }}</div>
                                <div class="text-sm text-gray-600">Perempuan</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-green-600">{{ $stats['married_members'] }}</div>
                                <div class="text-sm text-gray-600">Menikah</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-orange-600">{{ $stats['alive_members'] ?? $stats['total_members'] }}</div>
                                <div class="text-sm text-gray-600">Masih Hidup</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-indigo-600">{{ $allMembers->whereNull('parent_id')->count() }}</div>
                                <div class="text-sm text-gray-600">Generasi Pertama</div>
                            </div>
                        </div>
                    </div>
                @endif
            @endauth
            
        @else
            <!-- Empty State - FIXED AUTH -->
            <div class="text-center py-16">
                <div class="w-32 h-32 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-user-friends text-gray-400 text-4xl"></i>
                </div>
                <h3 class="text-2xl font-semibold text-gray-900 mb-4">Belum Ada Anggota Keluarga</h3>
                <p class="text-gray-600 mb-8 max-w-md mx-auto">
                    Keluarga ini belum memiliki anggota yang terdaftar. 
                    @auth('family')
                        @if($isAdmin)
                            Mulai tambahkan anggota keluarga Anda sekarang untuk membangun silsilah.
                        @else
                            Hubungi admin keluarga untuk menambahkan anggota.
                        @endif
                    @else
                        Login sebagai admin keluarga untuk menambahkan anggota.
                    @endauth
                </p>
                
                @auth('family')
                    @if($isAdmin)
                        <a href="{{ route('members.create') }}" 
                           class="bg-green-600 hover:bg-green-700 text-white py-4 px-8 rounded-xl transition font-medium shadow-lg transform hover:scale-105">
                            <i class="fas fa-user-plus mr-2"></i>
                            Tambah Anggota Pertama
                        </a>
                    @endif
                @else
                    <a href="{{ route('auth.login') }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-white py-4 px-8 rounded-xl transition font-medium shadow-lg transform hover:scale-105">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        Login untuk Mengelola
                    </a>
                @endauth
            </div>
        @endif
    </div>
</section>

<!-- Recent Activities Section -->
@if(isset($stats['recent_activities']) && $stats['recent_activities']->count() > 0)
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-3xl font-bold text-gray-900">Aktivitas Terbaru</h2>
                <a href="{{ route('activity.history', ['family_id' => $family->id]) }}" 
                   class="text-indigo-600 hover:text-indigo-800 font-medium">
                    Lihat Semua →
                </a>
            </div>
            
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                @foreach($stats['recent_activities'] as $activity)
                    <div class="flex items-start p-6 {{ !$loop->last ? 'border-b border-gray-100' : '' }} hover:bg-gray-50 transition">
                        <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                            <i class="fas fa-{{ $activity->subject_type === 'family' ? 'home' : ($activity->subject_type === 'member' ? 'user' : 'circle') }} text-indigo-600"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-gray-900 mb-1">{{ $activity->description }}</p>
                            <p class="text-sm text-gray-500">{{ $activity->created_at->diffForHumans() }}</p>
                        </div>
                        <div class="text-xs text-gray-400">
                            {{ $activity->created_at->format('d/m H:i') }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif

<!-- Family Tree CTA -->
@if($stats['total_members'] > 0)
    <section class="py-16 bg-gradient-to-r from-indigo-600 to-purple-600 text-white">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <div class="w-20 h-20 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-sitemap text-white text-2xl"></i>
            </div>
            <h2 class="text-3xl font-bold mb-4">Lihat Pohon Keluarga</h2>
            <p class="text-xl text-indigo-100 mb-8">
                Visualisasi lengkap hubungan keluarga dalam bentuk pohon interaktif yang mudah dipahami
            </p>
            <a href="{{ route('families.tree', $family) }}" 
               class="bg-white hover:bg-gray-100 text-indigo-600 font-bold py-4 px-8 rounded-xl transition duration-300 transform hover:scale-105 shadow-lg">
                <i class="fas fa-eye mr-2"></i>
                Lihat Pohon Keluarga
            </a>
        </div>
    </section>
@endif

{{-- Tombol Logout diletakkan di sini --}}
            @auth('family')
                <form action="{{ route('auth.logout') }}" method="POST" class="mt-8">
                    @csrf
                    <button type="submit"
                        class="w-full bg-red-500/20 hover:bg-red-500/30 text-white font-medium py-3 px-4 rounded-lg transition flex items-center justify-center">
                        <i class="fas fa-sign-out-alt mr-2"></i>
                        Logout
                    </button>
                </form>
            @endauth
        </div>
    </section>


<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6">
        <div class="text-center">
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">Konfirmasi Penghapusan</h3>
            <p class="text-gray-600 mb-6">
                Apakah Anda yakin ingin menghapus <strong id="memberName"></strong>? 
                Tindakan ini tidak dapat dibatalkan.
            </p>
            <div class="flex gap-4">
                <button onclick="closeDeleteModal()" 
                        class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-700 py-3 px-4 rounded-lg transition font-medium">
                    Batal
                </button>
                <form id="deleteForm" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="w-full bg-red-600 hover:bg-red-700 text-white py-3 px-4 rounded-lg transition font-medium">
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Delete confirmation
function confirmDelete(memberName, deleteUrl) {
    document.getElementById('memberName').textContent = memberName;
    document.getElementById('deleteForm').action = deleteUrl;
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});

// Auto-hide success/error messages
document.addEventListener('DOMContentLoaded', function() {
    const messages = document.querySelectorAll('.bg-green-50, .bg-red-50');
    messages.forEach(message => {
        setTimeout(() => {
            message.style.transition = 'opacity 0.5s ease-out';
            message.style.opacity = '0';
            setTimeout(() => {
                message.remove();
            }, 500);
        }, 5000);
    });
});
</script>
@endsection