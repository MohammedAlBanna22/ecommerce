<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Sign In' }} | {{ config('app.name', 'MyShop') }}</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        inter: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        'amz-dark': '#131921',
                        'amz-nav': '#232f3e',
                        'amz-navHover': '#2c3e50',
                        'amz-orange': '#FF9900',
                        'amz-orange-hover': '#E68A00',
                        'amz-blue': '#1f5a96',
                        'amz-green': '#00a699',
                        'amz-deal': '#D91E1E',
                        'amz-text': '#0f1419',
                        'amz-text-sec': '#555',
                        'amz-text-weak': '#999',
                    },
                }
            }
        }
    </script>

    <!-- Lucide Icons CDN -->
    <script src="https://cdn.jsdelivr.net/npm/lucide@latest"></script>

    <style>
        * {
            font-family: 'Inter', sans-serif;
        }

        [x-cloak] {
            display: none !important;
        }

        html, body {
            margin: 0;
            padding: 0;
            background-color: #ffffff;
        }

        /* Form input styling */
        input[type="text"],
        input[type="email"],
        input[type="password"],
        textarea {
            @apply border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amz-orange focus:border-transparent;
        }

        button[type="submit"] {
            @apply font-medium transition-colors;
        }

        /* Lucide Icons */
        [data-lucide] {
            width: 1em;
            height: 1em;
        }
    </style>

    @stack('styles')
</head>

<body class="bg-gray-50 text-amz-text antialiased">

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <div class="min-h-screen flex flex-col">

        <!-- Header -->
        <header class="bg-white border-b border-gray-200 py-4 sm:py-6">
            <div class="max-w-[1500px] mx-auto px-4 sm:px-6 lg:px-8">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-amz-dark hover:opacity-80 transition">
                    <i data-lucide="shopping-bag" class="w-8 h-8 text-amz-orange"></i>
                    <span class="text-2xl font-extrabold hidden sm:block">{{ config('app.name', 'MyShop') }}</span>
                </a>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 flex items-center justify-center px-4 py-12 sm:py-20">

            <!-- Flash Messages -->
            @if(session('status'))
            <div class="absolute top-24 left-0 right-0 z-50">
                <div class="max-w-md mx-auto bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg shadow-md flex items-center gap-2">
                    <i data-lucide="check-circle" class="w-5 h-5 flex-shrink-0"></i>
                    <span class="text-sm">{{ session('status') }}</span>
                </div>
            </div>
            @endif

            @if($errors->any())
            <div class="absolute top-24 left-0 right-0 z-50">
                <div class="max-w-md mx-auto bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg shadow-md">
                    <div class="flex items-start gap-2 mb-2">
                        <i data-lucide="alert-circle" class="w-5 h-5 flex-shrink-0 mt-0.5"></i>
                        <p class="font-medium text-sm">{{ count($errors) }} error{{ count($errors) > 1 ? 's' : '' }} found</p>
                    </div>
                    <ul class="list-inside space-y-1">
                        @foreach($errors->all() as $error)
                        <li class="text-sm ml-7">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

            <!-- Form Container -->
            <div class="w-full max-w-sm">

                <!-- Card -->
                <div class="bg-white border border-gray-300 rounded-lg shadow-sm p-6 sm:p-8">
                    {{ $slot }}
                </div>

                <!-- Help Text -->
                <div class="mt-6 text-center space-y-2 text-sm text-amz-text-weak">
                    <p>Secure checkout powered by {{ config('app.name', 'MyShop') }}</p>
                    <a href="{{ route('home') }}" class="text-amz-blue hover:underline">← Back to Shop</a>
                </div>

            </div>

        </main>

        <!-- Footer -->
        <footer class="bg-amz-dark text-gray-400 py-8 border-t border-gray-200 mt-auto">
            <div class="max-w-[1500px] mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4 text-[12px]">
                    <p>© {{ date('Y') }} {{ config('app.name', 'MyShop') }}. All rights reserved.</p>
                    <div class="flex gap-6">
                        <a href="#" class="hover:text-gray-200 transition">Privacy Policy</a>
                        <a href="#" class="hover:text-gray-200 transition">Terms of Service</a>
                        <a href="#" class="hover:text-gray-200 transition">Contact Us</a>
                    </div>
                </div>
            </div>
        </footer>

    </div>

    <!-- Initialize Lucide Icons -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>

    @stack('scripts')

</body>
</html>
