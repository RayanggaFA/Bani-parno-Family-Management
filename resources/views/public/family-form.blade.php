{{-- resources/views/public/family-form.blade.php - FIXED COMPLETE VERSION --}}
@extends('layouts.app')

@section('title', 'Daftarkan Keluarga Baru - Bani Parno')

@section('content')
<!-- Page Header -->
<section class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-16">
    <div class="max-w-4xl mx-auto px-4 text-center">
        <h1 class="text-4xl font-bold mb-4">Daftarkan Keluarga Baru</h1>
        <p class="text-xl text-blue-100">Buat akun keluarga dan menjadi admin untuk mengelola data anggota keluarga Anda</p>
    </div>
</section>

<!-- Form Section -->
<section class="py-16 bg-gray-50">
    <div class="max-w-2xl mx-auto px-4">
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <!-- Form Header -->
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-home text-blue-600 text-2xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Informasi Keluarga</h2>
                <p class="text-gray-600">Isi data keluarga Anda dengan lengkap dan benar</p>
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

            <!-- Registration Form -->
            <form method="POST" action="{{ route('families.store') }}" class="space-y-6" id="registrationForm">
                @csrf

                <!-- Family Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-users mr-2 text-blue-500"></i>Nama Keluarga <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                           placeholder="Contoh: Keluarga Besar Soekarno">
                    <p class="text-sm text-gray-500 mt-1">Nama keluarga yang akan ditampilkan di sistem</p>
                </div>

                <!-- Username -->
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-user mr-2 text-green-500"></i>Username Admin <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="username" name="username" value="{{ old('username') }}" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                           placeholder="Contoh: soekarno_admin">
                    <p class="text-sm text-gray-500 mt-1">Username untuk login sebagai admin keluarga (harus unik)</p>
                </div>

                <!-- Password -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-lock mr-2 text-purple-500"></i>Password <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="password" id="password" name="password" required
                                   class="w-full px-4 py-3 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                   placeholder="Minimal 8 karakter">
                            <button type="button" id="togglePassword1" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <i class="fas fa-eye text-gray-400 hover:text-gray-600"></i>
                            </button>
                        </div>
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-lock mr-2 text-purple-500"></i>Konfirmasi Password <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="password" id="password_confirmation" name="password_confirmation" required
                                   class="w-full px-4 py-3 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                   placeholder="Ulangi password">
                            <button type="button" id="togglePassword2" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <i class="fas fa-eye text-gray-400 hover:text-gray-600"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Domicile -->
                <div>
                    <label for="domicile" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-map-marker-alt mr-2 text-red-500"></i>Domisili <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="domicile" name="domicile" value="{{ old('domicile') }}" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                           placeholder="Contoh: Jakarta Selatan">
                    <p class="text-sm text-gray-500 mt-1">Lokasi domisili keluarga</p>
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-file-alt mr-2 text-orange-500"></i>Deskripsi (Opsional)
                    </label>
                    <textarea id="description" name="description" rows="4"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                              placeholder="Ceritakan tentang keluarga Anda...">{{ old('description') }}</textarea>
                    <p class="text-sm text-gray-500 mt-1">Deskripsi singkat tentang keluarga (maksimal 1000 karakter)</p>
                </div>


    <!-- Submit Button -->
    <div class="flex space-x-4">
        <button type="submit" id="submitBtn"
                class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-4 px-6 rounded-lg transition duration-200 transform hover:scale-105 focus:ring-4 focus:ring-blue-200 disabled:opacity-50 disabled:cursor-not-allowed">
            <span id="submitText">
                <i class="fas fa-user-plus mr-2"></i>Daftar Keluarga
            </span>
            <span id="loadingText" class="hidden">
                <i class="fas fa-spinner fa-spin mr-2"></i>Memproses...
            </span>
        </button>
        <a href="{{ route('families.index') }}" 
           class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-semibold py-4 px-6 rounded-lg transition duration-200 text-center">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>
</form>

                <!-- Login Link -->
                <div class="text-center pt-4 border-t border-gray-200">
                    <p class="text-gray-600">
                        Sudah punya akun keluarga?
                        <a href="{{ route('auth.login') }}" class="text-blue-600 hover:text-blue-700 font-medium">
                            Login di sini
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</section>



@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('registrationForm');
    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');
    const loadingText = document.getElementById('loadingText');
    const termsCheckbox = document.getElementById('terms');
    const password = document.getElementById('password');
    const passwordConfirmation = document.getElementById('password_confirmation');

    // Form validation
    function validateForm() {
        const name = document.getElementById('name').value.trim();
        const username = document.getElementById('username').value.trim();
        const passwordValue = password.value.trim();
        const passwordConfirmationValue = passwordConfirmation.value.trim();
        const domicile = document.getElementById('domicile').value.trim();
        const termsChecked = termsCheckbox.checked;

        const isValid = name && username && passwordValue && passwordConfirmationValue && 
                       domicile && termsChecked && passwordValue === passwordConfirmationValue;

        submitBtn.disabled = !isValid;
        submitBtn.classList.toggle('opacity-50', !isValid);
        submitBtn.classList.toggle('cursor-not-allowed', !isValid);

        return isValid;
    }

    // Add event listeners for real-time validation
    ['name', 'username', 'domicile'].forEach(fieldId => {
        document.getElementById(fieldId).addEventListener('input', validateForm);
    });
    
    password.addEventListener('input', validateForm);
    passwordConfirmation.addEventListener('input', validateForm);
    termsCheckbox.addEventListener('change', validateForm);

    // Password confirmation validation
    passwordConfirmation.addEventListener('input', function() {
        if (password.value !== this.value) {
            this.setCustomValidity('Password tidak cocok');
            this.classList.add('border-red-500');
        } else {
            this.setCustomValidity('');
            this.classList.remove('border-red-500');
        }
    });

    // Password toggle functionality
    function setupPasswordToggle(toggleId, inputId) {
        const toggleBtn = document.getElementById(toggleId);
        const input = document.getElementById(inputId);
        
        if (toggleBtn && input) {
            toggleBtn.addEventListener('click', function() {
                const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                input.setAttribute('type', type);
                
                const icon = this.querySelector('i');
                icon.classList.toggle('fa-eye');
                icon.classList.toggle('fa-eye-slash');
            });
        }
    }

    setupPasswordToggle('togglePassword1', 'password');
    setupPasswordToggle('togglePassword2', 'password_confirmation');

    // Form submission
    form.addEventListener('submit', function(e) {
        if (!validateForm()) {
            e.preventDefault();
            return;
        }

        // Show loading state
        submitBtn.disabled = true;
        submitText.classList.add('hidden');
        loadingText.classList.remove('hidden');
    });

    // Initial validation
    validateForm();

    // Username validation (alphanumeric and underscore only)
    document.getElementById('username').addEventListener('input', function() {
        this.value = this.value.replace(/[^a-zA-Z0-9_]/g, '');
    });

    // Description character counter
    const description = document.getElementById('description');
    const maxLength = 1000;
    
    const counter = document.createElement('div');
    counter.className = 'text-sm text-gray-500 mt-1 text-right';
    counter.textContent = `0/${maxLength}`;
    description.parentNode.appendChild(counter);
    
    description.addEventListener('input', function() {
        const currentLength = this.value.length;
        counter.textContent = `${currentLength}/${maxLength}`;
        
        if (currentLength > maxLength) {
            this.value = this.value.substring(0, maxLength);
            counter.textContent = `${maxLength}/${maxLength}`;
        }
        
        counter.classList.toggle('text-red-500', currentLength > maxLength * 0.9);
        counter.classList.toggle('text-yellow-500', currentLength > maxLength * 0.8 && currentLength <= maxLength * 0.9);
    });

    // Auto-hide error messages
    const errorMessages = document.querySelectorAll('.bg-red-50');
    errorMessages.forEach(message => {
        setTimeout(() => {
            message.style.transition = 'opacity 0.5s ease-out';
            message.style.opacity = '0';
            setTimeout(() => {
                message.remove();
            }, 500);
        }, 10000);
    });
});
</script>
@endsection