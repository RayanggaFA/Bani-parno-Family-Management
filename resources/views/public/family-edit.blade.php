<!-- resources/views/public/family-edit.blade.php - CREATE NEW FILE -->
@extends('layouts.app')

@section('title', 'Edit Keluarga - ' . $family->name)

@section('content')
<!-- Page Header -->
<section class="bg-gradient-to-r from-green-600 to-teal-600 text-white py-16">
    <div class="max-w-4xl mx-auto px-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-bold mb-4">Edit Data Keluarga</h1>
                <p class="text-xl text-green-100">{{ $family->name }}</p>
            </div>
            <div class="hidden md:block">
                <div class="w-20 h-20 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <i class="fas fa-edit text-white text-3xl"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Form Section -->
<section class="py-16 bg-gray-50">
    <div class="max-w-2xl mx-auto px-4">
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <!-- Back Button -->
            <div class="mb-6">
                <a href="{{ route('families.show', $family) }}" 
                   class="inline-flex items-center text-gray-600 hover:text-gray-900 transition">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali ke Detail Keluarga
                </a>
            </div>

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                    <div class="flex items-center mb-2">
                        <i class="fas fa-exclamation-circle text-red-500 mr-2"></i>
                        <h4 class="font-medium text-red-800">Terjadi Kesalahan:</h4>
                    </div>
                    <ul class="list-disc list-inside text-red-700 text-sm space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Edit Form -->
            <form method="POST" action="{{ route('families.update', $family) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Family Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-users mr-2 text-blue-500"></i>Nama Keluarga <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name', $family->name) }}" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition">
                </div>

                <!-- Username -->
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-user mr-2 text-green-500"></i>Username Admin <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="username" name="username" value="{{ old('username', $family->username) }}" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition">
                    <p class="text-sm text-gray-500 mt-1">Username untuk login sebagai admin keluarga</p>
                </div>

                <!-- Domicile -->
                <div>
                    <label for="domicile" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-map-marker-alt mr-2 text-red-500"></i>Domisili <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="domicile" name="domicile" value="{{ old('domicile', $family->domicile) }}" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition">
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-file-alt mr-2 text-orange-500"></i>Deskripsi (Opsional)
                    </label>
                    <textarea id="description" name="description" rows="4"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition"
                              placeholder="Ceritakan tentang keluarga Anda...">{{ old('description', $family->description) }}</textarea>
                    <p class="text-sm text-gray-500 mt-1">Deskripsi singkat tentang keluarga (maksimal 1000 karakter)</p>
                </div>

                <!-- Action Buttons -->
                <div class="flex space-x-4 pt-6">
                    <button type="submit" 
                            class="flex-1 bg-green-600 hover:bg-green-700 text-white font-semibold py-4 px-6 rounded-lg transition duration-200">
                        <i class="fas fa-save mr-2"></i>Simpan Perubahan
                    </button>
                    <a href="{{ route('families.show', $family) }}" 
                       class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-semibold py-4 px-6 rounded-lg transition duration-200 text-center">
                        <i class="fas fa-times mr-2"></i>Batal
                    </a>
                </div>
            </form>

            <!-- Danger Zone -->
            <div class="mt-12 pt-8 border-t border-gray-200">
                <h3 class="text-lg font-semibold text-red-600 mb-4">
                    <i class="fas fa-exclamation-triangle mr-2"></i>Zona Bahaya
                </h3>
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <p class="text-red-700 mb-4">Hapus keluarga ini beserta seluruh anggota dan data terkait. Tindakan ini tidak dapat dibatalkan!</p>
                    <button onclick="confirmDelete()" 
                            class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg transition">
                        <i class="fas fa-trash mr-2"></i>Hapus Keluarga
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg p-6 max-w-md mx-4">
        <div class="text-center">
            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Konfirmasi Penghapusan</h3>
            <p class="text-gray-600 mb-6">
                Apakah Anda yakin ingin menghapus keluarga <strong>{{ $family->name }}</strong>? 
                Semua anggota dan data terkait akan ikut terhapus dan tidak dapat dipulihkan.
            </p>
            <div class="flex space-x-4">
                <button onclick="closeDeleteModal()" 
                        class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-700 py-2 px-4 rounded-lg transition">
                    Batal
                </button>
                <form method="POST" action="{{ route('families.destroy', $family) }}" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="w-full bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded-lg transition">
                        Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function confirmDelete() {
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDeleteModal();
    }
});
</script>
@endsection