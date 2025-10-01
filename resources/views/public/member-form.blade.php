<!-- resources/views/public/member-form.blade.php -->
@extends('layouts.app')

@section('title', 'Tambah Anggota - ' . $family->name)

@section('content')
<!-- Page Header -->
<section class="bg-gradient-to-r from-purple-600 to-pink-600 text-white py-16">
    <div class="max-w-4xl mx-auto px-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-bold mb-4">Tambah Anggota Keluarga</h1>
                <p class="text-xl text-purple-100">{{ $family->name }}</p>
            </div>
            <div class="hidden md:block">
                <div class="w-20 h-20 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <i class="fas fa-user-plus text-white text-3xl"></i>
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

            <!-- Form Header -->
            <div class="text-center mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Data Anggota Keluarga</h2>
                <p class="text-gray-600">Isi informasi anggota keluarga dengan lengkap dan benar</p>
            </div>

            <!-- Member Form -->
            <form method="POST" action="{{ route('members.store') }}" enctype="multipart/form-data" class="space-y-8">
                @csrf

                <!-- Informasi Pribadi -->
                <div class="bg-blue-50 rounded-xl p-6">
                    <h3 class="text-lg font-semibold text-blue-900 mb-6 flex items-center">
                        <i class="fas fa-user mr-2"></i>Informasi Pribadi
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nama Lengkap -->
                        <div>
                            <label for="full_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="full_name" name="full_name" value="{{ old('full_name') }}" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                                   placeholder="Contoh: Ahmad Soekarno">
                        </div>

                        <!-- Nama Panggilan -->
                        <div>
                            <label for="nickname" class="block text-sm font-medium text-gray-700 mb-2">Nama Panggilan</label>
                            <input type="text" id="nickname" name="nickname" value="{{ old('nickname') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                                   placeholder="Contoh: Karno">
                        </div>

                        <!-- Foto Profil -->
                        <div>
                            <label for="profile_photo" class="block text-sm font-medium text-gray-700 mb-2">Foto Profil</label>
                            <input type="file" id="profile_photo" name="profile_photo" accept="image/*"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                            <p class="text-sm text-gray-500 mt-1">Format: JPG, PNG, GIF. Maksimal 2MB</p>
                        </div>

                        <!-- Tempat Lahir -->
                        <div>
                            <label for="birth_place" class="block text-sm font-medium text-gray-700 mb-2">Tempat Lahir</label>
                            <input type="text" id="birth_place" name="birth_place" value="{{ old('birth_place') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                                   placeholder="Contoh: Jakarta">
                        </div>

                        <!-- Tanggal Lahir -->
                        <div>
                            <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Lahir</label>
                            <input type="date" id="birth_date" name="birth_date" value="{{ old('birth_date') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                        </div>

                        <!-- Jenis Kelamin -->
                        <div>
                            <label for="gender" class="block text-sm font-medium text-gray-700 mb-2">
                                Jenis Kelamin <span class="text-red-500">*</span>
                            </label>
                            <select id="gender" name="gender" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="Laki-laki" {{ old('gender') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="Perempuan" {{ old('gender') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>

                        <!-- Status Pernikahan -->
                        <div>
                            <label for="marital_status" class="block text-sm font-medium text-gray-700 mb-2">
                                Status Pernikahan <span class="text-red-500">*</span>
                            </label>
                            <select id="marital_status" name="marital_status" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                                <option value="">Pilih Status Pernikahan</option>
                                <option value="single" {{ old('marital_status') == 'single' ? 'selected' : '' }}>Belum Menikah</option>
                                <option value="married" {{ old('marital_status') == 'married' ? 'selected' : '' }}>Menikah</option>
                                <option value="divorced" {{ old('marital_status') == 'divorced' ? 'selected' : '' }}>Cerai</option>
                                <option value="widowed" {{ old('marital_status') == 'widowed' ? 'selected' : '' }}>Janda/Duda</option>
                                <option value="prefer_not_to_answer" {{ old('marital_status') == 'prefer_not_to_answer' ? 'selected' : '' }}>
                                    Memilih untuk tidak menjawab
                                </option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Informasi Kontak -->
                <div class="bg-yellow-50 rounded-xl p-6">
                    <h3 class="text-lg font-semibold text-yellow-900 mb-6 flex items-center">
                        <i class="fas fa-phone mr-2"></i>Informasi Kontak
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Pekerjaan -->
                        <div>
                            <label for="occupation" class="block text-sm font-medium text-gray-700 mb-2">Pekerjaan</label>
                            <input type="text" id="occupation" name="occupation" value="{{ old('occupation') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                                   placeholder="Contoh: Pegawai Swasta">
                        </div>

                        <!-- Nomor Telepon -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon</label>
                            <input type="tel" id="phone" name="phone" value="{{ old('phone') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                                   placeholder="Contoh: 08123456789">
                        </div>
                    </div>
                </div>

                <!-- Informasi Alamat -->
                <div class="bg-red-50 rounded-xl p-6">
                    <h3 class="text-lg font-semibold text-red-900 mb-6 flex items-center">
                        <i class="fas fa-map-marker-alt mr-2"></i>Informasi Alamat
                    </h3>

                    <!-- Alamat KTP -->
                    <div class="mb-6">
                        <label for="ktp_address" class="block text-sm font-medium text-gray-700 mb-2">
                            Alamat KTP <span class="text-red-500">*</span>
                        </label>
                        <textarea id="ktp_address" name="ktp_address" rows="4" required
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                                  placeholder="Contoh: Jl. Merdeka No. 10, Kota Lama">{{ old('ktp_address') }}</textarea>
                    </div>

                    <!-- Alamat Saat Ini -->
                    <div>
                        <label for="current_address" class="block text-sm font-medium text-gray-700 mb-2">Alamat Saat Ini</label>
                        <textarea id="current_address" name="current_address" rows="4"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                                  placeholder="Alamat saat ini (jika berbeda dari KTP)">{{ old('current_address') }}</textarea>
                    </div>
                </div>

                <!-- Domisili -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="domicile_city" class="block text-sm font-medium text-gray-700 mb-2">
                            Kota Domisili <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="domicile_city" name="domicile_city" value="{{ old('domicile_city') }}" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                               placeholder="Contoh: Jakarta Selatan">
                    </div>

                    <div>
                        <label for="domicile_province" class="block text-sm font-medium text-gray-700 mb-2">
                            Provinsi Domisili <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="domicile_province" name="domicile_province" value="{{ old('domicile_province') }}" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                               placeholder="Contoh: DKI Jakarta">
                    </div>
                </div>

                <!-- Informasi Tambahan -->
                <div class="bg-gray-50 rounded-xl p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                        <i class="fas fa-info-circle mr-2"></i>Informasi Tambahan
                    </h3>
                    
                    <div class="grid grid-cols-1 gap-6">
                        <!-- Orang Tua -->
                        <div>
                            <label for="parent_id" class="block text-sm font-medium text-gray-700 mb-2">Orang Tua (Opsional)</label>
                            <select id="parent_id" name="parent_id"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                                <option value="">Pilih Orang Tua</option>
                                @if(isset($potentialParents))
                                    @foreach($potentialParents as $parent)
                                        <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>
                                            {{ $parent->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            <p class="text-sm text-gray-500 mt-1">Pilih jika anggota ini adalah anak dari salah satu anggota yang sudah ada</p>
                        </div>

                        <!-- Generasi & Catatan -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="generation" class="block text-sm font-medium text-gray-700 mb-2">
                                    Generasi <span class="text-red-500">*</span>
                                </label>
                                <select id="generation" name="generation" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                                    <option value="">Pilih Generasi</option>
                                    <option value="1" {{ old('generation') == '1' ? 'selected' : '' }}>Generasi 1 (Kakek/Nenek)</option>
                                    <option value="2" {{ old('generation') == '2' ? 'selected' : '' }}>Generasi 2 (Ayah/Ibu)</option>
                                    <option value="3" {{ old('generation') == '3' ? 'selected' : '' }}>Generasi 3 (Anak)</option>
                                    <option value="4" {{ old('generation') == '4' ? 'selected' : '' }}>Generasi 4 (Cucu)</option>
                                    <option value="5" {{ old('generation') == '5' ? 'selected' : '' }}>Generasi 5 (Cicit)</option>
                                </select>
                            </div>

                            <div>
                                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                                <textarea id="notes" name="notes" rows="3"
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                                          placeholder="Catatan tambahan tentang anggota keluarga">{{ old('notes') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex space-x-4 pt-6">
                    <button type="submit" 
                            class="flex-1 bg-purple-600 hover:bg-purple-700 text-white font-semibold py-4 px-6 rounded-lg transition duration-200">
                        <i class="fas fa-user-plus mr-2"></i>Tambah Anggota
                    </button>
                    <a href="{{ route('families.show', $family) }}" 
                       class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-semibold py-4 px-6 rounded-lg transition duration-200 text-center">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Photo preview
    const photoInput = document.getElementById('profile_photo');
    if (photoInput) {
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
                        <p class="text-sm text-gray-500 mt-1">Preview foto</p>
                    `;
                };
                reader.readAsDataURL(file);
            }
        });
    }
});
</script>
@endsection
