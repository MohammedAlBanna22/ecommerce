<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name', 'MyShop') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'Arial', 'sans-serif'] },
                    colors: {
                        amz: {
                            dark: '#131921',
                            nav: '#232F3E',
                            navHover: '#37475A',
                            orange: '#FF9900',
                            'orange-light': '#FFA41C',
                            'orange-btn': '#FFD814',
                            blue: '#007185',
                            'link-hover': '#C7511F',
                            star: '#FFA41C',
                            green: '#067D62',
                            page: '#EAEDED',
                            border: '#DDD',
                            'border-light': '#E7E7E7',
                            text: '#0F1111',
                            'text-sec': '#565959',
                            'text-weak': '#979797',
                            deal: '#CC0C39',
                        }
                    }
                }
            }
        }
    </script>

    <style>
        * { -webkit-font-smoothing: antialiased; }
        body { background-color: #EAEDED; }
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #f0f0f0; }
        ::-webkit-scrollbar-thumb { background: #ccc; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #aaa; }
        [x-cloak] { display: none !important; }
        .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    </style>

    @stack('styles')
</head>

<body class="font-sans text-[#0F1111] min-h-screen flex flex-col">
@include('layouts.components.navigation')
    {{-- Flash Messages --}}
    @if(session('success'))
    <div class="fixed top-20 left-0 right-0 z-40 flex justify-center px-4">
        <div class="bg-green-50 text-green-700 px-4 py-3 rounded-lg shadow-lg border border-green-200 flex items-center gap-3 max-w-xl w-full">
            <i data-lucide="check-circle" class="w-5 h-5 flex-shrink-0"></i>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="fixed top-20 left-0 right-0 z-40 flex justify-center px-4">
        <div class="bg-red-50 text-red-700 px-4 py-3 rounded-lg shadow-lg border border-red-200 flex items-center gap-3 max-w-xl w-full">
            <i data-lucide="alert-circle" class="w-5 h-5 flex-shrink-0"></i>
            <span class="text-sm font-medium">{{ session('error') }}</span>
        </div>
    </div>
    @endif

    <main class="flex-1">
        @yield('content')
    </main>

    @include('layouts.components.footer')

    <div id="toastContainer" class="fixed bottom-6 left-1/2 -translate-x-1/2 z-[100] flex flex-col items-center gap-2"></div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (typeof lucide !== 'undefined') lucide.createIcons();
        });
    </script>

    @stack('scripts')

</body>
</html>
