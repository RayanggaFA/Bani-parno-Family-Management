<!-- resources/views/public/home.blade.php - CREATE NEW FILE -->
@extends('layouts.app')

@section('title', 'Beranda - Bani Parno Database Keluarga')

@section('content')
<!-- Hero Section -->
    <section class="relative gradient-bg min-h-screen flex items-center justify-center text-center text-white">
        <div class="absolute inset-0 bg-black/40"></div> <!-- Overlay gelap biar teks jelas -->
        
        <div class="relative z-10 max-w-4xl px-6">
            <h1 class="text-5xl md:text-6xl font-bold mb-6">
                Selamat Datang di Database Keluarga Bani Parno
            </h1>
            <p class="text-lg md:text-xl text-blue-100 mb-10">
                Sistem manajemen keluarga untuk menyimpan dan mengelola informasi keluarga besar dengan mudah, aman, dan terstruktur.
            </p>

            <div class="flex flex-col md:flex-row gap-4 justify-center">
                <a href="{{ route('families.index') }}" 
                   class="bg-orange-500 hover:bg-orange-600 text-white font-semibold py-4 px-8 rounded-lg 
                          transition duration-200 transform hover:scale-105">
                    <i class="fas fa-users mr-2"></i> Lihat Data Keluarga
                </a>
                <a href="{{ route('members.index') }}" 
                   class="bg-blue-400 hover:bg-blue-500 text-white font-semibold py-4 px-8 rounded-lg 
                          transition duration-200 transform hover:scale-105">
                    <i class="fas fa-user-friends mr-2"></i> Lihat Anggota
                </a>
            </div>
        </div>
    </section>

<!-- Statistics Cards -->
<section class="py-16 -mt-10">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex justify-center">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                
                <!-- Card 1 -->
                <div class="bg-white rounded-xl shadow-lg p-6 text-center card-hover">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-home text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-3xl font-bold text-gray-900 mb-2">{{ $totalFamilies }}</h3>
                    <p class="text-gray-600">Keluarga Besar</p>
                </div>

                <!-- Card 2 -->
                <div class="bg-white rounded-xl shadow-lg p-6 text-center card-hover">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-users text-green-600 text-2xl"></i>
                    </div>
                    <h3 class="text-3xl font-bold text-gray-900 mb-2">{{ $totalMembers }}</h3>
                    <p class="text-gray-600">Total Anggota</p>
                </div>

                <!-- Card 3 -->
                <div class="bg-white rounded-xl shadow-lg p-6 text-center card-hover">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-chart-line text-purple-600 text-2xl"></i>
                    </div>
                    <h3 class="text-3xl font-bold text-gray-900 mb-2">4</h3>
                    <p class="text-gray-600">Generasi</p>
                </div>

            </div>
        </div>
    </div>
</section>


<!-- Recent Members -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Anggota Terbaru</h2>
            <p class="text-gray-600">Anggota keluarga yang baru saja bergabung</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($recentMembers as $member)
            <div class="bg-white rounded-xl shadow-lg overflow-hidden card-hover">
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <img src="{{ $member->profile_photo_url }}" 
                             alt="{{ $member->full_name }}" 
                             class="w-16 h-16 rounded-full mr-4 object-cover">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">{{ $member->full_name }}</h3>
                            <p class="text-blue-600">{{ $member->family->name }}</p>
                        </div>
                    </div>
                    
                    <div class="space-y-2 text-sm text-gray-600">
                        <p><i class="fas fa-birthday-cake mr-2 text-blue-500"></i>{{ $member->birth_place }}, {{ $member->birth_date->format('d/m/Y') }}</p>
                        <p><i class="fas fa-venus-mars mr-2 text-green-500"></i>{{ $member->gender }}</p>
                        <p><i class="fas fa-map-marker-alt mr-2 text-purple-500"></i>{{ $member->domicile_city }}</p>
                    </div>
                    
                    <div class="mt-4">
                        <a href="{{ route('members.show', $member) }}" 
                           class="inline-block bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                            <i class="fas fa-eye mr-1"></i>Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="text-center mt-8">
            <a href="{{ route('members.index') }}" 
               class="inline-block bg-blue-600 text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition">
                <i class="fas fa-arrow-right mr-2"></i>Lihat Semua Anggota
            </a>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="gradient-bg text-white py-16">
    <div class="max-w-4xl mx-auto px-4 text-center">
        <h2 class="text-3xl font-bold mb-4">Mulai Jelajahi Database Keluarga</h2>
        <p class="text-xl text-blue-100 mb-8">Temukan informasi lengkap tentang keluarga besar Anda</p>
        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <a href="{{ route('families.index') }}" class="bg-white text-blue-600 px-6 py-3 rounded-lg font-semibold hover:bg-blue-50 transition">
                Lihat Semua Keluarga
            </a>
        </div>
    </div>
</section>
@endsection