<!-- resources/views/public/family-tree.blade.php -->
@extends('layouts.app')

@section('title', 'Pohon Keluarga - ' . $family->name)

@section('content')
<section class="bg-gradient-to-r from-emerald-600 to-teal-600 text-white py-16">
    <div class="max-w-6xl mx-auto px-4">
        <div class="text-center">
            <h1 class="text-4xl font-bold mb-4">Pohon Keluarga</h1>
            <p class="text-xl text-emerald-100">{{ $family->name }}</p>
        </div>
    </div>
</section>

<section class="py-16 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4">
        <!-- Back Button -->
        <div class="mb-8">
            <a href="{{ route('families.show', $family) }}" 
               class="inline-flex items-center text-gray-600 hover:text-gray-900 transition">
                <i class="fas fa-arrow-left mr-2"></i>
                Kembali ke Detail Keluarga
            </a>
        </div>

        <!-- Tree Container -->
        <div class="bg-white rounded-xl shadow-lg p-8 overflow-x-auto">
            <div class="family-tree min-w-max">
                @foreach($generationData as $generation => $members)
                    <div class="generation-level mb-12">
                        <h3 class="text-lg font-semibold text-gray-900 mb-6 text-center">
                            Generasi {{ $generation }}
                            @switch($generation)
                                @case(1) <span class="text-sm text-gray-500">(Kakek/Nenek)</span> @break
                                @case(2) <span class="text-sm text-gray-500">(Ayah/Ibu)</span> @break
                                @case(3) <span class="text-sm text-gray-500">(Anak)</span> @break
                                @case(4) <span class="text-sm text-gray-500">(Cucu)</span> @break
                                @case(5) <span class="text-sm text-gray-500">(Cicit)</span> @break
                            @endswitch
                        </h3>
                        
                        <div class="flex flex-wrap justify-center gap-6">
                            @foreach($members as $member)
                                <div class="member-card relative">
                                    <!-- Connection Lines -->
                                    @if($member->children->count() > 0 && $generation < 5)
                                        <div class="connection-line absolute top-full left-1/2 transform -translate-x-1/2 w-px h-6 bg-gray-300"></div>
                                    @endif
                                    
                                    <!-- Member Card -->
                                    <div class="bg-white border-2 border-gray-200 rounded-lg p-4 text-center shadow-md hover:shadow-lg transition min-w-48">
                                        <!-- Profile Photo -->
                                        <div class="w-16 h-16 mx-auto mb-3">
                                            @if($member->profile_photo)
                                                <img src="{{ asset('storage/' . $member->profile_photo) }}" 
                                                     alt="{{ $member->full_name }}"
                                                     class="w-full h-full object-cover rounded-full border-2 border-gray-300">
                                            @else
                                                <div class="w-full h-full bg-gray-200 rounded-full flex items-center justify-center">
                                                    <i class="fas fa-user text-gray-400 text-xl"></i>
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <!-- Member Info -->
                                        <h4 class="font-semibold text-gray-900 mb-1">{{ $member->full_name }}</h4>
                                        <p class="text-sm text-gray-600 mb-2">
                                            @if($member->birth_date)
                                                {{ $member->birth_date->age }} tahun
                                            @else
                                                Usia tidak diketahui
                                            @endif
                                        </p>
                                        
                                        <!-- Gender & Status Icons -->
                                        <div class="flex justify-center space-x-2 mb-3">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs {{ $member->gender === 'Laki-laki' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' }}">
                                                <i class="fas fa-{{ $member->gender === 'Laki-laki' ? 'mars' : 'venus' }} mr-1"></i>
                                                {{ $member->gender === 'Laki-laki' ? 'L' : 'P' }}
                                            </span>
                                            
                                            @if($member->status === 'Sudah Menikah')
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-green-100 text-green-800">
                                                    <i class="fas fa-ring mr-1"></i>
                                                    Menikah
                                                </span>
                                            @endif
                                        </div>
                                        
                                        <!-- Action Button -->
                                        <a href="{{ route('members.show', $member) }}" 
                                           class="inline-flex items-center px-3 py-1 bg-emerald-600 hover:bg-emerald-700 text-white text-sm rounded-full transition">
                                            <i class="fas fa-eye mr-1"></i>
                                            Detail
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Family Statistics -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white rounded-lg shadow-md p-6 text-center">
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-users text-blue-600 text-xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900">{{ $stats['total_members'] }}</h3>
                <p class="text-gray-600">Total Anggota</p>
            </div>
            
            <div class="bg-white rounded-lg shadow-md p-6 text-center">
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-layer-group text-green-600 text-xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900">{{ count($generationData) }}</h3>
                <p class="text-gray-600">Generasi</p>
            </div>
            
            <div class="bg-white rounded-lg shadow-md p-6 text-center">
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-mars text-purple-600 text-xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900">{{ $stats['male_count'] }}</h3>
                <p class="text-gray-600">Laki-laki</p>
            </div>
            
            <div class="bg-white rounded-lg shadow-md p-6 text-center">
                <div class="w-12 h-12 bg-pink-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-venus text-pink-600 text-xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900">{{ $stats['female_count'] }}</h3>
                <p class="text-gray-600">Perempuan</p>
            </div>
        </div>
    </div>
</section>

<style>
.family-tree {
    font-family: 'Inter', sans-serif;
}

.member-card {
    position: relative;
}

.connection-line {
    z-index: 1;
}

.member-card::before {
    content: '';
    position: absolute;
    top: -24px;
    left: 50%;
    transform: translateX(-50%);
    width: 1px;
    height: 24px;
    background-color: #d1d5db;
}

.generation-level:first-child .member-card::before {
    display: none;
}
</style>
@endsection
