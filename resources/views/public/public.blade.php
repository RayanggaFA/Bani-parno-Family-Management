@extends('layouts.public')

@section('content')
<div class="text-center">
    <h1 class="text-4xl font-bold mb-4">Sistem Manajemen Keluarga</h1>
    <p class="text-xl text-gray-600 mb-8">Database Keluarga Bani Parno</p>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-lg mx-auto">
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-2xl font-bold text-blue-600">{{ $totalFamilies }}</h3>
            <p class="text-gray-600">Keluarga Besar</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-2xl font-bold text-green-600">{{ $totalMembers }}</h3>
            <p class="text-gray-600">Total Anggota</p>
        </div>
    </div>
</div>
@endsection