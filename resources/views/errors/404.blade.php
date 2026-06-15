<!DOCTYPE html>
<html lang="en" class="h-full bg-[#F4F7FB]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 Not Found - CE&P Internal Audit System</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .premium-shadow { box-shadow: 0 18px 50px -20px rgba(15, 23, 42, 0.25); }
        .bg-gradient-primary { background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%); }
    </style>
</head>
<body class="min-h-full bg-[#F4F7FB] text-slate-800 antialiased">
    <main class="min-h-screen flex items-center justify-center px-6 py-12 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-[700px] h-[500px] bg-indigo-100/70 rounded-full blur-[120px] translate-x-1/3 -translate-y-1/3"></div>
        <div class="absolute bottom-0 left-0 w-[560px] h-[520px] bg-sky-100/70 rounded-full blur-[120px] -translate-x-1/3 translate-y-1/3"></div>

        <section class="relative w-full max-w-xl bg-white border border-slate-100 rounded-3xl premium-shadow p-8 sm:p-12 text-center">
            <div class="mx-auto mb-6 h-16 w-16 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center border border-indigo-100">
                <i class="ph ph-magnifying-glass text-3xl"></i>
            </div>

            <p class="text-sm font-extrabold text-indigo-600 tracking-[0.18em] uppercase mb-3">404 Not Found</p>
            <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight text-slate-900 mb-4">Page not found</h1>
            <p class="text-slate-500 font-medium leading-relaxed mb-8">
                The page may have moved, or you may not have access to this area.
            </p>

            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                @auth
                    <a href="{{ auth()->user()->role === 'admin' ? route('dashboard') : route('audits.index') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-primary px-5 py-3 text-sm font-bold text-white shadow-lg shadow-indigo-500/20 hover:opacity-95 transition">
                        <i class="ph ph-house text-lg"></i>
                        Back to Home
                    </a>
                @else
                    <a href="{{ route('login') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-primary px-5 py-3 text-sm font-bold text-white shadow-lg shadow-indigo-500/20 hover:opacity-95 transition">
                        <i class="ph ph-sign-in text-lg"></i>
                        Sign In
                    </a>
                @endauth

                <button type="button" onclick="history.back()" class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-bold text-slate-700 hover:bg-slate-50 transition">
                    <i class="ph ph-arrow-left text-lg"></i>
                    Go Back
                </button>
            </div>
        </section>
    </main>
</body>
</html>
