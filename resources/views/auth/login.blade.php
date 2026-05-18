<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>AssetNova — Sign In</title>
    <link rel="icon" type="image/png" href="/images/assetnova-logo.png"/>
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800;900&family=Inter:wght@400;500;600&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&display=swap" rel="stylesheet"/>
    <script>
    tailwind.config = {
        theme: { extend: {
            fontFamily: { manrope: ['Manrope','sans-serif'] },
            colors: {
                primary:   '#002045',
                'primary-container': '#1a365d',
                'surface':            '#f9f9fe',
                'surface-container':  '#ededf2',
                'on-surface-variant': '#43474e',
                'outline-variant':    '#c4c6cf',
                error:     '#ba1a1a',
            }
        }}
    }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        input:-webkit-autofill {
            -webkit-box-shadow: 0 0 0 30px #f9f9fe inset !important;
            -webkit-text-fill-color: #002045 !important;
        }
    </style>
</head>
<body class="min-h-screen bg-surface flex">

    <!-- Left panel — branding -->
    <div class="hidden lg:flex w-2/5 bg-primary flex-col justify-between p-12">
        <!-- Logo -->
        <div class="flex items-center gap-3">
            <img src="/images/assetnova-logo.png" alt="AssetNova" class="w-10 h-10 object-contain"/>
            <span class="text-white font-bold text-lg font-[Manrope]">AssetNova</span>
        </div>

        <!-- Center copy -->
        <div>
            <h2 class="text-4xl font-black text-white font-[Manrope] leading-tight mb-4">
                Industrial<br/>Inventory,<br/>Reimagined.
            </h2>
            <p class="text-white/50 text-sm leading-relaxed max-w-xs">
                Real-time asset visibility, procurement intelligence, and multi-company control — all in one place.
            </p>
        </div>

        <!-- Bottom tagline -->
        <p class="text-white/20 text-xs font-medium">Digital Foreman · v1.0</p>
    </div>

    <!-- Right panel — form -->
    <div class="flex-1 flex items-center justify-center p-8">
        <div class="w-full max-w-sm">

            <!-- Mobile logo -->
            <div class="flex items-center gap-2 mb-10 lg:hidden">
                <img src="/images/assetnova-logo.png" alt="AssetNova" class="w-8 h-8 object-contain"/>
                <span class="text-primary font-bold font-[Manrope]">AssetNova</span>
            </div>

            <h1 class="text-2xl font-black text-primary font-[Manrope] mb-1">Sign in</h1>
            <p class="text-on-surface-variant text-sm mb-8">Access your company's inventory dashboard</p>

            <!-- Error -->
            @if($errors->any())
            <div class="mb-6 flex items-start gap-2.5 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">
                <span class="material-symbols-outlined text-red-500 text-sm mt-0.5">error</span>
                <div>@foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach</div>
            </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}" class="space-y-4">
                @csrf

                <!-- Email -->
                <div>
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1.5">Email</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-outline-variant text-sm">mail</span>
                        <input type="email" name="email" value="{{ old('email') }}" required
                            placeholder="you@company.in"
                            class="w-full bg-surface-container border-none rounded-xl py-3 pl-10 pr-4 text-sm text-primary placeholder-outline-variant focus:ring-2 focus:ring-primary/20"/>
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1.5">Password</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-outline-variant text-sm">lock</span>
                        <input type="password" name="password" required
                            placeholder="••••••••"
                            class="w-full bg-surface-container border-none rounded-xl py-3 pl-10 pr-4 text-sm text-primary placeholder-outline-variant focus:ring-2 focus:ring-primary/20"/>
                    </div>
                </div>

                <!-- Remember -->
                <div class="flex items-center gap-2 pt-1">
                    <input type="checkbox" name="remember" id="remember"
                        class="rounded bg-surface-container border-outline-variant text-primary focus:ring-primary/20"/>
                    <label for="remember" class="text-sm text-on-surface-variant">Keep me signed in</label>
                </div>

                <!-- Submit -->
                <button type="submit"
                    class="w-full bg-primary text-white font-bold py-3.5 rounded-xl hover:bg-primary-container transition-colors text-sm flex items-center justify-center gap-2 mt-2 shadow-sm shadow-primary/20">
                    <span class="material-symbols-outlined text-sm">login</span>
                    Sign In
                </button>
            </form>

            <p class="text-center text-on-surface-variant text-xs mt-6">
                New to AssetNova?
                <a href="{{ route('register') }}" class="text-primary font-semibold hover:underline">Create account</a>
            </p>

            <p class="text-center text-outline-variant text-xs mt-4">© 2026 AssetNova · Digital Foreman</p>
        </div>
    </div>

</body>
</html>

