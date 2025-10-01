<!-- resources/views/public/members.blade.php - REPLACE EXISTING CONTENT -->
@extends('layouts.app')

@section('title', 'Daftar Anggota Keluarga - Bani Parno')

@section('content')
<!-- Main Content -->
<section class="py-4 md:py-8 bg-gray-50 min-h-screen">
    <div class="w-full max-w-7xl mx-auto px-2 sm:px-4 lg:px-6">
        <!-- Page Header -->
        <div class="bg-white rounded-lg shadow-sm p-4 md:p-6 mb-4 md:mb-6 mx-auto">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 text-center sm:text-left">
                <div class="flex-1">
                    <h2 class="text-xl md:text-2xl font-bold text-gray-900 mb-2">Daftar Anggota Keluarga</h2>
                    <p class="text-gray-600 text-sm md:text-base">Kelola informasi seluruh anggota keluarga</p>
                </div>
               
    @auth('family')  {{-- Tambahkan 'family' guard --}}
    <div class="flex justify-center sm:justify-end">
        <a href="{{ route('members.create') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 md:px-6 py-2 md:py-3 rounded-lg transition flex items-center justify-center shadow-md text-sm md:text-base w-full sm:w-auto max-w-xs">
           <i class="fas fa-user-plus mr-2"></i>Tambah Anggota
        </a>
    </div>
@endauth

            </div>
        </div>

        <!-- Search and Filters -->
        <div class="bg-white rounded-lg shadow-sm p-4 md:p-6 mb-4 md:mb-6 mx-auto">
            <form method="GET" class="space-y-4 max-w-6xl mx-auto">
                <!-- Search Bar -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4">
                    <div class="relative sm:col-span-2 lg:col-span-1">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Nama atau pekerjaan..." 
                               class="w-full pl-10 pr-4 py-2 md:py-3 text-sm md:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-center sm:text-left">
                    </div>
                    
                    <!-- Family Filter -->
                    <select name="family_id" class="border border-gray-300 rounded-lg px-3 md:px-4 py-2 md:py-3 text-sm md:text-base focus:ring-2 focus:ring-blue-500 focus:border-transparent text-center w-full">
                        <option value="">Semua Keluarga</option>
                        @foreach($families as $id => $name)
                            <option value="{{ $id }}" {{ request('family_id') == $id ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                    
                    <!-- Status Filter -->
                    <select name="status" class="border border-gray-300 rounded-lg px-3 md:px-4 py-2 md:py-3 text-sm md:text-base focus:ring-2 focus:ring-blue-500 focus:border-transparent text-center w-full">
                        <option value="">Semua Status</option>
                        <option value="Belum Menikah" {{ request('status') == 'Belum Menikah' ? 'selected' : '' }}>Belum Menikah</option>
                        <option value="Sudah Menikah" {{ request('status') == 'Sudah Menikah' ? 'selected' : '' }}>Sudah Menikah</option>
                        <option value="Janda/Duda" {{ request('status') == 'Janda/Duda' ? 'selected' : '' }}>Janda/Duda</option>
                    </select>
                    
                    <!-- Gender Filter -->
                    <select name="gender" class="border border-gray-300 rounded-lg px-3 md:px-4 py-2 md:py-3 text-sm md:text-base focus:ring-2 focus:ring-blue-500 focus:border-transparent text-center w-full">
                        <option value="">Semua</option>
                        <option value="Laki-laki" {{ request('gender') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ request('gender') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row sm:justify-center gap-3 items-center">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 md:px-6 py-2 rounded-lg transition text-sm md:text-base w-full sm:w-auto min-w-[120px] flex items-center justify-center">
                        <i class="fas fa-search mr-2"></i>Cari
                    </button>
                    @if(request()->hasAny(['search', 'family_id', 'status', 'gender']))
                    <a href="{{ route('members.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 md:px-6 py-2 rounded-lg transition text-center text-sm md:text-base w-full sm:w-auto min-w-[120px] flex items-center justify-center">
                        <i class="fas fa-times mr-2"></i>Reset Filter
                    </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Members Table/Cards -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden mx-auto max-w-full">
            @if($members->count() > 0)
                <!-- Desktop Table Header -->
                <div class="hidden lg:block bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <div class="grid grid-cols-12 gap-4 text-sm font-medium text-gray-500 uppercase tracking-wider">
                        <div class="col-span-3">ANGGOTA</div>
                        <div class="col-span-2">KELUARGA</div>
                        <div class="col-span-2">INFORMASI</div>
                        <div class="col-span-2">KONTAK</div>
                        <div class="col-span-2">STATUS</div>
                        <div class="col-span-1">AKSI</div>
                    </div>
                </div>

                <!-- Table Body / Cards Container -->
                <div class="divide-y divide-gray-200 lg:divide-y-0">
                    @foreach($members as $member)
                    <!-- Desktop Row -->
                    <div class="hidden lg:block px-6 py-4 hover:bg-gray-50 transition-colors table-row">
                        <div class="grid grid-cols-12 gap-4 items-center">
                            <!-- Member Info -->
                            <div class="col-span-3">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 bg-gray-300 rounded-full flex items-center justify-center mr-4 overflow-hidden flex-shrink-0">
                                        @if($member->profile_photo)
                                            <img src="{{ $member->profile_photo_url }}" 
                                                 alt="{{ $member->full_name }}" 
                                                 class="w-full h-full object-cover">
                                        @else
                                            <i class="fas fa-user text-gray-500 text-lg"></i>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <div class="font-semibold text-gray-900 truncate">{{ $member->full_name }}</div>
                                        <div class="text-sm text-gray-500 truncate">{{ $member->occupation ?: 'Mahasiswa' }}</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Family Info -->
                            <div class="col-span-2">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-blue-600 rounded-full mr-2 flex-shrink-0"></div>
                                    <div class="min-w-0">
                                        <div class="font-medium text-gray-900 truncate">{{ $member->family->name }}</div>
                                        <div class="text-sm text-gray-500 truncate">{{ $member->family->domicile }}</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Personal Info -->
                            <div class="col-span-2">
                                <div class="space-y-1">
                                    <div class="flex items-center text-sm">
                                        <i class="fas fa-{{ $member->gender == 'Laki-laki' ? 'mars' : 'venus' }} mr-2 {{ $member->gender == 'Laki-laki' ? 'text-blue-600' : 'text-pink-600' }} flex-shrink-0"></i>
                                        <span class="font-medium">{{ $member->gender }}</span>
                                    </div>
                                    <div class="text-sm text-gray-600">
                                        {{ $member->birth_date->age }} tahun
                                    </div>
                                    <div class="text-sm text-gray-600 flex items-center">
                                        <i class="fas fa-map-marker-alt mr-1 flex-shrink-0"></i>
                                        <span class="truncate">{{ $member->domicile_city }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Contact Info -->
                            <div class="col-span-2">
                                <div class="space-y-1">
                                    @if($member->phone_number)
                                    <div class="flex items-center text-sm text-gray-600">
                                        <i class="fas fa-phone mr-2 text-green-600 flex-shrink-0"></i>
                                        <span class="truncate">{{ $member->phone_number }}</span>
                                    </div>
                                    @endif
                                    @if($member->email)
                                    <div class="flex items-center text-sm text-gray-600">
                                        <i class="fas fa-envelope mr-2 text-blue-600 flex-shrink-0"></i>
                                        <span class="truncate">{{ $member->email }}</span>
                                    </div>
                                    @endif
                                    @if(!$member->phone_number && !$member->email)
                                    <div class="text-sm text-gray-400">-</div>
                                    @endif
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="col-span-2">
                                <div class="space-y-2">
                                    @php
                                        $statusColor = match($member->status) {
                                            'Belum Menikah' => 'bg-blue-100 text-blue-800',
                                            'Sudah Menikah' => 'bg-green-100 text-green-800',
                                            'Janda/Duda' => 'bg-yellow-100 text-yellow-800',
                                            default => 'bg-gray-100 text-gray-800'
                                        };
                                        $activeStatus = $member->created_at->diffInDays(now()) < 30 ? 'Aktif' : 'Tidak Aktif';
                                        $activeColor = $activeStatus == 'Aktif' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800';
                                    @endphp
                                    
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColor }}">
                                        {{ $member->status }}
                                    </span>
                                    <br>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $activeColor }}">
                                        {{ $activeStatus }}
                                    </span>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="col-span-1">
                                <a href="{{ route('members.show', $member) }}" 
                                   class="text-blue-600 hover:text-blue-700 transition p-2 rounded action-btn inline-flex items-center justify-center" 
                                   title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Mobile Card -->
                    <div class="lg:hidden p-4 member-card max-w-full">
                        <div class="space-y-4 w-full">
                            <!-- Header with photo and basic info -->
                            <div class="flex items-start space-x-4 w-full">
                                <div class="w-16 h-16 bg-gray-300 rounded-full flex items-center justify-center overflow-hidden flex-shrink-0">
                                    @if($member->profile_photo)
                                        <img src="{{ $member->profile_photo_url }}" 
                                             alt="{{ $member->full_name }}" 
                                             class="w-full h-full object-cover">
                                    @else
                                        <i class="fas fa-user text-gray-500 text-xl"></i>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0 text-center sm:text-left">
                                    <h3 class="font-semibold text-lg text-gray-900 break-words">{{ $member->full_name }}</h3>
                                    <p class="text-sm text-gray-500 break-words">{{ $member->occupation ?: 'Mahasiswa' }}</p>
                                    <div class="flex items-center justify-center sm:justify-start mt-1">
                                        <i class="fas fa-{{ $member->gender == 'Laki-laki' ? 'mars' : 'venus' }} mr-1 {{ $member->gender == 'Laki-laki' ? 'text-blue-600' : 'text-pink-600' }}"></i>
                                        <span class="text-sm text-gray-600">{{ $member->gender }}, {{ $member->birth_date->age }} tahun</span>
                                    </div>
                                </div>
                                <div class="flex-shrink-0">
                                    <a href="{{ route('members.show', $member) }}" 
                                       class="text-blue-600 hover:text-blue-700 transition p-2 rounded action-btn flex items-center justify-center" 
                                       title="Lihat Detail">
                                        <i class="fas fa-eye text-lg"></i>
                                    </a>
                                </div>
                            </div>

                            <!-- Details Grid -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm w-full">
                                <!-- Family Info -->
                                <div class="text-center sm:text-left">
                                    <div class="font-medium text-gray-900 mb-1">Keluarga</div>
                                    <div class="flex items-center justify-center sm:justify-start">
                                        <div class="w-2 h-2 bg-blue-600 rounded-full mr-2 flex-shrink-0"></div>
                                        <div class="min-w-0 text-center sm:text-left">
                                            <div class="font-medium break-words">{{ $member->family->name }}</div>
                                            <div class="text-gray-500 break-words">{{ $member->family->domicile }}</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Location -->
                                <div class="text-center sm:text-left">
                                    <div class="font-medium text-gray-900 mb-1">Lokasi</div>
                                    <div class="flex items-center justify-center sm:justify-start text-gray-600">
                                        <i class="fas fa-map-marker-alt mr-2 flex-shrink-0"></i>
                                        <span class="break-words">{{ $member->domicile_city }}</span>
                                    </div>
                                </div>

                                <!-- Contact -->
                                <div class="text-center sm:text-left">
                                    <div class="font-medium text-gray-900 mb-1">Kontak</div>
                                    <div class="space-y-1">
                                        @if($member->phone_number)
                                        <div class="flex items-center justify-center sm:justify-start text-gray-600">
                                            <i class="fas fa-phone mr-2 text-green-600 w-4 flex-shrink-0"></i>
                                            <span class="break-words">{{ $member->phone_number }}</span>
                                        </div>
                                        @endif
                                        @if($member->email)
                                        <div class="flex items-center justify-center sm:justify-start text-gray-600">
                                            <i class="fas fa-envelope mr-2 text-blue-600 w-4 flex-shrink-0"></i>
                                            <span class="break-all">{{ $member->email }}</span>
                                        </div>
                                        @endif
                                        @if(!$member->phone_number && !$member->email)
                                        <span class="text-gray-400">-</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Status -->
                                <div class="text-center sm:text-left">
                                    <div class="font-medium text-gray-900 mb-1">Status</div>
                                    <div class="space-y-1 flex flex-col items-center sm:items-start">
                                        @php
                                            $statusColor = match($member->status) {
                                                'Belum Menikah' => 'bg-blue-100 text-blue-800',
                                                'Sudah Menikah' => 'bg-green-100 text-green-800',
                                                'Janda/Duda' => 'bg-yellow-100 text-yellow-800',
                                                default => 'bg-gray-100 text-gray-800'
                                            };
                                            $activeStatus = $member->created_at->diffInDays(now()) < 30 ? 'Aktif' : 'Tidak Aktif';
                                            $activeColor = $activeStatus == 'Aktif' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800';
                                        @endphp
                                        
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $statusColor }}">
                                            {{ $member->status }}
                                        </span>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $activeColor }}">
                                            {{ $activeStatus }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="bg-white px-4 md:px-6 py-4 border-t border-gray-200">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="text-sm text-gray-700 text-center sm:text-left">
                            Menampilkan {{ $members->firstItem() ?? 0 }} sampai {{ $members->lastItem() ?? 0 }} 
                            dari {{ $members->total() }} anggota
                        </div>
                        <div class="flex items-center justify-center space-x-2">
                            {{ $members->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            @else
                <!-- Empty State -->
<div class="text-center py-12 md:py-16 px-4">
    <div class="w-20 md:w-24 h-20 md:h-24 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-4">
        <i class="fas fa-user-friends text-gray-400 text-2xl md:text-3xl"></i>
    </div>
    <h3 class="text-lg md:text-xl font-semibold text-gray-900 mb-2">Tidak ada anggota ditemukan</h3>
    <p class="text-gray-600 mb-4 text-sm md:text-base">Coba sesuaikan filter pencarian Anda atau tambah anggota baru.</p>
    
    <a href="{{ route('members.create') }}"
       class="bg-blue-600 hover:bg-blue-700 text-white px-4 md:px-6 py-2 rounded-lg transition text-sm md:text-base inline-flex items-center">
        <i class="fas fa-user-plus mr-2"></i>
        Tambah Anggota Pertama
    </a>
</div>

            @endif
        </div>
    </div>
</section>

<style>
/* Desktop table styles */
.table-row:hover {
    background-color: #f9fafb !important;
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    transition: all 0.2s ease;
}

/* Mobile card styles */
.member-card {
    transition: all 0.2s ease;
    border-radius: 0;
}

.member-card:hover {
    background-color: #f9fafb;
    transform: translateX(4px);
}

/* Action button styles */
.action-btn {
    transition: all 0.2s ease;
}

.action-btn:hover {
    background-color: rgba(59, 130, 246, 0.1);
    transform: scale(1.1);
}

/* Responsive breakpoints */
@media (max-width: 640px) {
    /* Stack search filters on mobile */
    .grid.grid-cols-1.sm\\:grid-cols-2.lg\\:grid-cols-4 {
        grid-template-columns: 1fr;
    }
    
    /* Full width buttons on mobile */
    .flex.flex-col.sm\\:flex-row button,
    .flex.flex-col.sm\\:flex-row a {
        width: 100%;
        justify-content: center;
    }
}

@media (max-width: 480px) {
    /* Extra small screens */
    .max-w-7xl {
        padding-left: 1rem;
        padding-right: 1rem;
    }
    
    .member-card {
        margin: 0 -1rem;
        border-radius: 0;
        border-left: none;
        border-right: none;
    }
    
    .member-card + .member-card {
        border-top: 1px solid #e5e7eb;
    }
}

/* Loading states */
.loading {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.5;
    }
}

/* Print styles */
@media print {
    .member-card:hover {
        transform: none;
        background-color: white;
    }
    
    .table-row:hover {
        transform: none;
        box-shadow: none;
        background-color: white;
    }
    
    .action-btn {
        display: none;
    }
}

/* High contrast mode support */
@media (prefers-contrast: high) {
    .bg-gray-50 {
        background-color: #f8f8f8;
    }
    
    .border-gray-200 {
        border-color: #999;
    }
}

/* Reduced motion support */
@media (prefers-reduced-motion: reduce) {
    .table-row,
    .member-card,
    .action-btn {
        transition: none;
    }
    
    .table-row:hover,
    .member-card:hover {
        transform: none;
    }
    
    .action-btn:hover {
        transform: none;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Enhanced hover effects for desktop
    const tableRows = document.querySelectorAll('.table-row');
    tableRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            if (window.innerWidth >= 1024) { // Only on desktop
                this.style.transform = 'translateY(-2px)';
                this.style.boxShadow = '0 4px 12px rgba(0, 0, 0, 0.1)';
            }
        });
        
        row.addEventListener('mouseleave', function() {
            if (window.innerWidth >= 1024) { // Only on desktop
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = 'none';
            }
        });
    });

    // Mobile card hover effects
    const memberCards = document.querySelectorAll('.member-card');
    memberCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            if (window.innerWidth < 1024) { // Only on mobile/tablet
                this.style.backgroundColor = '#f9fafb';
                this.style.transform = 'translateX(4px)';
            }
        });
        
        card.addEventListener('mouseleave', function() {
            if (window.innerWidth < 1024) { // Only on mobile/tablet
                this.style.backgroundColor = '';
                this.style.transform = 'translateX(0)';
            }
        });
    });

    // Smart search with debounce
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                if (this.value.length > 2 || this.value.length === 0) {
                    // Show loading state
                    this.classList.add('loading');
                    this.form.submit();
                }
            }, 800); // Longer delay for mobile users
        });
    }

    // Auto-submit filters
    const filterSelects = document.querySelectorAll('select[name^="family"], select[name^="status"], select[name^="gender"]');
    filterSelects.forEach(select => {
        select.addEventListener('change', function() {
            // Show loading state
            this.classList.add('loading');
            this.form.submit();
        });
    });

    // Touch-friendly interactions for mobile
    if ('ontouchstart' in window) {
        // Add touch class for mobile-specific styles
        document.body.classList.add('touch-device');
        
        // Prevent hover effects on touch devices
        const actionBtns = document.querySelectorAll('.action-btn');
        actionBtns.forEach(btn => {
            btn.addEventListener('touchstart', function() {
                this.style.backgroundColor = 'rgba(59, 130, 246, 0.1)';
            });
            
            btn.addEventListener('touchend', function() {
                setTimeout(() => {
                    this.style.backgroundColor = '';
                }, 150);
            });
        });
    }

    // Responsive image lazy loading
    const images = document.querySelectorAll('img[src]');
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src || img.src;
                    img.classList.remove('loading');
                    observer.unobserve(img);
                }
            });
        });

        images.forEach(img => {
            imageObserver.observe(img);
        });
    }

    // Handle window resize
    let resizeTimeout;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(() => {
            // Reset any transforms when switching between mobile/desktop
            const allTransformElements = document.querySelectorAll('[style*="transform"]');
            allTransformElements.forEach(el => {
                el.style.transform = '';
                el.style.boxShadow = '';
                el.style.backgroundColor = '';
            });
        }, 250);
    });
});

// Service worker for offline functionality (optional)
if ('serviceWorker' in navigator) {
    window.addEventListener('load', function() {
        navigator.serviceWorker.register('/sw.js').then(function(registration) {
            // Registration was successful
            console.log('ServiceWorker registration successful');
        }, function(err) {
            // Registration failed
            console.log('ServiceWorker registration failed: ', err);
        });
    });
}
</script>
@endsection