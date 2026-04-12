<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>AssetNova | Digital Foreman</title>
    <link rel="icon" type="image/png" href="/images/assetnova-logo.png"/>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>

    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "on-secondary-fixed": "#0d1c2e",
                        "primary-fixed-dim": "#adc7f7",
                        "surface-container-low": "#f3f3f8",
                        "error": "#ba1a1a",
                        "primary": "#002045",
                        "secondary-container": "#d1e1fa",
                        "on-primary-fixed": "#001b3c",
                        "primary-container": "#1a365d",
                        "inverse-on-surface": "#f0f0f5",
                        "surface-container": "#ededf2",
                        "tertiary-fixed-dim": "#f2bc82",
                        "on-primary-container": "#86a0cd",
                        "on-background": "#1a1c1f",
                        "surface-container-highest": "#e2e2e7",
                        "on-tertiary-fixed": "#2b1700",
                        "on-surface-variant": "#43474e",
                        "tertiary-fixed": "#ffddba",
                        "surface-container-lowest": "#ffffff",
                        "surface": "#f9f9fe",
                        "on-surface": "#1a1c1f",
                        "tertiary": "#321b00",
                        "on-tertiary": "#ffffff",
                        "on-primary": "#ffffff",
                        "error-container": "#ffdad6",
                        "primary-fixed": "#d6e3ff",
                        "surface-variant": "#e2e2e7",
                        "outline": "#74777f",
                        "on-secondary-container": "#556479",
                        "outline-variant": "#c4c6cf",
                        "secondary-fixed": "#d4e4fc",
                        "on-tertiary-fixed-variant": "#633f0f",
                        "on-error-container": "#93000a",
                        "surface-bright": "#f9f9fe",
                        "on-secondary": "#ffffff",
                        "surface-container-high": "#e8e8ed",
                        "on-error": "#ffffff",
                        "inverse-surface": "#2e3034",
                        "secondary-fixed-dim": "#b8c8e0",
                        "secondary": "#515f74",
                        "tertiary-container": "#4f2e00",
                        "on-tertiary-container": "#c6955e",
                        "on-secondary-fixed-variant": "#39485c",
                        "surface-dim": "#d9dade",
                        "on-primary-fixed-variant": "#2d476f",
                        "background": "#f9f9fe",
                        "surface-tint": "#455f88",
                        "inverse-primary": "#adc7f7"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.125rem",
                        "lg": "0.25rem",
                        "xl": "0.5rem",
                        "full": "0.75rem"
                    },
                    "fontFamily": {
                        "headline": ["Manrope"],
                        "body": ["Inter"],
                        "label": ["Inter"]
                    }
                },
            },
        }
    </script>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            vertical-align: middle;
        }
        /* Prevent Flash of Unstyled Text (FOUT) */
        html { font-size: 14px; }
        body { 
            font-family: 'Inter', sans-serif; 
            opacity: 0; 
            transition: opacity 0.3s ease-in; 
            background-color: #f9f9fe;
        }
        body.loaded { opacity: 1; }
        h1, h2, h3, .headline { font-family: 'Manrope', sans-serif; }
        .glass-header {
            background: rgba(249, 249, 254, 0.9);
            backdrop-filter: blur(12px);
        }
        /* Sidebar drawer transition */
        #sidebar {
            transition: transform 0.28s cubic-bezier(0.4, 0, 0.2, 1);
        }
        #sidebar-overlay {
            transition: opacity 0.28s ease;
        }
    </style>
</head>
<body class="bg-surface text-on-surface">

    <!-- ═══════════════════════════════════════════════════════
         MOBILE OVERLAY (tap to close sidebar)
    ═══════════════════════════════════════════════════════ -->
    <div id="sidebar-overlay"
         class="fixed inset-0 bg-black/40 z-30 hidden lg:hidden"
         onclick="closeSidebar()"></div>

    <!-- ═══════════════════════════════════════════════════════
         SIDEBAR — fixed desktop / drawer mobile
    ═══════════════════════════════════════════════════════ -->
    <aside id="sidebar"
           class="h-screen w-64 fixed left-0 top-0 z-40 bg-[#ededf2] flex flex-col py-6
                  -translate-x-full lg:translate-x-0">

        <!-- Logo -->
        <div class="px-6 mb-8">
            <div class="flex items-center gap-3">
                <img src="/images/assetnova-logo.png" alt="AssetNova" class="w-10 h-10 object-contain rounded-xl"/>
                <div>
                    <h1 class="text-xl font-extrabold tracking-tighter text-[#002045] leading-tight">AssetNova</h1>
                    <p class="text-[9px] uppercase tracking-[0.2em] font-bold text-on-surface-variant/60">{{ auth()->user()->company->name ?? 'Digital Foreman' }}</p>
                </div>
            </div>
        </div>

        <!-- Nav links -->
        <nav class="flex-1 space-y-0.5 overflow-y-auto">
            <a class="flex items-center gap-3 {{ request()->routeIs('dashboard') ? 'bg-white text-[#002045] rounded-l-xl ml-2 shadow-sm font-bold' : 'text-[#43474e] hover:text-[#002045] hover:bg-slate-200/50 transition-all rounded-l-xl ml-2' }} px-6 py-3"
               href="{{ route('dashboard') }}" onclick="closeSidebar()">
                <span class="material-symbols-outlined">dashboard</span>
                <span class="font-['Manrope'] tracking-tight text-sm">Dashboard</span>
            </a>
            <a class="flex items-center gap-3 {{ request()->routeIs('parts.*') ? 'bg-white text-[#002045] rounded-l-xl ml-2 shadow-sm font-bold' : 'text-[#43474e] hover:text-[#002045] hover:bg-slate-200/50 transition-all rounded-l-xl ml-2' }} px-6 py-3"
               href="{{ route('parts.index') }}" onclick="closeSidebar()">
                <span class="material-symbols-outlined">inventory_2</span>
                <span class="font-['Manrope'] tracking-tight text-sm">Parts Directory</span>
            </a>
            <a class="flex items-center gap-3 {{ request()->routeIs('transactions.*') ? 'bg-white text-[#002045] rounded-l-xl ml-2 shadow-sm font-bold' : 'text-[#43474e] hover:text-[#002045] hover:bg-slate-200/50 transition-all rounded-l-xl ml-2' }} px-6 py-3"
               href="{{ route('transactions.index') }}" onclick="closeSidebar()">
                <span class="material-symbols-outlined">swap_horiz</span>
                <span class="font-['Manrope'] tracking-tight text-sm">Transactions</span>
            </a>
            <a class="flex items-center gap-3 {{ request()->routeIs('suppliers.*') ? 'bg-white text-[#002045] rounded-l-xl ml-2 shadow-sm font-bold' : 'text-[#43474e] hover:text-[#002045] hover:bg-slate-200/50 transition-all rounded-l-xl ml-2' }} px-6 py-3"
               href="{{ route('suppliers.index') }}" onclick="closeSidebar()">
                <span class="material-symbols-outlined">conveyor_belt</span>
                <span class="font-['Manrope'] tracking-tight text-sm">Suppliers</span>
            </a>
            <a class="flex items-center gap-3 {{ request()->routeIs('categories.*') ? 'bg-white text-[#002045] rounded-l-xl ml-2 shadow-sm font-bold' : 'text-[#43474e] hover:text-[#002045] hover:bg-slate-200/50 transition-all rounded-l-xl ml-2' }} px-6 py-3"
               href="{{ route('categories.index') }}" onclick="closeSidebar()">
                <span class="material-symbols-outlined">category</span>
                <span class="font-['Manrope'] tracking-tight text-sm">Categories</span>
            </a>
        </nav>

        <!-- Bottom: Settings + Logout -->
        <div class="mt-auto pt-4 border-t border-outline-variant/10 space-y-0.5">
            <a class="flex items-center gap-3 px-6 py-3 transition-all rounded-lg {{ request()->routeIs('settings') ? 'bg-secondary-container text-primary font-bold' : 'text-[#43474e] hover:text-[#002045] hover:bg-surface-container' }}"
               href="{{ route('settings') }}" onclick="closeSidebar()">
                <span class="material-symbols-outlined">settings</span>
                <span class="font-['Manrope'] tracking-tight text-sm">Settings</span>
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-6 py-3 text-error hover:bg-error-container/30 rounded-lg transition-all text-sm">
                    <span class="material-symbols-outlined">logout</span>
                    <span class="font-['Manrope'] tracking-tight text-sm">Sign Out</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- ═══════════════════════════════════════════════════════
         TOP HEADER
    ═══════════════════════════════════════════════════════ -->
    <header class="fixed top-0 right-0 z-30 flex justify-between items-center px-4 md:px-8 h-16 glass-header shadow-[0_12px_32px_-4px_rgba(26,28,31,0.06)]
                   w-full lg:w-[calc(100%-16rem)]">

        <!-- Left: hamburger (mobile) + search (desktop) -->
        <div class="flex items-center gap-3 flex-1 min-w-0">
            <!-- Hamburger — mobile only -->
            <button class="lg:hidden text-[#43474e] hover:text-[#002045] transition-colors flex-shrink-0"
                    onclick="openSidebar()" aria-label="Open menu">
                <span class="material-symbols-outlined text-2xl">menu</span>
            </button>

            <!-- Mobile brand logo (shown only on mobile) -->
            <div class="lg:hidden flex items-center gap-2">
                <img src="/images/assetnova-logo.png" alt="AssetNova" class="w-7 h-7 object-contain"/>
                <span class="font-bold text-primary text-sm font-['Manrope']">AssetNova</span>
            </div>

            <!-- Search bar — hidden on small mobile, shown md+ -->
            <form method="GET" action="{{ route('parts.index') }}" class="relative hidden sm:block w-full max-w-xs md:max-w-sm">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-lg">search</span>
                <input name="search"
                       value="{{ request()->routeIs('parts.*') ? request('search') : '' }}"
                       class="w-full bg-surface-container-low border-none rounded-lg py-2 pl-10 pr-4 text-sm focus:ring-2 focus:ring-primary/20 placeholder:text-on-surface-variant/50"
                       placeholder="Search parts by name, SKU..."
                       type="text"/>
            </form>
        </div>

        <!-- Right: notifications + user -->
        <div class="flex items-center gap-3 md:gap-6 flex-shrink-0">
            <a href="{{ route('transactions.index') }}"
               class="text-[#43474e] hover:text-[#002045] transition-colors relative"
               title="Recent Activity">
                <span class="material-symbols-outlined">notifications</span>
                <span class="absolute top-0 right-0 w-2 h-2 bg-error rounded-full border-2 border-surface"></span>
            </a>
            <div class="hidden md:block h-8 w-[1px] bg-outline-variant/30"></div>
            <a href="{{ route('settings') }}"
               class="flex items-center gap-2 md:gap-3 group hover:opacity-80 transition-opacity"
               title="Settings">
                <!-- Name + company — hidden on small screens -->
                <div class="text-right hidden md:block">
                    <p class="text-xs font-bold text-primary">{{ auth()->user()->name }}</p>
                    <p class="text-[10px] text-on-surface-variant">{{ auth()->user()->company->name ?? 'Central Hub' }}</p>
                </div>
                <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center overflow-hidden border border-outline-variant/30">
                    @if(auth()->user()->company->logo_url)
                        <img src="{{ auth()->user()->company->logo_url }}" alt="Company Logo" class="w-full h-full object-contain"/>
                    @else
                        <span class="material-symbols-outlined text-xl text-primary">account_circle</span>
                    @endif
                </div>
            </a>
        </div>
    </header>

    <!-- ═══════════════════════════════════════════════════════
         MAIN CONTENT
    ═══════════════════════════════════════════════════════ -->
    <main class="lg:ml-64 pt-20 pb-24 lg:pb-12 px-4 md:px-8 min-h-screen">
        @yield('content')
    </main>

    <!-- ═══════════════════════════════════════════════════════
         MOBILE BOTTOM NAV BAR (visible on mobile only)
    ═══════════════════════════════════════════════════════ -->
    <nav class="lg:hidden fixed bottom-0 left-0 right-0 z-30 bg-[#ededf2] border-t border-outline-variant/20
                flex items-center justify-around h-16 px-2 safe-area-inset-bottom shadow-[0_-4px_16px_rgba(0,0,0,0.06)]">
        <a href="{{ route('dashboard') }}"
           class="flex flex-col items-center gap-0.5 px-3 py-2 rounded-xl transition-all {{ request()->routeIs('dashboard') ? 'text-primary' : 'text-on-surface-variant' }}">
            <span class="material-symbols-outlined text-xl" style="{{ request()->routeIs('dashboard') ? 'font-variation-settings: FILL 1, wght 600, GRAD 0, opsz 24' : '' }}">dashboard</span>
            <span class="text-[9px] font-bold tracking-wide">Home</span>
        </a>
        <a href="{{ route('parts.index') }}"
           class="flex flex-col items-center gap-0.5 px-3 py-2 rounded-xl transition-all {{ request()->routeIs('parts.*') ? 'text-primary' : 'text-on-surface-variant' }}">
            <span class="material-symbols-outlined text-xl">inventory_2</span>
            <span class="text-[9px] font-bold tracking-wide">Parts</span>
        </a>
        <a href="{{ route('transactions.index') }}"
           class="flex flex-col items-center gap-0.5 px-3 py-2 rounded-xl transition-all {{ request()->routeIs('transactions.*') ? 'text-primary' : 'text-on-surface-variant' }}">
            <span class="material-symbols-outlined text-xl">swap_horiz</span>
            <span class="text-[9px] font-bold tracking-wide">Activity</span>
        </a>
        <a href="{{ route('suppliers.index') }}"
           class="flex flex-col items-center gap-0.5 px-3 py-2 rounded-xl transition-all {{ request()->routeIs('suppliers.*') ? 'text-primary' : 'text-on-surface-variant' }}">
            <span class="material-symbols-outlined text-xl">conveyor_belt</span>
            <span class="text-[9px] font-bold tracking-wide">Suppliers</span>
        </a>
        <button onclick="openSidebar()"
                class="flex flex-col items-center gap-0.5 px-3 py-2 rounded-xl transition-all text-on-surface-variant">
            <span class="material-symbols-outlined text-xl">menu</span>
            <span class="text-[9px] font-bold tracking-wide">More</span>
        </button>
    </nav>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
    @stack('scripts')

    <script>
        // Trigger page fade-in
        window.addEventListener('load', () => {
            document.body.classList.add('loaded');
        });
        // Backup for fast connections
        setTimeout(() => document.body.classList.add('loaded'), 500);

        function openSidebar() {
            document.getElementById('sidebar').classList.remove('-translate-x-full');
            document.getElementById('sidebar-overlay').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
        function closeSidebar() {
            if (window.innerWidth < 1024) {
                document.getElementById('sidebar').classList.add('-translate-x-full');
                document.getElementById('sidebar-overlay').classList.add('hidden');
                document.body.style.overflow = '';
            }
        }
        // Close sidebar on resize to desktop
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
                document.getElementById('sidebar-overlay').classList.add('hidden');
                document.body.style.overflow = '';
            }
        });
    </script>
</body>
</html>
