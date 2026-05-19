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
        html { 
            font-size: 14px; 
            scroll-behavior: smooth;
        }
        body { 
            font-family: 'Inter', sans-serif; 
            opacity: 0; 
            transform: scale(0.98);
            transition: opacity 0.5s cubic-bezier(0.4, 0, 0.2, 1), transform 0.5s cubic-bezier(0.4, 0, 0.2, 1); 
            background-color: #f9f9fe;
        }
        body.loaded { 
            opacity: 1; 
            transform: scale(1);
        }
        h1, h2, h3, .headline { font-family: 'Manrope', sans-serif; }

        /* Premium Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { 
            background: rgba(0, 32, 69, 0.1); 
            border-radius: 10px; 
        }
        ::-webkit-scrollbar-thumb:hover { background: rgba(0, 32, 69, 0.2); }

        .glass-header {
            background: rgba(249, 249, 254, 0.85);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(0, 32, 69, 0.05);
        }
        
        /* Interactive Elements */
        .btn-premium {
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .btn-premium:active { transform: scale(0.95); }

        /* Sidebar drawer transition */
        #sidebar {
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 12px 0 32px -4px rgba(0, 32, 69, 0.03);
        }
        #sidebar-overlay {
            transition: opacity 0.28s ease;
        }
    </style>
</head>
<body class="bg-surface text-on-surface">

    <!-- ═══════════════════════════════════════════════════════
         IMPERSONATION BANNER (Platform Master)
    ═══════════════════════════════════════════════════════ -->
    @if(session()->has('impersonator_id'))
    <div class="fixed top-0 left-0 right-0 z-[60] bg-blue-700 text-white px-4 py-2 flex items-center justify-between shadow-2xl animate-pulse-subtle">
        <div class="flex items-center gap-3">
            <span class="material-symbols-outlined text-sm">security</span>
            <span class="text-[10px] font-black uppercase tracking-[0.2em]">Platform Master Mode: Masquerading as {{ auth()->user()->name }}</span>
        </div>
        <a href="{{ route('super-admin.stop-impersonate') }}" class="flex items-center gap-2 bg-white text-blue-700 px-4 py-1 rounded-full font-black text-[10px] uppercase tracking-widest hover:bg-blue-50 transition-colors">
            <span class="material-symbols-outlined text-sm">logout</span>
            Return to Tower
        </a>
    </div>
    <style>
        /* Pad the header or body when banner is active */
        header { top: 36px !important; }
        #sidebar { padding-top: 36px !important; }
        main { padding-top: calc(5rem + 36px) !important; }
        @keyframes pulse-subtle {
            0% { background-color: #1d4ed8; }
            50% { background-color: #1e40af; }
            100% { background-color: #1d4ed8; }
        }
        .animate-pulse-subtle { animation: pulse-subtle 3s infinite; }
    </style>
    @endif

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
            @if(auth()->user()->isSuperAdmin())
                <a class="flex items-center gap-3 {{ request()->routeIs('super-admin.dashboard') ? 'bg-white text-[#002045] rounded-l-xl ml-2 shadow-sm font-bold' : 'text-[#43474e] hover:text-[#002045] hover:bg-slate-200/50 transition-all rounded-l-xl ml-2' }} px-6 py-3"
                   href="{{ route('super-admin.dashboard') }}" onclick="closeSidebar()">
                    <span class="material-symbols-outlined">analytics</span>
                    <span class="font-['Manrope'] tracking-tight text-sm">Control Tower</span>
                </a>
                <a class="flex items-center gap-3 {{ request()->routeIs('super-admin.companies.create') ? 'bg-white text-[#002045] rounded-l-xl ml-2 shadow-sm font-bold' : 'text-[#43474e] hover:text-[#002045] hover:bg-slate-200/50 transition-all rounded-l-xl ml-2' }} px-6 py-3"
                   href="{{ route('super-admin.companies.create') }}" onclick="closeSidebar()">
                    <span class="material-symbols-outlined">add_business</span>
                    <span class="font-['Manrope'] tracking-tight text-sm">Enterprise Factory</span>
                </a>
            @else
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
                <!-- AI Forecast -->
                <a class="flex items-center gap-3 {{ request()->routeIs('ai.forecast') ? 'bg-white text-[#002045] rounded-l-xl ml-2 shadow-sm font-bold' : 'text-[#43474e] hover:text-[#002045] hover:bg-slate-200/50 transition-all rounded-l-xl ml-2' }} px-6 py-3"
                   href="{{ route('ai.forecast') }}" onclick="closeSidebar()">
                    <span class="material-symbols-outlined" style="background: linear-gradient(135deg,#7c3aed,#4f46e5); -webkit-background-clip:text; -webkit-text-fill-color:transparent">auto_graph</span>
                    <span class="font-['Manrope'] tracking-tight text-sm">AI Forecast</span>
                    <span class="ml-auto text-[9px] bg-violet-100 text-violet-600 font-black px-1.5 py-0.5 rounded-full">AI</span>
                </a>
            @endif
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
            @if(auth()->user()->isCompanyAdmin())
            <form method="GET" action="{{ route('parts.index') }}" class="relative hidden sm:block w-full max-w-xs md:max-w-sm">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-lg">search</span>
                <input name="search"
                       value="{{ request()->routeIs('parts.*') ? request('search') : '' }}"
                       class="w-full bg-surface-container-low border-none rounded-lg py-2 pl-10 pr-4 text-sm focus:ring-2 focus:ring-primary/20 placeholder:text-on-surface-variant/50"
                       placeholder="Search parts by name, SKU..."
                       type="text"/>
            </form>
            @endif
        </div>

        <!-- Right: notifications + user -->
        <div class="flex items-center gap-3 md:gap-6 flex-shrink-0">
            @if(auth()->user()->isCompanyAdmin())
            <a href="{{ route('transactions.index') }}"
               class="text-[#43474e] hover:text-[#002045] transition-colors relative"
               title="Recent Activity">
                <span class="material-symbols-outlined">notifications</span>
                <span class="absolute top-0 right-0 w-2 h-2 bg-error rounded-full border-2 border-surface"></span>
            </a>
            @endif
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
                    @if(auth()->user()->company && auth()->user()->company->logo_url)
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
        
        @if(auth()->user()->isSuperAdmin())
            <a href="{{ route('super-admin.dashboard') }}"
               class="flex flex-col items-center gap-0.5 px-3 py-2 rounded-xl transition-all {{ request()->routeIs('super-admin.dashboard') ? 'text-primary' : 'text-on-surface-variant' }}">
                <span class="material-symbols-outlined text-xl" style="{{ request()->routeIs('super-admin.dashboard') ? 'font-variation-settings: FILL 1, wght 600, GRAD 0, opsz 24' : '' }}">analytics</span>
                <span class="text-[9px] font-bold tracking-wide">Tower</span>
            </a>
            <a href="{{ route('super-admin.companies.create') }}"
               class="flex flex-col items-center gap-0.5 px-3 py-2 rounded-xl transition-all {{ request()->routeIs('super-admin.companies.create') ? 'text-primary' : 'text-on-surface-variant' }}">
                <span class="material-symbols-outlined text-xl">add_business</span>
                <span class="text-[9px] font-bold tracking-wide">Onboard</span>
            </a>
        @else
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
        @endif

        <button onclick="openSidebar()"
                class="flex flex-col items-center gap-0.5 px-3 py-2 rounded-xl transition-all text-on-surface-variant">
            <span class="material-symbols-outlined text-xl">menu</span>
            <span class="text-[9px] font-bold tracking-wide">More</span>
        </button>
    </nav>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
    @stack('scripts')

    @if(auth()->check() && !auth()->user()->isSuperAdmin())
    <!-- ═══════════════════════════════════════════════════════
         NOVA AI CHAT WIDGET
    ═══════════════════════════════════════════════════════ -->
    <style>
        #nova-bubble { transition: all .3s cubic-bezier(.4,0,.2,1); }
        #nova-panel  { transition: all .3s cubic-bezier(.4,0,.2,1); transform-origin: bottom right; }
        #nova-panel.hidden { transform: scale(0.85); opacity:0; pointer-events:none; }
        #nova-panel.open   { transform: scale(1);    opacity:1; pointer-events:all; }
        .nova-msg { animation: novaSlide .25s ease; }
        @keyframes novaSlide { from { opacity:0; transform:translateY(6px); } to { opacity:1; transform:translateY(0); } }
        #nova-messages::-webkit-scrollbar { width:4px; }
        #nova-messages::-webkit-scrollbar-thumb { background:rgba(0,32,69,.15); border-radius:4px; }
        .nova-typing span { display:inline-block; width:6px; height:6px; border-radius:50%; background:#7c3aed; margin:0 1px;
            animation: novaBounce 1.2s infinite; }
        .nova-typing span:nth-child(2) { animation-delay:.2s; }
        .nova-typing span:nth-child(3) { animation-delay:.4s; }
        @keyframes novaBounce { 0%,80%,100% { transform:translateY(0); } 40% { transform:translateY(-5px); } }
    </style>

    <!-- Bubble button -->
    <button id="nova-bubble"
            onclick="toggleNova()"
            class="fixed bottom-20 right-5 lg:bottom-6 lg:right-6 z-50
                   w-14 h-14 rounded-full shadow-2xl
                   bg-gradient-to-br from-violet-600 to-indigo-700
                   flex items-center justify-center
                   hover:scale-110 active:scale-95 transition-all"
            title="Ask Nova AI">
        <span id="nova-icon" class="material-symbols-outlined text-white text-2xl">smart_toy</span>
        <span id="nova-close-icon" class="material-symbols-outlined text-white text-2xl hidden">close</span>
        <!-- Pulse ring -->
        <span class="absolute w-full h-full rounded-full bg-violet-500 opacity-30 animate-ping"></span>
    </button>

    <!-- Chat panel -->
    <div id="nova-panel"
         class="hidden fixed bottom-36 right-5 lg:bottom-24 lg:right-6 z-50
                w-[22rem] max-w-[92vw] rounded-2xl shadow-2xl overflow-hidden
                border border-violet-200
                flex flex-col bg-white">

        <!-- Header -->
        <div class="bg-gradient-to-r from-[#0d1b3e] to-[#1a2e6e] px-4 py-3 flex items-center gap-3">
            <div class="w-8 h-8 rounded-full bg-violet-500/30 border border-violet-400/40 flex items-center justify-center">
                <span class="material-symbols-outlined text-violet-300 text-lg">smart_toy</span>
            </div>
            <div class="flex-1">
                <p class="text-white font-bold text-sm">Nova</p>
                <p class="text-violet-300 text-xs">AI Inventory Assistant</p>
            </div>
            <a href="{{ route('ai.forecast') }}" title="Open Forecast Dashboard"
               class="text-violet-300 hover:text-white transition-colors">
                <span class="material-symbols-outlined text-lg">auto_graph</span>
            </a>
        </div>

        <!-- Messages -->
        <div id="nova-messages" class="flex-1 overflow-y-auto p-4 space-y-3 min-h-[240px] max-h-[340px] bg-gray-50">
            <!-- Welcome -->
            <div class="nova-msg flex items-start gap-2">
                <div class="w-6 h-6 rounded-full bg-violet-100 flex items-center justify-center flex-shrink-0">
                    <span class="material-symbols-outlined text-violet-600 text-sm">smart_toy</span>
                </div>
                <div class="bg-white rounded-2xl rounded-tl-none px-3 py-2 text-sm text-gray-800 shadow-sm border border-gray-100 max-w-[85%]">
                    Hi {{ auth()->user()->name }}! 👋 I'm <strong>Nova</strong>, your AI inventory assistant.<br/>
                    Ask me anything — stock levels, supplier info, or what to reorder next!
                </div>
            </div>
        </div>

        <!-- Quick prompts -->
        <div id="nova-quick" class="px-3 py-2 flex gap-2 overflow-x-auto border-t border-gray-100 bg-white">
            <button onclick="sendQuick('Which parts are running low on stock?')"
                class="flex-shrink-0 text-xs bg-violet-50 text-violet-700 font-semibold px-3 py-1.5 rounded-full hover:bg-violet-100 transition-colors whitespace-nowrap">
                🔴 Low stock?
            </button>
            <button onclick="sendQuick('Which supplier has the highest rating?')"
                class="flex-shrink-0 text-xs bg-violet-50 text-violet-700 font-semibold px-3 py-1.5 rounded-full hover:bg-violet-100 transition-colors whitespace-nowrap">
                ⭐ Top supplier
            </button>
            <button onclick="sendQuick('What are the top 3 most consumed parts this month?')"
                class="flex-shrink-0 text-xs bg-violet-50 text-violet-700 font-semibold px-3 py-1.5 rounded-full hover:bg-violet-100 transition-colors whitespace-nowrap">
                📊 Top consumed
            </button>
        </div>

        <!-- Input -->
        <div class="px-3 py-3 border-t border-gray-100 bg-white flex gap-2 items-end">
            <textarea id="nova-input"
                rows="1"
                maxlength="500"
                placeholder="Ask about your inventory…"
                class="flex-1 resize-none bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-sm focus:ring-2 focus:ring-violet-300 focus:border-violet-300 outline-none leading-relaxed"
                onkeydown="if(event.key==='Enter'&&!event.shiftKey){event.preventDefault();sendNova();}"></textarea>
            <button onclick="sendNova()"
                id="nova-send-btn"
                class="flex-shrink-0 w-9 h-9 rounded-xl bg-gradient-to-br from-violet-600 to-indigo-700 flex items-center justify-center hover:scale-105 active:scale-95 transition-all shadow">
                <span class="material-symbols-outlined text-white text-lg">send</span>
            </button>
        </div>
    </div>

    <script>
    let novaOpen = false;

    function toggleNova() {
        novaOpen = !novaOpen;
        const panel  = document.getElementById('nova-panel');
        const icon   = document.getElementById('nova-icon');
        const close  = document.getElementById('nova-close-icon');
        if (novaOpen) {
            panel.classList.remove('hidden');
            requestAnimationFrame(() => panel.classList.add('open'));
            panel.classList.remove('open'); // reset for animation
            setTimeout(() => panel.classList.add('open'), 10);
            icon.classList.add('hidden');
            close.classList.remove('hidden');
            document.getElementById('nova-input').focus();
        } else {
            panel.classList.remove('open');
            setTimeout(() => panel.classList.add('hidden'), 300);
            icon.classList.remove('hidden');
            close.classList.add('hidden');
        }
    }

    function sendQuick(text) {
        document.getElementById('nova-input').value = text;
        sendNova();
    }

    async function sendNova() {
        const input = document.getElementById('nova-input');
        const msg   = input.value.trim();
        if (!msg) return;

        appendMsg(msg, 'user');
        input.value = '';
        input.style.height = 'auto';

        // Hide quick prompts after first message
        document.getElementById('nova-quick').style.display = 'none';

        // Show typing indicator
        const typingId = 'typing-' + Date.now();
        appendTyping(typingId);

        try {
            const res = await fetch('{{ route("ai.chat") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ message: msg })
            });
            const data = await res.json();
            removeTyping(typingId);
            appendMsg(data.reply || 'Sorry, something went wrong.', 'nova');
        } catch(e) {
            removeTyping(typingId);
            appendMsg('Connection error. Please try again.', 'nova');
        }
    }

    function appendMsg(text, who) {
        const msgs = document.getElementById('nova-messages');
        const div  = document.createElement('div');
        div.className = 'nova-msg flex items-start gap-2' + (who === 'user' ? ' justify-end' : '');

        if (who === 'user') {
            div.innerHTML = `
                <div class="bg-gradient-to-br from-violet-600 to-indigo-700 text-white rounded-2xl rounded-tr-none px-3 py-2 text-sm max-w-[85%] whitespace-pre-wrap">${escHtml(text)}</div>
                <div class="w-6 h-6 rounded-full bg-primary flex items-center justify-center flex-shrink-0">
                    <span class="material-symbols-outlined text-white text-sm">person</span>
                </div>`;
        } else {
            // Format markdown-ish bullets
            const formatted = escHtml(text)
                .replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>')
                .replace(/^- (.+)/gm, '• $1');
            div.innerHTML = `
                <div class="w-6 h-6 rounded-full bg-violet-100 flex items-center justify-center flex-shrink-0">
                    <span class="material-symbols-outlined text-violet-600 text-sm">smart_toy</span>
                </div>
                <div class="bg-white rounded-2xl rounded-tl-none px-3 py-2 text-sm text-gray-800 shadow-sm border border-gray-100 max-w-[85%] whitespace-pre-wrap leading-relaxed">${formatted}</div>`;
        }
        msgs.appendChild(div);
        msgs.scrollTop = msgs.scrollHeight;
    }

    function appendTyping(id) {
        const msgs = document.getElementById('nova-messages');
        const div  = document.createElement('div');
        div.id        = id;
        div.className = 'nova-msg flex items-start gap-2';
        div.innerHTML = `
            <div class="w-6 h-6 rounded-full bg-violet-100 flex items-center justify-center flex-shrink-0">
                <span class="material-symbols-outlined text-violet-600 text-sm">smart_toy</span>
            </div>
            <div class="bg-white rounded-2xl rounded-tl-none px-3 py-2 shadow-sm border border-gray-100">
                <div class="nova-typing"><span></span><span></span><span></span></div>
            </div>`;
        msgs.appendChild(div);
        msgs.scrollTop = msgs.scrollHeight;
    }

    function removeTyping(id) {
        const el = document.getElementById(id);
        if (el) el.remove();
    }

    function escHtml(str) {
        return str.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    // Auto-resize textarea
    document.getElementById('nova-input')?.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = Math.min(this.scrollHeight, 100) + 'px';
    });
    </script>
    @endif

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
