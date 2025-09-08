<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin Keluarga - Bani Parno</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100 min-h-screen">
    <!-- Page Header -->
    <section class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-16">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <h1 class="text-4xl font-bold mb-4">Login Admin Keluarga</h1>
            <p class="text-xl text-indigo-100">Masuk untuk mengelola data keluarga dan anggota Anda</p>
        </div>
    </section>

    <!-- Login Form Section -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-md mx-auto px-4">
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <!-- Form Header -->
                <div class="text-center mb-8">
                    <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-sign-in-alt text-indigo-600 text-2xl"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Masuk ke Akun</h2>
                    <p class="text-gray-600">Login dengan username dan password admin keluarga</p>
                </div>

                <!-- Error Messages -->
                <div id="errorMessages" class="hidden bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                    <div class="flex items-center mb-2">
                        <i class="fas fa-exclamation-circle text-red-500 mr-2"></i>
                        <h4 class="font-medium text-red-800">Login Gagal:</h4>
                    </div>
                    <ul id="errorList" class="list-disc list-inside text-red-700 text-sm space-y-1">
                        <!-- Error messages will be inserted here -->
                    </ul>
                </div>

                <!-- Success Message -->
                <div id="successMessage" class="hidden bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        <span class="text-green-800 font-medium">Login berhasil! Mengalihkan...</span>
                    </div>
                </div>

                <!-- Login Form -->
                <form id="loginForm" class="space-y-6">
                    <!-- Username -->
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-user mr-2 text-indigo-500"></i>Username
                        </label>
                        <div class="relative">
                            <input type="text" id="username" name="username" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition pr-10"
                                   placeholder="Masukkan username admin keluarga">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <i class="fas fa-user text-gray-400"></i>
                            </div>
                        </div>
                        <p id="usernameError" class="hidden text-sm text-red-600 mt-1"></p>
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-lock mr-2 text-purple-500"></i>Password
                        </label>
                        <div class="relative">
                            <input type="password" id="password" name="password" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition pr-10"
                                   placeholder="Masukkan password">
                            <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <i class="fas fa-eye text-gray-400 hover:text-gray-600 transition"></i>
                            </button>
                        </div>
                        <p id="passwordError" class="hidden text-sm text-red-600 mt-1"></p>
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="remember" name="remember" type="checkbox" 
                                   class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                            <label for="remember" class="ml-2 block text-sm text-gray-700">
                                Ingat saya
                            </label>
                        </div>
                        <div class="text-sm">
                            <a href="#" onclick="showForgotPassword()" class="text-indigo-600 hover:text-indigo-700 font-medium transition">
                                Lupa password?
                            </a>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" id="loginButton"
                            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-4 px-6 rounded-lg transition duration-200 transform hover:scale-105 focus:ring-4 focus:ring-indigo-200 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none">
                        <span id="loginButtonText">
                            <i class="fas fa-sign-in-alt mr-2"></i>Masuk
                        </span>
                        <span id="loginButtonLoading" class="hidden">
                            <i class="fas fa-spinner fa-spin mr-2"></i>Memproses...
                        </span>
                    </button>

                    <!-- Register Link -->
                    <div class="text-center pt-4 border-t border-gray-200">
                        <p class="text-gray-600">
                            Belum punya akun keluarga?
                            <a href="#" onclick="goToRegister()" class="text-indigo-600 hover:text-indigo-700 font-medium transition">
                                Daftar di sini
                            </a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Info Section -->
    <section class="py-16 bg-white">
        <div class="max-w-4xl mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Panduan Login Admin Keluarga</h2>
                <p class="text-gray-600">Ikuti langkah-langkah berikut untuk masuk ke sistem</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-indigo-50 rounded-xl p-6 text-center transform hover:scale-105 transition duration-200">
                    <div class="w-12 h-12 bg-indigo-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-white font-bold text-lg">1</span>
                    </div>
                    <h3 class="text-lg font-semibold text-indigo-900 mb-2">Masukkan Username</h3>
                    <p class="text-indigo-700">Gunakan username yang sudah Anda daftarkan saat membuat keluarga</p>
                </div>
                
                <div class="bg-purple-50 rounded-xl p-6 text-center transform hover:scale-105 transition duration-200">
                    <div class="w-12 h-12 bg-purple-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-white font-bold text-lg">2</span>
                    </div>
                    <h3 class="text-lg font-semibold text-purple-900 mb-2">Masukkan Password</h3>
                    <p class="text-purple-700">Ketik password yang Anda buat saat mendaftarkan keluarga</p>
                </div>
                
                <div class="bg-pink-50 rounded-xl p-6 text-center transform hover:scale-105 transition duration-200">
                    <div class="w-12 h-12 bg-pink-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-white font-bold text-lg">3</span>
                    </div>
                    <h3 class="text-lg font-semibold text-pink-900 mb-2">Kelola Keluarga</h3>
                    <p class="text-pink-700">Setelah login, Anda dapat mengelola data keluarga dan anggota</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-6xl mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Fitur Admin Keluarga</h2>
                <p class="text-gray-600">Berbagai fitur yang dapat Anda gunakan setelah login</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white rounded-xl p-6 shadow-md hover:shadow-lg transition duration-200">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-users text-blue-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Kelola Keluarga</h3>
                    <p class="text-gray-600 text-sm">Edit informasi keluarga, domisili, dan deskripsi</p>
                </div>

                <div class="bg-white rounded-xl p-6 shadow-md hover:shadow-lg transition duration-200">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-user-plus text-green-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Tambah Anggota</h3>
                    <p class="text-gray-600 text-sm">Menambahkan anggota keluarga baru dengan lengkap</p>
                </div>

                <div class="bg-white rounded-xl p-6 shadow-md hover:shadow-lg transition duration-200">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-edit text-purple-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Edit Anggota</h3>
                    <p class="text-gray-600 text-sm">Memperbarui data anggota keluarga yang ada</p>
                </div>

                <div class="bg-white rounded-xl p-6 shadow-md hover:shadow-lg transition duration-200">
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-sitemap text-orange-600 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Pohon Keluarga</h3>
                    <p class="text-gray-600 text-sm">Melihat visualisasi hubungan keluarga</p>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-16 bg-white">
        <div class="max-w-4xl mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Pertanyaan Umum</h2>
                <p class="text-gray-600">Jawaban untuk pertanyaan yang sering diajukan</p>
            </div>
            
            <div class="space-y-6">
                <div class="bg-gray-50 rounded-xl p-6">
                    <button onclick="toggleFAQ(1)" class="flex items-center justify-between w-full text-left">
                        <h3 class="text-lg font-semibold text-gray-900">Bagaimana cara mendaftar sebagai admin keluarga?</h3>
                        <i id="faqIcon1" class="fas fa-chevron-down text-gray-500 transform transition duration-200"></i>
                    </button>
                    <div id="faqAnswer1" class="hidden mt-4">
                        <p class="text-gray-600">Klik tombol "Daftar di sini" di bawah form login, lalu isi semua informasi keluarga yang diperlukan. Setelah pendaftaran berhasil, Anda akan otomatis login sebagai admin keluarga tersebut.</p>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-xl p-6">
                    <button onclick="toggleFAQ(2)" class="flex items-center justify-between w-full text-left">
                        <h3 class="text-lg font-semibold text-gray-900">Apa yang bisa saya lakukan sebagai admin keluarga?</h3>
                        <i id="faqIcon2" class="fas fa-chevron-down text-gray-500 transform transition duration-200"></i>
                    </button>
                    <div id="faqAnswer2" class="hidden mt-4">
                        <p class="text-gray-600">Sebagai admin keluarga, Anda dapat mengelola data keluarga Anda sendiri, menambah/edit/hapus anggota keluarga, mengatur hubungan parent-child, dan melihat pohon keluarga. Anda hanya dapat mengelola keluarga Anda sendiri.</p>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-xl p-6">
                    <button onclick="toggleFAQ(3)" class="flex items-center justify-between w-full text-left">
                        <h3 class="text-lg font-semibold text-gray-900">Bagaimana jika lupa password?</h3>
                        <i id="faqIcon3" class="fas fa-chevron-down text-gray-500 transform transition duration-200"></i>
                    </button>
                    <div id="faqAnswer3" class="hidden mt-4">
                        <p class="text-gray-600">Fitur reset password sedang dalam pengembangan. Untuk sementara, silakan hubungi administrator sistem melalui kontak yang tersedia di halaman utama.</p>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-xl p-6">
                    <button onclick="toggleFAQ(4)" class="flex items-center justify-between w-full text-left">
                        <h3 class="text-lg font-semibold text-gray-900">Apakah data keluarga saya aman?</h3>
                        <i id="faqIcon4" class="fas fa-chevron-down text-gray-500 transform transition duration-200"></i>
                    </button>
                    <div id="faqAnswer4" class="hidden mt-4">
                        <p class="text-gray-600">Ya, sistem kami menggunakan enkripsi password dan kontrol akses yang ketat. Setiap admin keluarga hanya dapat mengakses dan mengelola data keluarganya sendiri, tidak dapat mengakses data keluarga lain.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="max-w-6xl mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-xl font-bold mb-4">Bani Parno</h3>
                    <p class="text-gray-400">Sistem manajemen keluarga digital untuk melestarikan silsilah dan memperkuat hubungan kekeluargaan.</p>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold mb-4">Menu Utama</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Beranda</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Keluarga</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Anggota</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition">Riwayat</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold mb-4">Kontak</h3>
                    <div class="space-y-2">
                        <p class="text-gray-400">
                            <i class="fas fa-envelope mr-2"></i>
                            admin@baniparno.com
                        </p>
                        <p class="text-gray-400">
                            <i class="fas fa-phone mr-2"></i>
                            +62 812-3456-7890
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="border-t border-gray-700 mt-8 pt-8 text-center">
                <p class="text-gray-400">&copy; 2024 Bani Parno. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Forgot Password Modal -->
    <div id="forgotPasswordModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white rounded-lg p-6 max-w-md mx-4 w-full">
            <div class="text-center">
                <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-key text-orange-600 text-xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Lupa Password</h3>
                <p class="text-gray-600 mb-6">
                    Fitur reset password sedang dalam pengembangan. Untuk bantuan, silakan hubungi administrator.
                </p>
                <div class="flex space-x-4">
                    <button onclick="closeForgotPassword()" 
                            class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-700 py-2 px-4 rounded-lg transition">
                        Tutup
                    </button>
                    <a href="mailto:admin@baniparno.com" 
                       class="flex-1 bg-orange-600 hover:bg-orange-700 text-white py-2 px-4 rounded-lg transition text-center">
                        Hubungi Admin
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Demo users for testing
        const demoUsers = {
            'admin_soekarno': { password: 'password123', name: 'Keluarga Soekarno' },
            'admin_kartini': { password: 'kartini456', name: 'Keluarga Kartini' },
            'demo_user': { password: 'demo123', name: 'Keluarga Demo' }
        };

        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('loginForm');
            const usernameInput = document.getElementById('username');
            const passwordInput = document.getElementById('password');
            const submitBtn = document.getElementById('loginButton');
            const togglePasswordBtn = document.getElementById('togglePassword');
            
            // Form validation
            function validateForm() {
                const isValid = usernameInput.value.trim() !== '' && passwordInput.value.trim() !== '';
                submitBtn.disabled = !isValid;
            }
            
            usernameInput.addEventListener('input', validateForm);
            passwordInput.addEventListener('input', validateForm);
            validateForm();
            
            // Toggle password visibility
            togglePasswordBtn.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                
                const icon = this.querySelector('i');
                icon.classList.toggle('fa-eye');
                icon.classList.toggle('fa-eye-slash');
            });
            
            // Form submission
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                handleLogin();
            });

            // Demo info display
            displayDemoInfo();
        });

        function handleLogin() {
            const username = document.getElementById('username').value.trim();
            const password = document.getElementById('password').value.trim();
            const loginButton = document.getElementById('loginButton');
            const loginButtonText = document.getElementById('loginButtonText');
            const loginButtonLoading = document.getElementById('loginButtonLoading');
            
            // Clear previous errors
            hideErrors();
            
            // Basic validation
            if (!username || !password) {
                showError(['Username dan password harus diisi']);
                return;
            }
            
            // Show loading state
            loginButton.disabled = true;
            loginButtonText.classList.add('hidden');
            loginButtonLoading.classList.remove('hidden');
            
            // Simulate API call
            setTimeout(() => {
                // Check demo credentials
                if (demoUsers[username] && demoUsers[username].password === password) {
                    showSuccess();
                    setTimeout(() => {
                        // In real implementation, redirect to dashboard
                        alert(`Login berhasil! Selamat datang di dashboard ${demoUsers[username].name}`);
                        resetForm();
                    }, 1500);
                } else {
                    showError(['Username atau password salah']);
                }
                
                // Reset button state
                loginButton.disabled = false;
                loginButtonText.classList.remove('hidden');
                loginButtonLoading.classList.add('hidden');
            }, 1500);
        }

        function showError(messages) {
            const errorDiv = document.getElementById('errorMessages');
            const errorList = document.getElementById('errorList');
            
            errorList.innerHTML = '';
            messages.forEach(message => {
                const li = document.createElement('li');
                li.textContent = message;
                errorList.appendChild(li);
            });
            
            errorDiv.classList.remove('hidden');
            
            // Auto hide after 5 seconds
            setTimeout(() => {
                hideErrors();
            }, 5000);
        }

        function showSuccess() {
            const successDiv = document.getElementById('successMessage');
            successDiv.classList.remove('hidden');
        }

        function hideErrors() {
            document.getElementById('errorMessages').classList.add('hidden');
            document.getElementById('successMessage').classList.add('hidden');
        }

        function resetForm() {
            document.getElementById('loginForm').reset();
            document.getElementById('loginButton').disabled = true;
            hideErrors();
        }

        function showForgotPassword() {
            document.getElementById('forgotPasswordModal').classList.remove('hidden');
        }

        function closeForgotPassword() {
            document.getElementById('forgotPasswordModal').classList.add('hidden');
        }

        function goToRegister() {
            alert('Mengalihkan ke halaman pendaftaran keluarga...');
            // In real implementation: window.location.href = '/keluarga/daftar';
        }

        function toggleFAQ(number) {
            const answer = document.getElementById(`faqAnswer${number}`);
            const icon = document.getElementById(`faqIcon${number}`);
            
            answer.classList.toggle('hidden');
            icon.classList.toggle('rotate-180');
        }

        function displayDemoInfo() {
            // Create demo info box
            const demoInfo = document.createElement('div');
            demoInfo.className = 'fixed bottom-4 right-4 bg-yellow-100 border border-yellow-300 rounded-lg p-4 max-w-sm shadow-lg z-40';
            demoInfo.innerHTML = `
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-yellow-600 text-lg"></i>
                    </div>
                    <div class="ml-3">
                        <h4 class="text-sm font-medium text-yellow-800">Demo Login</h4>
                        <div class="mt-2 text-xs text-yellow-700">
                            <p><strong>Username:</strong> admin_soekarno</p>
                            <p><strong>Password:</strong> password123</p>
                            <p class="mt-2">atau gunakan:</p>
                            <p><strong>Username:</strong> demo_user</p>
                            <p><strong>Password:</strong> demo123</p>
                        </div>
                        <button onclick="this.parentElement.parentElement.parentElement.remove()" 
                                class="mt-2 text-xs text-yellow-600 hover:text-yellow-800">
                            <i class="fas fa-times mr-1"></i>Tutup
                        </button>
                    </div>
                </div>
            `;
            document.body.appendChild(demoInfo);
        }

        // Close modal when clicking outside
        document.getElementById('forgotPasswordModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeForgotPassword();
            }
        });

        // Auto-fill demo credentials (for testing)
        function fillDemo(username, password) {
            document.getElementById('username').value = username;
            document.getElementById('password').value = password;
            document.getElementById('loginButton').disabled = false;
        }

        // Add keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl + Enter to submit form
            if (e.ctrlKey && e.key === 'Enter') {
                document.getElementById('loginForm').dispatchEvent(new Event('submit'));
            }
            
            // Escape to close modals
            if (e.key === 'Escape') {
                closeForgotPassword();
            }
        });
    </script>
</body>
</html>