<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CE&P Internal Audit System</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}" >
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="h-full flex overflow-hidden bg-slate-50 antialiased">

    <!-- Left Branding Section -->
    <div class="hidden lg:flex lg:w-1/2 relative bg-[#0A0F1C] items-center justify-center overflow-hidden">
        <!-- Decorative Background Elements -->
        <div class="absolute inset-0 z-0">
            <div class="absolute top-0 left-0 w-[500px] h-[500px] bg-indigo-600/30 blur-[120px] rounded-full mix-blend-screen -translate-x-1/2 -translate-y-1/2"></div>
            <div class="absolute bottom-0 right-0 w-[600px] h-[600px] bg-purple-600/20 blur-[150px] rounded-full mix-blend-screen translate-x-1/3 translate-y-1/3"></div>
            <!-- Grid Pattern -->
            <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMSIgY3k9IjEiIHI9IjEiIGZpbGw9InJnYmEoMjU1LDI1NSwyNTUsMC4wNSkiLz48L3N2Zz4=')] opacity-50"></div>
        </div>

        <div class="relative z-10 w-full max-w-lg px-12">
            <div class="bg-white/10 p-3 rounded-2xl backdrop-blur-md border border-white/10 inline-block mb-8">
                <img src="{{ asset('images/logo.png') }}" alt="CE&P Logo" class="h-10 filter brightness-0 invert">
            </div>
            <h1 class="text-4xl font-extrabold text-white tracking-tight leading-tight mb-4">
                Internal Audit <br>
                <span class="text-indigo-400">Management System</span>
            </h1>
            <p class="text-slate-400 font-medium leading-relaxed max-w-md">
                Securely manage projects, schedule audit events, and track findings across CE&P Corporation departments.
            </p>
        </div>
    </div>

    <!-- Right Login Section -->
    <div class="w-full lg:w-1/2 flex items-center justify-center bg-white p-8 sm:p-12 relative">
        <div class="w-full max-w-[420px]">
            <!-- Mobile Logo -->
            <div class="lg:hidden flex justify-center mb-8">
                <img src="{{ asset('images/logo.png') }}" alt="CE&P Logo" class="h-12">
            </div>

            <div class="mb-10">
                <h2 class="text-3xl font-extrabold text-slate-800 tracking-tight mb-2">Welcome Back</h2>
                <p class="text-slate-500 font-medium text-sm">Please enter your credentials to access your account.</p>
            </div>

            <form action="{{ route('login.post') }}" method="POST" class="space-y-6">
                @csrf

                @if($errors->any())
                <div class="bg-rose-50 border border-rose-100 text-rose-600 rounded-xl p-4 text-sm font-medium flex items-start gap-3">
                    <i class="ph ph-warning-circle text-xl mt-0.5"></i>
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <div class="space-y-2">
                    <label class="block text-sm font-bold text-slate-700">Email Address</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="ph ph-envelope-simple text-slate-400 text-lg"></i>
                        </div>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="name@company.com" class="w-full pl-11 pr-4 py-3.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 bg-slate-50 hover:bg-white transition-colors font-medium text-slate-800 outline-none shadow-sm">
                    </div>
                </div>

                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <label class="block text-sm font-bold text-slate-700">Password</label>
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="ph ph-lock-key text-slate-400 text-lg"></i>
                        </div>
                        <input type="password" name="password" required placeholder="••••••••" class="w-full pl-11 pr-4 py-3.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 bg-slate-50 hover:bg-white transition-colors font-medium text-slate-800 outline-none shadow-sm">
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <label for="remember" class="flex items-center gap-2 cursor-pointer group">
                        <input type="checkbox" name="remember" id="remember" class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500 border-slate-300 transition-colors" {{ old('remember') ? 'checked' : '' }}>
                        <span class="text-sm font-medium text-slate-600 group-hover:text-slate-800 transition-colors">Remember me for 30 days</span>
                    </label>
                </div>

                <button type="submit" class="w-full bg-slate-800 hover:bg-indigo-600 text-white font-bold py-3.5 rounded-xl transition-all duration-300 shadow-lg shadow-slate-900/10 hover:shadow-indigo-500/25 flex items-center justify-center gap-2 mt-4 group">
                    Sign In to Workspace 
                    <i class="ph ph-arrow-right font-bold group-hover:translate-x-1 transition-transform"></i>
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-sm font-medium text-slate-500">
                    Don't have an account? 
                    <a href="{{ route('register') }}" class="text-indigo-600 hover:text-indigo-700 font-bold ml-1 hover:underline">Register</a>
                </p>
            </div>

            <div class="mt-8 text-center pb-8 lg:pb-0">
                <p class="text-xs font-medium text-slate-400">
                    &copy; {{ date('Y') }} CE&P Corporation. <br> Internal Audit Management System.
                </p>
            </div>
        </div>
    </div>

</body>
</html>
