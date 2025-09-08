@extends('layouts.app')

@section('title', 'Riwayat Perubahan Anggota - Bani Parno')

@section('content')
<section class="py-16">
    <div class="max-w-7xl mx-auto px-4">
        <h1 class="text-3xl font-bold mb-6">Riwayat Perubahan Informasi Anggota</h1>

        <!-- Filter & Sort -->
        <div class="flex justify-between items-center mb-4">
            <form method="GET" class="flex gap-2">
                <select name="type" class="border rounded p-2">
                    <option value="">Semua Tipe</option>
                    <option value="Member" {{ request('type') === 'Member' ? 'selected' : '' }}>Member</option>
                    <option value="Family" {{ request('type') === 'Family' ? 'selected' : '' }}>Family</option>
                </select>

                <select name="sort" class="border rounded p-2">
                    <option value="desc" {{ $sort === 'desc' ? 'selected' : '' }}>Terbaru</option>
                    <option value="asc" {{ $sort === 'asc' ? 'selected' : '' }}>Terlama</option>
                </select>

                <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Filter</button>
            </form>
        </div>

        <!-- Table -->
        <div class="bg-white shadow rounded-lg overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-100 text-gray-700">
                        <th class="p-3 border-b">Perubahan</th>
                        <th class="p-3 border-b">Tipe</th>
                        <th class="p-3 border-b">Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr class="hover:bg-gray-50">
                            <td class="p-3 border-b">{{ $log->description }}</td>
                            <td class="p-3 border-b">{{ $log->subject_type }}</td>
                            <td class="p-3 border-b">{{ $log->created_at->format('M d, Y H:i:s') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="p-3 text-center text-gray-500">Belum ada riwayat perubahan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $logs->links() }}
        </div>
    </div>
</section>
@endsection
