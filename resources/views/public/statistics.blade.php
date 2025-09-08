@extends('layouts.app')

@section('title', 'Statistik Keluarga - Bani Parno')

@section('content')
<section class="py-16">
    <div class="max-w-7xl mx-auto px-4">
        <h1 class="text-3xl font-bold mb-6">Statistik Keluarga</h1>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white shadow rounded-lg p-6 text-center">
                <h2 class="text-xl font-semibold">Total Keluarga</h2>
                <p class="mt-2 text-2xl text-blue-600 font-bold">{{ $stats['total_families'] ?? 0 }}</p>
            </div>
            <div class="bg-white shadow rounded-lg p-6 text-center">
                <h2 class="text-xl font-semibold">Total Anggota</h2>
                <p class="mt-2 text-2xl text-green-600 font-bold">{{ $stats['total_members'] ?? 0 }}</p>
            </div>
            <div class="bg-white shadow rounded-lg p-6 text-center">
                <h2 class="text-xl font-semibold">Kota Terdaftar</h2>
                <p class="mt-2 text-2xl text-purple-600 font-bold">{{ $stats['total_cities'] ?? 0 }}</p>
            </div>
        </div>
    </div>
</section>
@endsection
