@extends('layouts.app')

@section('title', $family->name . ' - Detail Keluarga')

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
                    </div>
                </div>
                
                @if($family->description)
                    <p class="text-lg text-indigo-100 leading-relaxed mb-6">{{ $family->description }}</p>
                @endif
                
                <!-- Family Stats -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold">{{ $family->members_count ?? 0 }}</div>
                        <div class="text-indigo-100 text-sm">Anggota</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold">{{ $family->members()->distinct('generation')->count() }}</div>
                        <div class="text-indigo-100 text-sm">Generasi</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold">{{ $family->created_at->format('Y') }}</div>
                        <div class="text-indigo-100 text-sm">Bergabung</div>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold">{{ $family->activityLogs()->count() }}</div>
                        <div class="text-indigo-100 text-sm">Aktivitas</div>
                    </div>
                </div>
            </div>
            
            <!-- Admin Actions -->
            @auth('family')
                @if(Auth::guard('family')->id() === $family->id)
                    <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 border border-white/20 min-w-80">
                        <h3 class="text-xl font-bold text-white mb-4 flex items-center">
                            <i class="fas fa-user-shield mr-2"></i>
                            Panel Admin
                        </h3>
                        <div class="space-y-3">
                            <a href="{{ route('families.edit', $family) }}" 
                               class="w-full bg-white/20 hover:bg-white/30 text-white font-medium py-3 px-4 rounded-lg transition flex items-center justify-center">
                                <i class="fas fa-edit mr-2"></i>
                                Edit Keluarga
                            </a>
                            <a href="{{ route('members.create') }}" 
                               class="w-full bg-green-500/20 hover:bg-green-500/30 text-white font-medium py-3 px-4 rounded-lg transition flex items-center justify-center">
                                <i class="fas fa-user-plus mr-2"></i>
                                Tambah Anggota
                            </a>
                            @if($family->members_count > 0)
                                <a href="{{ route('families.tree', $family) }}" 
                                   class="w-full bg-purple-500/20 hover:bg-purple-500/30 text-white font-medium py-3 px-4 rounded-lg transition flex items-center justify-center">
                                    <i class="fas fa-sitemap mr-2"></i>
                                    Pohon Keluarga
                                </a>
                            @endif
                        </div>
                    </div>
                @endif
            @endauth
        </div>
    </div>
</section>

<!-- Members Section -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex items-center justify-between mb-12">
            <div>
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Anggota Keluarga</h2>
                <p class="text-gray-600">Daftar semua anggota dalam keluarga {{ $family->name }}</p>
            </div>
            
            @auth('family')
                @if(Auth::guard('family')->id() === $family->id)
                    <a href="{{ route('members.create') }}" 
                       class="bg-green-600 hover:bg-green-700 text-white font-medium py-3 px-6 rounded-lg transition flex items-center">
                        <i class="fas fa-user-plus mr-2"></i>
                        Tambah Anggota
                    </a>
                @endif
            @endauth
        </div>

        @if($family->members_count > 0)
            <!-- Generation Filter -->
            <div class="mb-8">
                <div class="flex flex-wrap gap-2" id="generationFilter">
                    <button onclick="filterByGeneration('all')" 
                            class="filter-btn active bg-indigo-600 text-white px-4 py-2 rounded-lg font-medium transition">
                        Semua Generasi
                    </button>
                    @foreach($family->members()->select('generation')->distinct()->orderBy('generation')->pluck('generation') as $gen)
                        <button onclick="filterByGeneration({{ $gen }})" 
                                class="filter-btn bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg font-medium transition">
                            Generasi {{ $gen }}
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Members Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="membersGrid">
                @foreach($family->members()->with('parent')->orderBy('generation')->orderBy('full_name')->get() as $member)
                    <div class="member-card bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition duration-300 transform hover:-translate-y-1" 
                         data-generation="{{ $member->generation }}">
                        <!-- Member Photo -->
                        <div class="relative">
                            @if($member->profile_photo)
                                <img src="{{ asset('storage/' . $member->profile_photo) }}" 
                                     alt="{{ $member->full_name }}"
                                     class="w-full h-48 object-cover">
                            @else
                                <div class="w-full h-48 bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center">
                                    <i class="fas fa-user text-gray-500 text-4xl"></i>
                                </div>
                            @endif
                            
                            <!-- Generation Badge -->
                            <div class="absolute top-3 right-3 bg-indigo-600 text-white text-xs font-bold px-2 py-1 rounded-full">
                                Gen {{ $member->generation }}
                            </div>
                            
                            <!-- Gender Icon -->
                            <div class="absolute top-3 left-3 w-8 h-8 {{ $member->gender === 'Laki-laki' ? 'bg-blue-500' : 'bg-pink-500' }} rounded-full flex items-center justify-center">
                                <i class="fas fa-{{ $member->gender === 'Laki-laki' ? 'mars' : 'venus' }} text-white text-sm"></i>
                            </div>
                        </div>
                        
                        <!-- Member Info -->
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $member->full_name }}</h3>
                            
                            <div class="space-y-2 text-sm text-gray-600 mb-4">
                                <p class="flex items-center">
                                    <i class="fas fa-birthday-cake mr-2 w-4"></i>
                                    {{ $member->birth_place }}, {{ \Carbon\Carbon::parse($member->birth_date)->format('d M Y') }}
                                </p>
                                
                                @if($member->occupation)
                                    <p class="flex items-center">
                                        <i class="fas fa-briefcase mr-2 w-4"></i>
                                        {{ $member->occupation }}
                                    </p>
                                @endif
                                
                                <p class="flex items-center">
                                    <i class="fas fa-map-marker-alt mr-2 w-4"></i>
                                    {{ $member->domicile_city }}, {{ $member->domicile_province }}
                                </p>
                                
                                @if($member->parent)
                                    <p class="flex items-center text-blue-600">
                                        <i class="fas fa-link mr-2 w-4"></i>
                                        Anak dari {{ $member->parent->full_name }}
                                    </p>
                                @endif
                            </div>
                            
                            <!-- Status Badge -->
                            <div class="mb-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                    {{ $member->status === 'Sudah Menikah' ? 'bg-green-100 text-green-800' : 
                                       ($member->status === 'Belum Menikah' ? 'bg-blue-100 text-blue-800' : 
                                       ($member->status === 'Janda/Duda' ? 'bg-orange-100 text-orange-800' : 'bg-gray-100 text-gray-800')) }}">
                                    {{ $member->status }}
                                </span>
                            </div>
                            <!-- Actions -->
                            <div class="flex gap-2">
                                <a href="{{ route('members.show', $member) }}" 
                                   class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white text-center py-2 px-3 rounded-lg transition text-sm font-medium">
                                    <i class="fas fa-eye mr-1"></i>Detail
                                </a>
                                
                                @auth('family')
                                    @if(Auth::guard('family')->id() === $family->id)
                                        <a href="{{ route('members.edit', $member) }}" 
                                           class="bg-green-600 hover:bg-green-700 text-white py-2 px-3 rounded-lg transition">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @endif
                                @endauth
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-16">
                <div class="w-32 h-32 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-user-friends text-gray-400 text-4xl"></i>
                </div>
                <h3 class="text-2xl font-semibold text-gray-900 mb-4">Belum Ada Anggota</h3>
                <p class="text-gray-600 mb-8 max-w-md mx-auto">
                    Keluarga ini belum memiliki anggota yang terdaftar. 
                    @auth('family')
                        @if(Auth::guard('family')->id() === $family->id)
                            Mulai tambahkan anggota keluarga Anda sekarang.
                        @endif
                    @endauth
                </p>
                
                @auth('family')
                    @if(Auth::guard('family')->id() === $family->id)
                        <a href="{{ route('members.create') }}" 
                           class="bg-green-600 hover:bg-green-700 text-white py-3 px-6 rounded-lg transition font-medium">
                            <i class="fas fa-user-plus mr-2"></i>
                            Tambah Anggota Pertama
                        </a>
                    @endif
                @endauth
            </div>
        @endif
    </div>
</section>

<!-- Recent Activities Section -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-3xl font-bold text-gray-900">Aktivitas Terbaru</h2>
            <a href="{{ route('activity.history', ['family_id' => $family->id]) }}" 
               class="text-indigo-600 hover:text-indigo-800 font-medium">
                Lihat Semua →
            </a>
        </div>
        
        @php
            $recentActivities = $family->activityLogs()->latest()->take(5)->get();
        @endphp
        
        @if($recentActivities->count() > 0)
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                @foreach($recentActivities as $activity)
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
        @else
            <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-history text-gray-400 text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Belum Ada Aktivitas</h3>
                <p class="text-gray-600">Aktivitas keluarga akan muncul di sini</p>
            </div>
        @endif
    </div>
</section>

<!-- Family Tree CTA -->
@if($family->members_count > 0)
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

<script>
// Family detail page interactions
function filterByGeneration(generation) {
    const cards = document.querySelectorAll('.member-card');
    const buttons = document.querySelectorAll('.filter-btn');
    
    // Update button states
    buttons.forEach(btn => {
        btn.classList.remove('active', 'bg-indigo-600', 'text-white');
        btn.classList.add('bg-gray-200', 'text-gray-700');
    });
    
    // Activate current button
    event.target.classList.add('active', 'bg-indigo-600', 'text-white');
    event.target.classList.remove('bg-gray-200', 'text-gray-700');
    
    // Filter cards with animation
    cards.forEach((card, index) => {
        if (generation === 'all' || card.dataset.generation == generation) {
            card.style.display = 'block';
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                card.style.transition = 'all 0.3s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 50);
        } else {
            card.style.transition = 'all 0.3s ease';
            card.style.opacity = '0';
            card.style.transform = 'translateY(-20px)';
            
            setTimeout(() => {
                card.style.display = 'none';
            }, 300);
        }
    });
}
</script>
@endsection