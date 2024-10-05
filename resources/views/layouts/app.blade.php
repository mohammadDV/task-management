<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Include tailwindcss -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Include style.css -->
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <!-- Include Axios and jQuery -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex flex-col">
        <!-- Navigation Bar -->
        <nav class="bg-white shadow-md py-4">
            <div class="container mx-auto px-6 flex gap-2 items-center">
                <a href="/dashboard" title="Main page" class="text-lg font-semibold text-gray-700">Magicport</a>
                <div>
                    <a href="/dashboard" title="Dashboard" class="text-gray-600 hover:text-gray-900">Dashboard</a>
                </div>
                <div class="ml-auto">
                    @if(Auth::check())
                        <a href="#" id="logoutBtn" class="text-gray-600 hover:text-gray-900">Logout</a>
                    @endif
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main class="flex-grow container mx-auto px-6 py-8">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-white shadow-md py-4 text-center">
            <p class="text-gray-600">&copy; 2024 Magicport. All rights reserved.</p>
        </footer>
    </div>

    <script>
        // Set CSRF token for Axios requests
        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;

        // Logout function
        $('#logoutBtn').click(function (event) {
            event.preventDefault();

            $.ajax({
                url: '/logout',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    // Remove auth token
                    localStorage.removeItem('auth_token');
                    window.location.href = '/login';
                },
                error: function (xhr, status, error) {
                    console.error('Logout failed:', error);
                }
            });
        });

    </script>
    @yield('scripts')
</body>
</html>
