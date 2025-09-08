<!-- resources/views/public/member-detail.blade.php - REPLACE EXISTING -->
@extends('layouts.app')

@section('title', $member->full_name . ' - Detail Anggota')

@section('content')
<!-- Back Navigation -->
<div class="bg-gray-100 py-4">
    <div class="max-w-6xl mx-auto px-4">
        <a href="{{ route('members.index') }}" class="flex items-center text-gray-600 hover:text-blue-600 transition">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali ke Daftar Anggota
        </a>
    </div>
</div>

<!-- Main Content -->
<section class="py-8 bg-gray-50 min-h-screen">
    <div class="max-w-6xl mx-auto px-4">
        <!-- Top Navigation -->
        <div class="flex justify-between items-center mb-6">
            <a href="{{ route('members.index') }}" class="text-gray-600 hover:text-blue-600 transition">
                <i class="fas fa-arrow-left mr-2"></i>Kembali ke {{ $member->family->name }}
            </a>
            <div class="flex space-x-4">
                <a href="{{ route('families.index') }}" class="flex items-center text-gray-600 hover:text-blue-600 transition">
                    <i class="fas fa-home mr-1"></i>Keluarga
                </a>
                <a href="{{ route('members.index') }}" class="flex items-center text-gray-600 hover:text-blue-600 transition">
                    <i class="fas fa-users mr-1"></i>Anggota
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Profile Card -->
            <div class="lg:col-span-1">
                <!-- Main Profile Card -->
                <div class="profile-card text-white rounded-3xl p-8 text-center shadow-xl mb-6 relative overflow-hidden">
                    <!-- Background Pattern -->
                    <div class="absolute top-0 left-0 w-full h-full opacity-10">
                        <div class="absolute top-4 right-4 w-20 h-20 border border-white rounded-full"></div>
                        <div class="absolute bottom-4 left-4 w-16 h-16 border border-white rounded-full"></div>
                    </div>

                    <!-- Profile Photo with Initial Overlay -->
                    <div class="relative mb-6 z-10">
                        <div class="w-32 h-32 mx-auto rounded-full border-4 border-white shadow-lg overflow-hidden relative">
                            <img src="{{ $member->profile_photo_url }}" 
                                 alt="{{ $member->full_name }}" 
                                 class="w-full h-full object-cover">
                        </div>
                    </div>

                    <!-- Name and Details -->
                    <h1 class="text-2xl font-bold mb-2 z-10 relative">{{ $member->full_name }}</h1>
                    <p class="text-blue-100 mb-1">{{ $member->family->name }}</p>
                    <p class="text-blue-200 text-sm mb-6">{{ $member->occupation ?: 'Tidak diketahui' }}</p>

                    <!-- Status Badge -->
                    <div class="inline-flex items-center bg-green-500 bg-opacity-80 px-4 py-2 rounded-full mb-6 z-10 relative backdrop-blur-sm">
                        <div class="w-2 h-2 bg-white rounded-full mr-2 animate-pulse"></div>
                        <span class="text-sm font-medium">Aktif</span>
                    </div>

                    <!-- Age and Gender Stats -->
                    <div class="grid grid-cols-2 gap-4 mb-6 z-10 relative">
                        <div class="bg-white bg-opacity-20 rounded-2xl p-4 backdrop-blur-sm border border-white border-opacity-20">
                            <div class="text-3xl font-bold mb-1">{{ $member->birth_date->age }}</div>
                            <div class="text-blue-100 text-sm">Tahun</div>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-2xl p-4 backdrop-blur-sm border border-white border-opacity-20">
                            <div class="text-2xl mb-1">
                                @if($member->gender == 'Laki-laki')
                                    <i class="fas fa-mars text-blue-200"></i>
                                @else
                                    <i class="fas fa-venus text-pink-200"></i>
                                @endif
                            </div>
                            <div class="text-blue-100 text-sm">{{ $member->gender == 'Laki-laki' ? 'Laki-laki' : 'Perempuan' }}</div>
                        </div>
                    </div>

                    <!-- Edit Profile Button -->
                    <button class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-8 py-3 rounded-full transition backdrop-blur-sm border border-white border-opacity-30 z-10 relative">
                        <i class="fas fa-edit mr-2"></i>Edit Profil
                    </button>
                </div>

                <!-- Contact Information Card -->
                <div class="bg-white rounded-2xl p-6 shadow-lg">
                    <h3 class="flex items-center text-lg font-bold mb-5 text-gray-900">
                        <i class="fas fa-address-card text-blue-600 mr-2"></i>Informasi Kontak
                    </h3>
                    
                    <div class="space-y-4">
                        @if($member->phone_number)
                        <div class="flex items-center p-3 bg-green-50 rounded-lg">
                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-phone text-green-600"></i>
                            </div>
                            <span class="text-gray-700 font-medium">{{ $member->phone_number }}</span>
                        </div>
                        @endif

                        @if($member->email)
                        <div class="flex items-center p-3 bg-red-50 rounded-lg">
                            <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-envelope text-red-600"></i>
                            </div>
                            <span class="text-gray-700 font-medium">{{ $member->email }}</span>
                        </div>
                        @endif

                        <div class="flex items-center p-3 bg-blue-50 rounded-lg">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-map-marker-alt text-blue-600"></i>
                            </div>
                            <span class="text-gray-700 font-medium">{{ $member->domicile_city }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Content Area -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Family Information Card -->
                <div class="bg-white rounded-2xl p-6 shadow-lg">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="flex items-center text-xl font-bold text-gray-900">
                            <i class="fas fa-users text-blue-600 mr-2"></i>Informasi Keluarga
                        </h2>
                        <a href="{{ route('families.show', $member->family) }}" 
                           class="text-blue-600 hover:text-blue-700 transition text-sm font-medium flex items-center">
                            Lihat Keluarga <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>

                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-5 border border-blue-100">
                        <div class="flex items-center">
                            <div class="w-4 h-4 bg-blue-600 rounded-full mr-4"></div>
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-900 text-lg">{{ $member->family->name }}</h3>
                                <p class="text-gray-600 mb-1">{{ $member->family->domicile }}</p>
                                <p class="text-blue-600 font-medium">
                                    <i class="fas fa-users mr-1"></i>{{ $member->family->members->count() }} Anggota
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Personal Details Card -->
                <div class="bg-white rounded-2xl p-6 shadow-lg">
                    <h2 class="flex items-center text-xl font-bold mb-6 text-gray-900">
                        <i class="fas fa-id-card text-green-600 mr-2"></i>Detail Personal
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Informasi Dasar -->
                        <div>
                            <h3 class="font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">Informasi Dasar</h3>
                            <div class="space-y-4">
                                <div class="flex justify-between py-2">
                                    <span class="text-gray-600">Tanggal Lahir:</span>
                                    <span class="font-semibold text-gray-900">{{ $member->birth_date->format('d M Y') }}</span>
                                </div>
                                <div class="flex justify-between py-2">
                                    <span class="text-gray-600">Tempat Lahir:</span>
                                    <span class="font-semibold text-gray-900">{{ $member->birth_place }}</span>
                                </div>
                                <div class="flex justify-between py-2">
                                    <span class="text-gray-600">Jenis Kelamin:</span>
                                    <span class="font-semibold text-gray-900">{{ $member->gender }}</span>
                                </div>
                                <div class="flex justify-between py-2">
                                    <span class="text-gray-600">Usia:</span>
                                    <span class="font-semibold text-gray-900">{{ $member->birth_date->age }} tahun</span>
                                </div>
                            </div>
                        </div>

                        <!-- Informasi Profesi -->
                        <div>
                            <h3 class="font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-200">Informasi Profesi</h3>
                            <div class="space-y-4">
                                <div class="flex justify-between py-2">
                                    <span class="text-gray-600">Pekerjaan:</span>
                                    <span class="font-semibold text-gray-900">{{ $member->occupation ?: 'Tidak diketahui' }}</span>
                                </div>
                                <div class="flex justify-between py-2">
                                    <span class="text-gray-600">Status:</span>
                                    <span class="font-semibold text-gray-900">{{ $member->status }}</span>
                                </div>
                                <div class="flex justify-between py-2">
                                    <span class="text-gray-600">Generasi:</span>
                                    <span class="font-semibold text-gray-900">{{ $member->generation }}</span>
                                </div>
                                <div class="flex justify-between py-2">
                                    <span class="text-gray-600">Domisili:</span>
                                    <span class="font-semibold text-gray-900">{{ $member->domicile_city }}, {{ $member->domicile_province }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Address Information -->
                <div class="bg-white rounded-2xl p-6 shadow-lg">
                    <h2 class="flex items-center text-xl font-bold mb-6 text-gray-900">
                        <i class="fas fa-map-marker-alt text-red-600 mr-2"></i>Informasi Alamat
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gray-50 rounded-xl p-5">
                            <div class="flex items-center mb-3">
                                <i class="fas fa-id-card text-purple-600 mr-2"></i>
                                <h3 class="font-semibold text-gray-800">Alamat KTP</h3>
                            </div>
                            <p class="text-gray-600">{{ $member->ktp_address }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-5">
                            <div class="flex items-center mb-3">
                                <i class="fas fa-home text-green-600 mr-2"></i>
                                <h3 class="font-semibold text-gray-800">Alamat Sekarang</h3>
                            </div>
                            <p class="text-gray-600">{{ $member->current_address }}</p>
                        </div>
                    </div>
                </div>

                <!-- Family Relations -->
                @if($member->parent || $siblings->count() > 0 || $member->children->count() > 0)
                <div class="bg-white rounded-2xl p-6 shadow-lg">
                    <h2 class="flex items-center text-xl font-bold mb-6 text-gray-900">
                        <i class="fas fa-sitemap text-purple-600 mr-2"></i>Hubungan Keluarga
                    </h2>

                    <!-- Parent -->
                    @if($member->parent)
                    <div class="mb-6">
                        <h3 class="font-semibold text-gray-800 mb-3 flex items-center">
                            <i class="fas fa-user-tie text-purple-600 mr-2"></i>Orang Tua
                        </h3>
                        <div class="flex items-center bg-purple-50 p-4 rounded-xl border border-purple-100">
                            <img src="{{ $member->parent->profile_photo_url }}" 
                                 alt="{{ $member->parent->full_name }}" 
                                 class="w-14 h-14 rounded-full object-cover mr-4 border-2 border-purple-200">
                            <div class="flex-1">
                                <a href="{{ route('members.show', $member->parent) }}" 
                                   class="font-semibold text-purple-700 hover:text-purple-800 transition block">
                                    {{ $member->parent->full_name }}
                                </a>
                                <p class="text-purple-600 text-sm">{{ $member->parent->occupation ?: 'Tidak diketahui' }}</p>
                                <p class="text-gray-500 text-xs">Generasi {{ $member->parent->generation }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Siblings -->
                    @if($siblings->count() > 0)
                    <div class="mb-6">
                        <h3 class="font-semibold text-gray-800 mb-3 flex items-center">
                            <i class="fas fa-user-friends text-blue-600 mr-2"></i>Saudara ({{ $siblings->count() }})
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach($siblings as $sibling)
                            <div class="flex items-center bg-blue-50 p-4 rounded-xl border border-blue-100">
                                <img src="{{ $sibling->profile_photo_url }}" 
                                     alt="{{ $sibling->full_name }}" 
                                     class="w-12 h-12 rounded-full object-cover mr-3 border-2 border-blue-200">
                                <div class="flex-1">
                                    <a href="{{ route('members.show', $sibling) }}" 
                                       class="font-medium text-blue-700 hover:text-blue-800 transition">
                                        {{ $sibling->full_name }}
                                    </a>
                                    <p class="text-blue-600 text-sm">{{ $sibling->gender }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Children -->
                    @if($member->children->count() > 0)
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-3 flex items-center">
                            <i class="fas fa-baby text-green-600 mr-2"></i>Anak ({{ $member->children->count() }})
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach($member->children as $child)
                            <div class="flex items-center bg-green-50 p-4 rounded-xl border border-green-100">
                                <img src="{{ $child->profile_photo_url }}" 
                                     alt="{{ $child->full_name }}" 
                                     class="w-12 h-12 rounded-full object-cover mr-3 border-2 border-green-200">
                                <div class="flex-1">
                                    <a href="{{ route('members.show', $child) }}" 
                                       class="font-medium text-green-700 hover:text-green-800 transition">
                                        {{ $child->full_name }}
                                    </a>
                                    <p class="text-green-600 text-sm">Generasi {{ $child->generation }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

<style>
.profile-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

/* Additional animations */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.card-hover {
    animation: fadeInUp 0.6s ease-out;
}

.card-hover:hover {
    transform: translateY(-2px);
    transition: all 0.3s ease;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .profile-card {
        margin-bottom: 1rem;
    }
    
    .grid-cols-1 {
        gap: 1rem;
    }
}
</style>
@endsection