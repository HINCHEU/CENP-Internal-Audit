<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Too Many Requests - CE&P Internal Audit System</title>
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
<body class="h-full flex items-center justify-center bg-slate-50 antialiased overflow-hidden">
    
    <!-- Decorative Background Elements -->
    <div class="absolute inset-0 z-0 overflow-hidden pointer-events-none">
        <div class="absolute top-0 left-1/4 w-[500px] h-[500px] bg-indigo-600/10 blur-[120px] rounded-full mix-blend-multiply -translate-x-1/2 -translate-y-1/2"></div>
        <div class="absolute bottom-0 right-1/4 w-[600px] h-[600px] bg-rose-600/10 blur-[150px] rounded-full mix-blend-multiply translate-x-1/3 translate-y-1/3"></div>
    </div>

    <div class="relative z-10 w-full max-w-lg px-8 text-center">
        <div class="w-20 h-20 bg-rose-50 rounded-3xl flex items-center justify-center border border-rose-100 mx-auto mb-8 shadow-sm">
            <i class="ph ph-hand-palm text-4xl text-rose-500"></i>
        </div>
        
        <h1 class="text-6xl font-black text-slate-800 tracking-tight mb-4">429</h1>
        <h2 class="text-2xl font-bold text-slate-700 mb-4">Whoa there, slow down!</h2>
        <p class="text-slate-500 font-medium leading-relaxed mb-8">
            You're making too many requests too quickly. Please wait a minute before trying again to help us keep the system secure and running smoothly.
        </p>

        <a href="javascript:history.back()" class="inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-slate-800 hover:bg-indigo-600 text-white font-bold rounded-xl transition-all duration-300 shadow-lg shadow-slate-900/10 hover:shadow-indigo-500/25 group">
            <i class="ph ph-arrow-left text-lg group-hover:-translate-x-1 transition-transform"></i>
            Go Back
        </a>
    </div>

</body>
</html>
