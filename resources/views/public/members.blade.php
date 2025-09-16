<!-- resources/views/public/members.blade.php - REPLACE EXISTING CONTENT -->
@extends('layouts.app')

@section('title', 'Daftar Anggota Keluarga - Bani Parno')

@section('content')
<!-- Main Content -->
<section class="py-8 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4">
        <!-- Page Header -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Daftar Anggota Keluarga</h2>
                    <p class="text-gray-600">Kelola informasi seluruh anggota keluarga</p>
                </div>
                <a href="{{ route('members.create') }}" 
   class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition flex items-center shadow-md">
   <i class="fas fa-user-plus mr-2"></i>Tambah Anggota
</a>
            </div>
        </div>

        <!-- Search and Filters -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <form method="GET" class="space-y-4">
                <!-- Search Bar -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Nama atau pekerjaan..." 
                               class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    
                    <!-- Family Filter -->
                    <select name="family_id" class="border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Semua Keluarga</option>
                        @foreach($families as $id => $name)
                            <option value="{{ $id }}" {{ request('family_id') == $id ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                    
                    <!-- Status Filter -->
                    <select name="status" class="border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Semua Status</option>
                        <option value="Belum Menikah" {{ request('status') == 'Belum Menikah' ? 'selected' : '' }}>Belum Menikah</option>
                        <option value="Sudah Menikah" {{ request('status') == 'Sudah Menikah' ? 'selected' : '' }}>Sudah Menikah</option>
                        <option value="Janda/Duda" {{ request('status') == 'Janda/Duda' ? 'selected' : '' }}>Janda/Duda</option>
                    </select>
                    
                    <!-- Gender Filter -->
                    <select name="gender" class="border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Semua</option>
                        <option value="Laki-laki" {{ request('gender') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ request('gender') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex justify-between items-center">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition">
                        <i class="fas fa-search mr-2"></i>Cari
                    </button>
                    @if(request()->hasAny(['search', 'family_id', 'status', 'gender']))
                    <a href="{{ route('members.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition">
                        <i class="fas fa-times mr-2"></i>Reset Filter
                    </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Members Table -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            @if($members->count() > 0)
                <!-- Table Header -->
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <div class="grid grid-cols-12 gap-4 text-sm font-medium text-gray-500 uppercase tracking-wider">
                        <div class="col-span-3">ANGGOTA</div>
                        <div class="col-span-2">KELUARGA</div>
                        <div class="col-span-2">INFORMASI</div>
                        <div class="col-span-2">KONTAK</div>
                        <div class="col-span-2">STATUS</div>
                        <div class="col-span-1">AKSI</div>
                    </div>
                </div>

                <!-- Table Body -->
                <div class="divide-y divide-gray-200">
                    @foreach($members as $member)
                    <div class="px-6 py-4 hover:bg-gray-50 transition-colors table-row">
                        <div class="grid grid-cols-12 gap-4 items-center">
                            <!-- Member Info -->
                            <div class="col-span-3">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 bg-gray-300 rounded-full flex items-center justify-center mr-4 overflow-hidden">
                                        @if($member->profile_photo)
                                            <img src="{{ $member->profile_photo_url }}" 
                                                 alt="{{ $member->full_name }}" 
                                                 class="w-full h-full object-cover">
                                        @else
                                            <i class="fas fa-user text-gray-500 text-lg"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-semibold text-gray-900">{{ $member->full_name }}</div>
                                        <div class="text-sm text-gray-500">{{ $member->occupation ?: 'Mahasiswa' }}</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Family Info -->
                            <div class="col-span-2">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-blue-600 rounded-full mr-2"></div>
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $member->family->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $member->family->domicile }}</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Personal Info -->
                            <div class="col-span-2">
                                <div class="space-y-1">
                                    <div class="flex items-center text-sm">
                                        <i class="fas fa-{{ $member->gender == 'Laki-laki' ? 'mars' : 'venus' }} mr-2 {{ $member->gender == 'Laki-laki' ? 'text-blue-600' : 'text-pink-600' }}"></i>
                                        <span class="font-medium">{{ $member->gender }}</span>
                                    </div>
                                    <div class="text-sm text-gray-600">
                                        {{ $member->birth_date->age }} tahun
                                    </div>
                                    <div class="text-sm text-gray-600">
                                        <i class="fas fa-map-marker-alt mr-1"></i>{{ $member->domicile_city }}
                                    </div>
                                </div>
                            </div>

                            <!-- Contact Info -->
                            <div class="col-span-2">
                                <div class="space-y-1">
                                    @if($member->phone_number)
                                    <div class="flex items-center text-sm text-gray-600">
                                        <i class="fas fa-phone mr-2 text-green-600"></i>
                                        <span>{{ $member->phone_number }}</span>
                                    </div>
                                    @endif
                                    @if($member->email)
                                    <div class="flex items-center text-sm text-gray-600">
                                        <i class="fas fa-envelope mr-2 text-blue-600"></i>
                                        <span>{{ $member->email }}</span>
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
                                <div class="flex space-x-2">
                                    <a href="{{ route('members.show', $member) }}" 
                                       class="text-blue-600 hover:text-blue-700 transition p-1 rounded action-btn" 
                                       title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button class="text-green-600 hover:text-green-700 transition p-1 rounded action-btn" 
                                            title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="text-red-600 hover:text-red-700 transition p-1 rounded action-btn" 
                                            title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="bg-white px-6 py-4 border-t border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-700">
                            Menampilkan {{ $members->firstItem() ?? 0 }} sampai {{ $members->lastItem() ?? 0 }} 
                            dari {{ $members->total() }} anggota
                        </div>
                        <div class="flex items-center space-x-2">
                            {{ $members->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-16">
                    <div class="w-24 h-24 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-user-friends text-gray-400 text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">Tidak ada anggota ditemukan</h3>
                    <p class="text-gray-600 mb-4">Coba sesuaikan filter pencarian Anda atau tambah anggota baru.</p>
                    <button class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition">
                        <i class="fas fa-user-plus mr-2"></i>Tambah Anggota Pertama
                    </button>
                </div>
            @endif
        </div>
    </div>
</section>

<style>
/* Custom styles for table */
.table-row:hover {
    background-color: #f9fafb !important;
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    transition: all 0.2s ease;
}

/* Action button hover effects */
.action-btn {
    transition: all 0.2s ease;
}

.action-btn:hover {
    background-color: rgba(59, 130, 246, 0.1);
    transform: scale(1.1);
}

/* Mobile responsive */
@media (max-width: 1024px) {
    .grid-cols-12 > div {
        min-width: 0;
    }
}

@media (max-width: 768px) {
    /* Hide table header on mobile */
    .bg-gray-50.border-b {
        display: none;
    }
    
    /* Convert table rows to cards on mobile */
    .table-row {
        margin-bottom: 1rem;
        border-radius: 0.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        padding: 1rem !important;
    }
    
    .table-row .grid-cols-12 {
        display: block;
        space-y: 0.5rem;
    }
    
    .table-row .col-span-3,
    .table-row .col-span-2,
    .table-row .col-span-1 {
        width: 100%;
        margin-bottom: 0.75rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .table-row .col-span-1:last-child {
        border-bottom: none;
        margin-bottom: 0;
    }
    
    /* Add labels on mobile */
    .table-row .col-span-2:before,
    .table-row .col-span-1:before {
        content: attr(data-label);
        font-weight: bold;
        color: #374151;
        display: block;
        margin-bottom: 0.25rem;
    }
}

/* Loading animation */
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
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Table row hover effects
    const tableRows = document.querySelectorAll('.table-row');
    tableRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
            this.style.boxShadow = '0 4px 12px rgba(0, 0, 0, 0.1)';
        });
        
        row.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = 'none';
        });
    });

    // Auto-submit search after user stops typing
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                if (this.value.length > 2 || this.value.length === 0) {
                    this.form.submit();
                }
            }, 500);
        });
    }

    // Filter change auto-submit
    const filterSelects = document.querySelectorAll('select[name^="family"], select[name^="status"], select[name^="gender"]');
    filterSelects.forEach(select => {
        select.addEventListener('change', function() {
            this.form.submit();
        });
    });
});
</script>
@endsection