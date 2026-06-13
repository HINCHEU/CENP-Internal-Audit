<!DOCTYPE html>
<html lang="en" class="h-full bg-[#F4F7FB]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'CE&P Internal Audit System')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}" >
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        
        /* Premium Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94A3B8; }

        /* Smooth page transitions */
        .page-enter { animation: fade-in-up 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        @keyframes fade-in-up {
            0% { opacity: 0; transform: translateY(10px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        /* Glass sidebar item active */
        .nav-item { position: relative; overflow: hidden; }
        .nav-item::after {
            content: ''; position: absolute; left: 0; top: 0; height: 100%; width: 3px;
            background: linear-gradient(to bottom, #6366F1, #A855F7);
            transform: scaleY(0); transition: transform 0.3s ease; transform-origin: left;
        }
        .nav-item.active { background: rgba(99, 102, 241, 0.08); color: #E0E7FF; }
        .nav-item.active::after { transform: scaleY(1); }
        .nav-item.active i { color: #818CF8; }
        
        .premium-shadow { box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.05); }
        .premium-hover:hover { transform: translateY(-2px); box-shadow: 0 12px 30px -4px rgba(0, 0, 0, 0.08); }
        
        /* Gradients */
        .text-gradient { background: linear-gradient(135deg, #4F46E5 0%, #9333EA 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .bg-gradient-primary { background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%); }
    </style>
</head>
<body class="h-full flex overflow-hidden text-[#1E293B] antialiased bg-[#F4F7FB]">

    <!-- Sidebar -->
    <aside class="w-[280px] bg-[#0A0F1C] border-r border-white/5 flex flex-col justify-between hidden md:flex shadow-2xl z-20 relative">
        <!-- Subtle Glow behind sidebar -->
        <div class="absolute top-0 left-0 w-full h-64 bg-indigo-500/10 blur-[80px] -z-10 pointer-events-none"></div>

        <div class="flex-1 flex flex-col overflow-y-auto overflow-x-hidden">
            <!-- Logo area -->
            <div class="h-24 flex items-center px-8 shrink-0">
                <div class="bg-white/10 p-2 rounded-xl backdrop-blur-md border border-white/10 mr-3">
                    <img src="{{ asset('images/logo.png') }}" alt="CE&P Logo" class="h-7 w-auto filter brightness-0 invert">
                </div>
                <div>
                    <h2 class="text-white font-bold text-lg tracking-tight leading-tight">CE&P Audit</h2>
                    <p class="text-indigo-300/70 text-[10px] font-semibold uppercase tracking-widest">Internal System</p>
                </div>
            </div>
            
            <!-- Navigation -->
            <nav class="flex-1 px-4 pb-8 space-y-8 mt-4">
                
                @if(auth()->check() && auth()->user()->role === 'admin')
                <div>
                    <p class="px-4 text-[11px] font-bold text-slate-500 uppercase tracking-widest mb-3">Menu</p>
                    <div class="space-y-1">
                        <a href="{{ route('dashboard') }}" class="nav-item flex items-center gap-3.5 px-4 py-3 rounded-xl text-slate-400 hover:text-white hover:bg-white/5 transition-all duration-300 font-medium text-sm {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="ph ph-squares-four text-xl transition-colors"></i> Dashboard
                        </a>
                        
                        <a href="{{ route('projects.index') }}" class="nav-item flex items-center gap-3.5 px-4 py-3 rounded-xl text-slate-400 hover:text-white hover:bg-white/5 transition-all duration-300 font-medium text-sm {{ request()->routeIs('projects.*') ? 'active' : '' }}">
                            <i class="ph ph-briefcase text-xl transition-colors"></i> Projects
                        </a>

                        <a href="{{ route('audit-events.index') }}" class="nav-item flex items-center gap-3.5 px-4 py-3 rounded-xl text-slate-400 hover:text-white hover:bg-white/5 transition-all duration-300 font-medium text-sm {{ request()->routeIs('audit-events.*') ? 'active' : '' }}">
                            <i class="ph ph-calendar-check text-xl transition-colors"></i> Audit Events
                        </a>

                        <a href="{{ route('admin-evaluations.index') }}" class="nav-item flex items-center gap-3.5 px-4 py-3 rounded-xl text-slate-400 hover:text-white hover:bg-white/5 transition-all duration-300 font-medium text-sm {{ request()->routeIs('admin-evaluations.*') ? 'active' : '' }}">
                            <i class="ph ph-star text-xl transition-colors"></i> Quick Evaluations
                        </a>
                    </div>
                </div>
                @endif
                
                <div>
                    <p class="px-4 text-[11px] font-bold text-slate-500 uppercase tracking-widest mb-3">Auditor Workspace</p>
                    <div class="space-y-1">
                        <a href="{{ route('audits.index') }}" class="nav-item flex items-center justify-between px-4 py-3 rounded-xl text-slate-400 hover:text-white hover:bg-white/5 transition-all duration-300 font-medium text-sm {{ request()->routeIs('audits.*') ? 'active' : '' }}">
                            <div class="flex items-center gap-3.5">
                                <i class="ph ph-clipboard-text text-xl transition-colors"></i> My Audits
                            </div>
                            @if(isset($pendingAuditsCount) && $pendingAuditsCount > 0)
                                <span class="inline-flex items-center justify-center min-w-[22px] h-[22px] px-1.5 rounded-full bg-rose-500 text-white text-[11px] font-bold ring-2 ring-[#0A0F1C]">
                                    {{ $pendingAuditsCount > 99 ? '99+' : $pendingAuditsCount }}
                                </span>
                            @endif
                        </a>

                        @php
                            $hasSubmittedEvaluations = \App\Models\EvaluationScore::where('user_id', auth()->id())->exists();
                        @endphp
                        @if(auth()->user()->role === 'admin' || $hasSubmittedEvaluations)
                        <a href="{{ route('user-evaluations.index') }}" class="nav-item flex items-center gap-3.5 px-4 py-3 rounded-xl text-slate-400 hover:text-white hover:bg-white/5 transition-all duration-300 font-medium text-sm {{ request()->routeIs('user-evaluations.*') ? 'active' : '' }}">
                            <i class="ph ph-star-half text-xl transition-colors"></i> Score Evaluations
                        </a>
                        @endif
                    </div>
                </div>

                @if(auth()->check() && auth()->user()->role === 'admin')
                <div>
                    <p class="px-4 text-[11px] font-bold text-slate-500 uppercase tracking-widest mb-3">Administration</p>
                    <div class="space-y-1">
                        <a href="{{ route('departments.index') }}" class="nav-item flex items-center gap-3.5 px-4 py-3 rounded-xl text-slate-400 hover:text-white hover:bg-white/5 transition-all duration-300 font-medium text-sm {{ request()->routeIs('departments.*') ? 'active' : '' }}">
                            <i class="ph ph-buildings text-xl transition-colors"></i> Departments
                        </a>
                        
                        <a href="{{ route('users.index') }}" class="nav-item flex items-center gap-3.5 px-4 py-3 rounded-xl text-slate-400 hover:text-white hover:bg-white/5 transition-all duration-300 font-medium text-sm {{ request()->routeIs('users.*') ? 'active' : '' }}">
                            <i class="ph ph-users text-xl transition-colors"></i> Users
                        </a>

                        <a href="{{ route('reports.index') }}" class="nav-item flex items-center gap-3.5 px-4 py-3 rounded-xl text-slate-400 hover:text-white hover:bg-white/5 transition-all duration-300 font-medium text-sm {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                            <i class="ph ph-chart-polar text-xl transition-colors"></i> Reports & Analytics
                        </a>
                    </div>
                </div>
                @endif
            </nav>
        </div>

        @auth
        <div class="p-5 mx-4 mb-6 rounded-2xl bg-gradient-to-br from-white/5 to-transparent border border-white/10 backdrop-blur-sm">
            <div class="flex items-center gap-3 mb-4">
                <div class="relative">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=6366F1&color=fff&rounded=true" alt="User" class="w-10 h-10 rounded-full ring-2 ring-white/10">
                    <div class="absolute bottom-0 right-0 w-3 h-3 rounded-full bg-emerald-400 ring-2 ring-[#0A0F1C]"></div>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                    <p class="text-[11px] text-slate-400 truncate capitalize">{{ str_replace('_', ' ', auth()->user()->role) }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <button type="submit" class="w-full py-2 rounded-lg bg-white/5 hover:bg-white/10 text-white text-xs font-medium transition-colors flex items-center justify-center gap-2">
                    <i class="ph ph-sign-out"></i> Sign Out
                </button>
            </form>
        </div>
        @endauth
    </aside>

    <!-- Main Content Wrapper -->
    <div class="flex-1 flex flex-col h-full overflow-hidden relative w-full">
        
        <!-- Abstract Background Shapes -->
        <div class="absolute top-0 right-0 w-[800px] h-[600px] bg-indigo-50/50 rounded-full blur-[120px] -z-10 pointer-events-none translate-x-1/3 -translate-y-1/3"></div>
        <div class="absolute bottom-0 left-0 w-[600px] h-[600px] bg-purple-50/50 rounded-full blur-[100px] -z-10 pointer-events-none -translate-x-1/3 translate-y-1/3"></div>

        <!-- Top Header -->
        <header class="h-24 bg-white/40 backdrop-blur-xl border-b border-white/60 flex items-center justify-between px-8 z-10 sticky top-0 transition-all duration-300 shadow-sm">
            <div class="page-enter">
                <h1 class="text-[26px] font-extrabold text-slate-800 tracking-tight leading-none mb-1">@yield('header')</h1>
                <p class="text-sm font-medium text-slate-500">@yield('subheader', 'Manage and monitor your internal audit processes.')</p>
            </div>

            <div class="flex items-center gap-4">
                <!-- Search -->
                <div class="hidden lg:flex items-center relative mr-2">
                    <i class="ph ph-magnifying-glass absolute left-4 text-slate-400"></i>
                    <input type="text" placeholder="Quick search..." class="pl-11 pr-4 py-2.5 bg-white border border-slate-200 rounded-full text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 w-64 shadow-sm transition-all duration-300 focus:w-72">
                </div>

                <a href="{{ route('audits.index') }}" class="relative p-2.5 text-slate-500 hover:text-indigo-600 transition-colors bg-white rounded-full premium-shadow premium-hover" title="My Audits">
                    <i class="ph ph-bell text-xl"></i>
                    @if($pendingAuditsCount > 0)
                        <span class="absolute -top-0.5 -right-0.5 inline-flex items-center justify-center min-w-[18px] h-[18px] px-1 rounded-full bg-rose-500 text-white text-[10px] font-bold border-2 border-white">
                            {{ $pendingAuditsCount > 99 ? '99+' : $pendingAuditsCount }}
                        </span>
                    @endif
                </a>
                <button class="p-2.5 text-slate-500 hover:text-indigo-600 transition-colors bg-white rounded-full premium-shadow premium-hover">
                    <i class="ph ph-gear-six text-xl"></i>
                </button>
            </div>
        </header>

        <!-- Main Scrollable Content -->
        <main class="flex-1 overflow-y-auto p-8 z-0">
            <div class="max-w-7xl mx-auto page-enter">
                @yield('content')
            </div>
        </main>
    </div>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Global Delete Confirmation
        function confirmDelete(formId, entityName = 'this item') {
            Swal.fire({
                title: 'Are you sure?',
                text: "You are about to delete " + entityName + ". This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#4F46E5', // indigo-600
                cancelButtonColor: '#F43F5E', // rose-500
                confirmButtonText: 'Yes, delete it!',
                scrollbarPadding: false,
                heightAuto: false,
                customClass: {
                    popup: 'rounded-3xl premium-shadow border border-slate-100',
                    confirmButton: 'rounded-xl font-bold px-6 py-2.5',
                    cancelButton: 'rounded-xl font-bold px-6 py-2.5'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit();
                }
            });
        }

        // Global Toast Notifications for Session Flashes
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            scrollbarPadding: false,
            heightAuto: false,
            customClass: {
                popup: 'rounded-2xl premium-shadow border border-slate-100',
            },
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });

        @if(session('success'))
            Toast.fire({
                icon: 'success',
                title: '{{ session('success') }}'
            });
        @endif

        @if(session('error'))
            Toast.fire({
                icon: 'error',
                title: '{{ session('error') }}'
            });
        @endif
    </script>
</body>
</html>
