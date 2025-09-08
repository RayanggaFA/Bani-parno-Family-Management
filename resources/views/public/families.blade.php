@extends('layouts.app')

@section('title', 'Daftar Keluarga - Bani Parno')

@section('content')
<!-- Page Header -->
<section class="bg-gradient-to-r from-green-600 to-blue-600 text-white py-16">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-bold mb-4">Daftar Keluarga</h1>
                <p class="text-xl text-green-100">Jelajahi keluarga-keluarga yang terdaftar dalam sistem</p>
            </div>
            <div class="hidden md:block">
                <div class="bg-white/20 backdrop-blur-sm rounded-2xl p-6">
                    <div class="text-center">
                        <div class="text-3xl font-bold">{{ $families->total() }}</div>
                        <div class="text-green-100">Keluarga Terdaftar</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Search & Filter -->
<section class="py-8 bg-white border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4">
        <div class="bg-gray-50 rounded-xl p-6">
            <form method="GET" class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Cari nama keluarga atau domisili..."
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                </div>
                <div class="flex gap-2">
                    <button type="submit" 
                            class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg transition flex items-center">
                        <i class="fas fa-search mr-2"></i>Cari
                    </button>
                    @if(request()->hasAny(['search']))
                        <a href="{{ route('families.index') }}" 
                           class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg transition flex items-center">
                            <i class="fas fa-times mr-2"></i>Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>
</section>

<!-- Families Grid -->
<section class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4">
        @if($families->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($families as $family)
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition duration-300 transform hover:-translate-y-1">
                        <!-- Header -->
                        <div class="bg-gradient-to-r from-green-500 to-blue-500 h-32 relative">
                            <div class="absolute inset-0 bg-black/20"></div>
                            <div class="absolute bottom-4 left-6 right-6">
                                <h3 class="text-xl font-bold text-white mb-1">{{ $family->name }}</h3>
                                <p class="text-green-100 text-sm flex items-center">
                                    <i class="fas fa-map-marker-alt mr-1"></i>
                                    {{ $family->domicile }}
                                </p>
                            </div>
                        </div>
                        
                        <!-- Content -->
                        <div class="p-6">
                            @if($family->description)
                                <p class="text-gray-600 mb-4 line-clamp-3">{{ Str::limit($family->description, 120) }}</p>
                            @endif
                            
                            <!-- Stats -->
                            <div class="flex items-center justify-between mb-6">
                                <div class="flex items-center text-gray-500">
                                    <i class="fas fa-users mr-2"></i>
                                    <span>{{ $family->members_count ?? 0 }} anggota</span>
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $family->created_at->diffForHumans() }}
                                </div>
                            </div>
                            
                            <!-- Actions -->
                            <div class="flex gap-3">
                                <a href="{{ route('families.show', $family) }}" 
                                   class="flex-1 bg-green-600 hover:bg-green-700 text-white text-center py-3 px-4 rounded-lg transition font-medium">
                                    <i class="fas fa-eye mr-2"></i>Lihat Detail
                                </a>
                                @if($family->members_count > 0)
                                    <a href="{{ route('families.tree', $family) }}" 
                                       class="bg-blue-600 hover:bg-blue-700 text-white py-3 px-4 rounded-lg transition">
                                        <i class="fas fa-sitemap"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="mt-12 flex justify-center">
                {{ $families->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-16">
                <div class="w-32 h-32 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-search text-gray-400 text-4xl"></i>
                </div>
                <h3 class="text-2xl font-semibold text-gray-900 mb-4">
                    @if(request('search'))
                        Tidak Ada Hasil Pencarian
                    @else
                        Belum Ada Keluarga Terdaftar
                    @endif
                </h3>
                <p class="text-gray-600 mb-8 max-w-md mx-auto">
                    @if(request('search'))
                        Tidak ditemukan keluarga dengan kata kunci "{{ request('search') }}". Coba dengan kata kunci lain.
                    @else
                        Belum ada keluarga yang terdaftar dalam sistem. Jadilah keluarga pertama yang bergabung!
                    @endif
                </p>
                
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    @if(request('search'))
                        <a href="{{ route('families.index') }}" 
                           class="bg-gray-600 hover:bg-gray-700 text-white py-3 px-6 rounded-lg transition">
                            <i class="fas fa-list mr-2"></i>Lihat Semua Keluarga
                        </a>
                    @endif

                    @guest('family')
                        <a href="{{ route('families.create') }}" 
                           class="bg-green-600 hover:bg-green-700 text-white py-3 px-6 rounded-lg transition">
                            <i class="fas fa-plus mr-2"></i>Daftarkan Keluarga
                        </a>
                    @endguest
                </div>
            </div>
        @endif
    </div>
</section>

<!-- CTA Section for Guests -->
@guest('family')
    <section class="py-16 bg-gradient-to-r from-green-600 to-blue-600 text-white">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold mb-4">Daftarkan Keluarga Anda</h2>
            <p class="text-xl text-green-100 mb-8">
                Bergabunglah dengan keluarga-keluarga lainnya dan mulai kelola data keluarga Anda secara digital
            </p>
            <a href="{{ route('families.create') }}" 
               class="bg-white hover:bg-gray-100 text-green-600 font-bold py-4 px-8 rounded-xl transition duration-300 transform hover:scale-105 shadow-lg">
                <i class="fas fa-rocket mr-2"></i>
                Daftar Sekarang
            </a>
        </div>
    </section>
@endguest
@endsection
