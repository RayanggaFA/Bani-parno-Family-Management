<!-- resources/views/public/member-edit.blade.php - CREATE NEW FILE -->
@extends('layouts.app')

@section('title', 'Edit Anggota - ' . $member->full_name)

@section('content')
<!-- Page Header -->
<section class="bg-gradient-to-r from-teal-600 to-blue-600 text-white py-16">
    <div class="max-w-4xl mx-auto px-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-bold mb-4">Edit Data Anggota</h1>
                <p class="text-xl text-teal-100">{{ $member->full_name }}</p>
            </div>
            <div class="hidden md:block">
                <div class="w-20 h-20 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <i class="fas fa-user-edit text-white text-3xl"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Form Section -->
<section class="py-16 bg-gray-50">
    <div class="max-w-4xl mx-auto px-4">
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <!-- Back Button -->
            <div class="mb-6">
                <a href="{{ route('members.show', $member) }}" 
                   class="inline-flex items-center text-gray-600 hover:text-gray-900 transition">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali ke Detail Anggota
                </a>
            </div>

            <!-- Current Photo Display -->
            @if($member->profile_photo)
                <div class="text-center mb-8">
                    <div class="w-24 h-24 mx-auto rounded-full overflow-hidden border-4 border-teal-200">
                        <img src="{{ asset('storage/' . $member->profile_photo) }}" 
                             alt="{{ $member->full_name }}"
                             class="w-full h-full object-cover">
                    </div>
                    <p class="text-sm text-gray-500 mt-2">Foto saat ini</p>
                </div>
            @endif

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
            <form method="POST" action="{{ route('members.update', $member) }}" enctype="multipart/form-data" class="space-y-8">
                @csrf
                @method('PUT')

                <!-- Personal Information Section -->
                <div class="bg-blue-50 rounded-xl p-6">
                    <h3 class="text-lg font-semibold text-blue-900 mb-6 flex items-center">
                        <i class="fas fa-user mr-2"></i>Informasi Pribadi
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Full Name -->
                        <div>
                            <label for="full_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="full_name" name="full_name" value="{{ old('full_name', $member->full_name) }}" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent transition">
                        </div>

                        <!-- Profile Photo -->
                        <div>
                            <label for="profile_photo" class="block text-sm font-medium text-gray-700 mb-2">
                                Foto Profil Baru
                            </label>
                            <input type="file" id="profile_photo" name="profile_photo" accept="image/*"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent transition">
                            <p class="text-sm text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah foto</p>
                        </div>

                        <!-- Birth Place -->
                        <div>
                            <label for="birth_place" class="block text-sm font-medium text-gray-700 mb-2">
                                Tempat Lahir <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="birth_place" name="birth_place" value="{{ old('birth_place', $member->birth_place) }}" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent transition">
                        </div>

                        <!-- Birth Date -->
                        <div>
                            <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-2">
                                Tanggal Lahir <span class="text-red-500">*</span>
                            </label>
                            <input type="date" id="birth_date" name="birth_date" value="{{ old('birth_date', $member->birth_date) }}" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent transition">
                        </div>

                        <!-- Gender -->
                        <div>
                            <label for="gender" class="block text-sm font-medium text-gray-700 mb-2">
                                Jenis Kelamin <span class="text-red-500">*</span>
                            </label>
                            <select id="gender" name="gender" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent transition">
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="Laki-laki" {{ old('gender', $member->gender) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="Perempuan" {{ old('gender', $member->gender) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select id="status" name="status" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent transition">
                                <option value="">Pilih Status</option>
                                <option value="Belum Menikah" {{ old('status', $member->status) == 'Belum Menikah' ? 'selected' : '' }}>Belum Menikah</option>
                                <option value="Sudah Menikah" {{ old('status', $member->status) == 'Sudah Menikah' ? 'selected' : '' }}>Sudah Menikah</option>
                                <option value="Janda/Duda" {{ old('status', $member->status) == 'Janda/Duda' ? 'selected' : '' }}>Janda/Duda</option>
                                <option value="Memilih untuk tidak menjawab" {{ old('status', $member->status) == 'Memilih untuk tidak menjawab' ? 'selected' : '' }}>Memilih untuk tidak menjawab</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Family Position Section -->
                <div class="bg-green-50 rounded-xl p-6">
                    <h3 class="text-lg font-semibold text-green-900 mb-6 flex items-center">
                        <i class="fas fa-sitemap mr-2"></i>Posisi dalam Keluarga
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Generation -->
                        <div>
                            <label for="generation" class="block text-sm font-medium text-gray-700 mb-2">
                                Generasi <span class="text-red-500">*</span>
                            </label>
                            <select id="generation" name="generation" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent transition">
                                <option value="">Pilih Generasi</option>
                                <option value="1" {{ old('generation', $member->generation) == '1' ? 'selected' : '' }}>Generasi 1 (Kakek/Nenek)</option>
                                <option value="2" {{ old('generation', $member->generation) == '2' ? 'selected' : '' }}>Generasi 2 (Ayah/Ibu)</option>
                                <option value="3" {{ old('generation', $member->generation) == '3' ? 'selected' : '' }}>Generasi 3 (Anak)</option>
                                <option value="4" {{ old('generation', $member->generation) == '4' ? 'selected' : '' }}>Generasi 4 (Cucu)</option>
                                <option value="5" {{ old('generation', $member->generation) == '5' ? 'selected' : '' }}>Generasi 5 (Cicit)</option>
                            </select>
                        </div>

                        <!-- Parent -->
                        <div>
                            <label for="parent_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Orang Tua (Opsional)
                            </label>
                            <select id="parent_id" name="parent_id"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent transition">
                                <option value="">Pilih Orang Tua</option>
                                @foreach($potentialParents as $parent)
                                    <option value="{{ $parent->id }}" {{ old('parent_id', $member->parent_id) == $parent->id ? 'selected' : '' }}>
                                        {{ $parent->full_name }} (Gen {{ $parent->generation }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Contact Information Section -->
                <div class="bg-yellow-50 rounded-xl p-6">
                    <h3 class="text-lg font-semibold text-yellow-900 mb-6 flex items-center">
                        <i class="fas fa-phone mr-2"></i>Informasi Kontak
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Occupation -->
                        <div>
                            <label for="occupation" class="block text-sm font-medium text-gray-700 mb-2">
                                Pekerjaan
                            </label>
                            <input type="text" id="occupation" name="occupation" value="{{ old('occupation', $member->occupation) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent transition">
                        </div>

                        <!-- Phone Number -->
                        <div>
                            <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-2">
                                Nomor Telepon
                            </label>
                            <input type="tel" id="phone_number" name="phone_number" value="{{ old('phone_number', $member->phone_number) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent transition">
                        </div>

                        <!-- Email -->
                        <div class="md:col-span-2">
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email
                            </label>
                            <input type="email" id="email" name="email" value="{{ old('email', $member->email) }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent transition">
                        </div>
                    </div>
                </div>

                <!-- Address Information Section -->
                <div class="bg-red-50 rounded-xl p-6">
                    <h3 class="text-lg font-semibold text-red-900 mb-6 flex items-center">
                        <i class="fas fa-map-marker-alt mr-2"></i>Informasi Alamat
                    </h3>
                    
                    <div class="space-y-6">
                        <!-- Domicile -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="domicile_city" class="block text-sm font-medium text-gray-700 mb-2">
                                    Kota Domisili <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="domicile_city" name="domicile_city" value="{{ old('domicile_city', $member->domicile_city) }}" required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent transition">
                            </div>

                            <div>
                                <label for="domicile_province" class="block text-sm font-medium text-gray-700 mb-2">
                                    Provinsi Domisili <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="domicile_province" name="domicile_province" value="{{ old('domicile_province', $member->domicile_province) }}" required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent transition">
                            </div>
                        </div>

                        <!-- KTP Address -->
                        <div>
                            <label for="ktp_address" class="block text-sm font-medium text-gray-700 mb-2">
                                Alamat KTP <span class="text-red-500">*</span>
                            </label>
                            <textarea id="ktp_address" name="ktp_address" rows="3" required
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent transition">{{ old('ktp_address', $member->ktp_address) }}</textarea>
                        </div>

                        <!-- Current Address -->
                        <div>
                            <label for="current_address" class="block text-sm font-medium text-gray-700 mb-2">
                                Alamat Sekarang <span class="text-red-500">*</span>
                            </label>
                            <div class="flex items-center mb-2">
                                <input type="checkbox" id="same_address" class="mr-2">
                                <label for="same_address" class="text-sm text-gray-600">Sama dengan alamat KTP</label>
                            </div>
                            <textarea id="current_address" name="current_address" rows="3" required
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent transition">{{ old('current_address', $member->current_address) }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex space-x-4 pt-6">
                    <button type="submit" 
                            class="flex-1 bg-teal-600 hover:bg-teal-700 text-white font-semibold py-4 px-6 rounded-lg transition duration-200">
                        <i class="fas fa-save mr-2"></i>Simpan Perubahan
                    </button>
                    <a href="{{ route('members.show', $member) }}" 
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
                    <p class="text-red-700 mb-4">Hapus anggota ini dari keluarga. Tindakan ini tidak dapat dibatalkan!</p>
                    <button onclick="confirmDelete()" 
                            class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg transition">
                        <i class="fas fa-trash mr-2"></i>Hapus Anggota
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
                Apakah Anda yakin ingin menghapus <strong>{{ $member->full_name }}</strong> dari keluarga? 
                Tindakan ini tidak dapat dibatalkan.
            </p>
            <div class="flex space-x-4">
                <button onclick="closeDeleteModal()" 
                        class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-700 py-2 px-4 rounded-lg transition">
                    Batal
                </button>
                <form method="POST" action="{{ route('members.destroy', $member) }}" class="flex-1">
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
document.addEventListener('DOMContentLoaded', function() {
    // Same address checkbox functionality
    const sameAddressCheckbox = document.getElementById('same_address');
    const ktpAddressTextarea = document.getElementById('ktp_address');
    const currentAddressTextarea = document.getElementById('current_address');
    
    sameAddressCheckbox.addEventListener('change', function() {
        if (this.checked) {
            currentAddressTextarea.value = ktpAddressTextarea.value;
        }
    });
    
    ktpAddressTextarea.addEventListener('input', function() {
        if (sameAddressCheckbox.checked) {
            currentAddressTextarea.value = this.value;
        }
    });
    
    // Photo preview
    const photoInput = document.getElementById('profile_photo');
    photoInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                let preview = document.getElementById('photo-preview');
                if (!preview) {
                    preview = document.createElement('div');
                    preview.id = 'photo-preview';
                    preview.className = 'mt-2';
                    photoInput.parentNode.appendChild(preview);
                }
                preview.innerHTML = `
                    <img src="${e.target.result}" alt="Preview" class="w-20 h-20 object-cover rounded-lg border">
                    <p class="text-sm text-gray-500 mt-1">Preview foto baru</p>
                `;
            };
            reader.readAsDataURL(file);
        }
    });
});

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