<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Bani Parno')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <nav class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <h1 class="text-xl font-bold">Bani Parno</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('families.index') }}" class="text-gray-600 hover:text-gray-900">Keluarga</a>
                    <a href="{{ route('members.index') }}" class="text-gray-600 hover:text-gray-900">Anggota</a>
                    <a href="/admin" class="bg-blue-500 text-white px-4 py-2 rounded">Admin Login</a>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto py-6 px-4">
        @yield('content')
    </main>
</body>
</html>