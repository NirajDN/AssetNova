<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>AssetNova — Create Account</title>
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
                accent:    '#0057b8',
                error:     '#ba1a1a',
            }
        }}
    }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        input:-webkit-autofill {
            -webkit-box-shadow: 0 0 0 30px #ededf2 inset !important;
            -webkit-text-fill-color: #002045 !important;
        }
        .step { display: none; }
        .step.active { display: block; }

        /* Logo drop zone */
        #drop-zone { transition: border-color .2s, background .2s; }
        #drop-zone.drag-over { border-color: #0057b8; background: #eef4ff; }

        /* Progress bar */
        #progress-bar { transition: width .4s ease; }
    </style>
</head>
<body class="min-h-screen bg-surface flex">

    <!-- Left panel — branding -->
    <div class="hidden lg:flex w-2/5 bg-primary flex-col justify-between p-12">
        <div class="flex items-center gap-3">
            <img src="/images/assetnova-logo.png" alt="AssetNova" class="w-10 h-10 object-contain"/>
            <span class="text-white font-bold text-lg font-[Manrope]">AssetNova</span>
        </div>
        <div>
            <h2 class="text-4xl font-black text-white font-[Manrope] leading-tight mb-4">
                Set up your<br/>company in<br/>minutes.
            </h2>
            <p class="text-white/50 text-sm leading-relaxed max-w-xs">
                Start tracking inventory, managing assets, and gaining full visibility across your operations — free.
            </p>
        </div>
        <p class="text-white/20 text-xs font-medium">Digital Foreman · v1.0</p>
    </div>

    <!-- Right panel — form -->
    <div class="flex-1 flex items-center justify-center p-8">
        <div class="w-full max-w-sm">

            <!-- Mobile logo -->
            <div class="flex items-center gap-2 mb-8 lg:hidden">
                <img src="/images/assetnova-logo.png" alt="AssetNova" class="w-8 h-8 object-contain"/>
                <span class="text-primary font-bold font-[Manrope]">AssetNova</span>
            </div>

            <!-- Step indicator -->
            <div class="flex items-center gap-2 mb-6">
                <div id="dot-1" class="w-2 h-2 rounded-full bg-primary transition-all duration-300"></div>
                <div id="dot-2" class="w-2 h-2 rounded-full bg-outline-variant transition-all duration-300"></div>
                <div class="flex-1 h-px bg-outline-variant ml-1"></div>
                <span id="step-label" class="text-xs font-semibold text-on-surface-variant uppercase tracking-widest">Step 1 of 2</span>
            </div>

            <!-- Heading -->
            <h1 class="text-2xl font-black text-primary font-[Manrope] mb-1" id="form-title">Your Company</h1>
            <p class="text-on-surface-variant text-sm mb-6" id="form-subtitle">Tell us a bit about your organisation</p>

            <!-- Errors -->
            @if($errors->any())
            <div class="mb-5 flex items-start gap-2.5 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">
                <span class="material-symbols-outlined text-red-500 text-sm mt-0.5">error</span>
                <div>@foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach</div>
            </div>
            @endif

            <!-- Success flash -->
            @if(session('status'))
            <div class="mb-5 flex items-start gap-2.5 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm">
                <span class="material-symbols-outlined text-green-500 text-sm mt-0.5">check_circle</span>
                <p>{{ session('status') }}</p>
            </div>
            @endif

            <form method="POST" action="{{ route('register.post') }}" enctype="multipart/form-data" id="register-form">
                @csrf

                <!-- ═══ STEP 1 — Company Info ═══ -->
                <div class="step active" id="step-1">

                    <!-- Company Name (required) -->
                    <div class="mb-4">
                        <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1.5">
                            Company Name <span class="text-error">*</span>
                        </label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-outline-variant text-sm">business</span>
                            <input type="text" name="company_name" id="company_name"
                                value="{{ old('company_name') }}"
                                placeholder="Acme Industries Ltd."
                                class="w-full bg-surface-container border-none rounded-xl py-3 pl-10 pr-4 text-sm text-primary placeholder-outline-variant focus:ring-2 focus:ring-primary/20"/>
                        </div>
                    </div>

                    <!-- Industry (optional) -->
                    <div class="mb-4">
                        <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1.5">
                            Industry <span class="text-outline-variant font-normal normal-case tracking-normal">(optional)</span>
                        </label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-outline-variant text-sm">factory</span>
                            <select name="industry"
                                class="w-full bg-surface-container border-none rounded-xl py-3 pl-10 pr-4 text-sm text-primary focus:ring-2 focus:ring-primary/20 appearance-none">
                                <option value="">Select industry…</option>
                                <option value="Manufacturing" {{ old('industry') == 'Manufacturing' ? 'selected' : '' }}>Manufacturing</option>
                                <option value="Automotive" {{ old('industry') == 'Automotive' ? 'selected' : '' }}>Automotive</option>
                                <option value="Mining" {{ old('industry') == 'Mining' ? 'selected' : '' }}>Mining</option>
                                <option value="Construction" {{ old('industry') == 'Construction' ? 'selected' : '' }}>Construction</option>
                                <option value="Oil & Gas" {{ old('industry') == 'Oil & Gas' ? 'selected' : '' }}>Oil &amp; Gas</option>
                                <option value="Food & Beverage" {{ old('industry') == 'Food & Beverage' ? 'selected' : '' }}>Food &amp; Beverage</option>
                                <option value="Pharmaceuticals" {{ old('industry') == 'Pharmaceuticals' ? 'selected' : '' }}>Pharmaceuticals</option>
                                <option value="Logistics" {{ old('industry') == 'Logistics' ? 'selected' : '' }}>Logistics</option>
                                <option value="Other" {{ old('industry') == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                    </div>

                    <!-- Logo (optional) -->
                    <div class="mb-6">
                        <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1.5">
                            Company Logo <span class="text-outline-variant font-normal normal-case tracking-normal">(optional)</span>
                        </label>
                        <div id="drop-zone"
                            class="border-2 border-dashed border-outline-variant rounded-xl p-5 text-center cursor-pointer hover:border-accent transition-colors"
                            onclick="document.getElementById('logo').click()">
                            <div id="drop-placeholder">
                                <span class="material-symbols-outlined text-outline-variant text-3xl mb-1">upload_file</span>
                                <p class="text-xs text-on-surface-variant">Drag &amp; drop or <span class="text-accent font-semibold">browse</span></p>
                                <p class="text-xs text-outline-variant mt-0.5">PNG, JPG, SVG · max 2 MB</p>
                            </div>
                            <div id="drop-preview" class="hidden flex-col items-center gap-2">
                                <img id="logo-preview-img" src="" alt="Logo preview" class="h-14 w-14 object-contain rounded-lg border border-outline-variant"/>
                                <p id="logo-preview-name" class="text-xs text-on-surface-variant truncate max-w-full"></p>
                                <button type="button" onclick="clearLogo(event)"
                                    class="text-xs text-error underline">Remove</button>
                            </div>
                        </div>
                        <input type="file" name="logo" id="logo" accept="image/*" class="hidden"/>
                    </div>

                    <!-- Next -->
                    <button type="button" id="next-btn"
                        class="w-full bg-primary text-white font-bold py-3.5 rounded-xl hover:bg-primary-container transition-colors text-sm flex items-center justify-center gap-2 shadow-sm shadow-primary/20">
                        Continue
                        <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </button>
                </div>

                <!-- ═══ STEP 2 — Admin Account ═══ -->
                <div class="step" id="step-2">

                    <!-- Full Name (required) -->
                    <div class="mb-4">
                        <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1.5">
                            Your Name <span class="text-error">*</span>
                        </label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-outline-variant text-sm">person</span>
                            <input type="text" name="name" id="admin_name"
                                value="{{ old('name') }}"
                                placeholder="John Smith"
                                class="w-full bg-surface-container border-none rounded-xl py-3 pl-10 pr-4 text-sm text-primary placeholder-outline-variant focus:ring-2 focus:ring-primary/20"/>
                        </div>
                    </div>

                    <!-- Email (required) -->
                    <div class="mb-4">
                        <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1.5">
                            Work Email <span class="text-error">*</span>
                        </label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-outline-variant text-sm">mail</span>
                            <input type="email" name="email" id="admin_email"
                                value="{{ old('email') }}"
                                placeholder="you@company.in"
                                class="w-full bg-surface-container border-none rounded-xl py-3 pl-10 pr-4 text-sm text-primary placeholder-outline-variant focus:ring-2 focus:ring-primary/20"/>
                        </div>
                    </div>

                    <!-- Password (required) -->
                    <div class="mb-4">
                        <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1.5">
                            Password <span class="text-error">*</span>
                        </label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-outline-variant text-sm">lock</span>
                            <input type="password" name="password" id="admin_password"
                                placeholder="Min 8 characters"
                                class="w-full bg-surface-container border-none rounded-xl py-3 pl-10 pr-12 text-sm text-primary placeholder-outline-variant focus:ring-2 focus:ring-primary/20"/>
                            <button type="button" onclick="togglePwd()" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-outline-variant hover:text-primary">
                                <span id="pwd-icon" class="material-symbols-outlined text-sm">visibility</span>
                            </button>
                        </div>
                        <!-- Strength meter -->
                        <div class="mt-2 h-1 w-full bg-outline-variant/30 rounded-full overflow-hidden">
                            <div id="strength-bar" class="h-full rounded-full bg-error transition-all duration-300 w-0"></div>
                        </div>
                        <p id="strength-label" class="text-xs text-outline-variant mt-1"></p>
                    </div>

                    <!-- Confirm password (optional — skippable) -->
                    <div class="mb-5">
                        <label class="block text-xs font-bold text-on-surface-variant uppercase tracking-widest mb-1.5">
                            Confirm Password <span class="text-outline-variant font-normal normal-case tracking-normal">(optional)</span>
                        </label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-outline-variant text-sm">lock_reset</span>
                            <input type="password" name="password_confirmation" id="admin_password_confirm"
                                placeholder="Repeat password"
                                class="w-full bg-surface-container border-none rounded-xl py-3 pl-10 pr-4 text-sm text-primary placeholder-outline-variant focus:ring-2 focus:ring-primary/20"/>
                        </div>
                    </div>

                    <!-- Back + Submit -->
                    <div class="flex gap-3">
                        <button type="button" id="back-btn"
                            class="flex-none bg-surface-container text-primary font-bold py-3.5 px-5 rounded-xl hover:bg-outline-variant/30 transition-colors text-sm flex items-center gap-1">
                            <span class="material-symbols-outlined text-sm">arrow_back</span>
                        </button>
                        <button type="submit"
                            class="flex-1 bg-primary text-white font-bold py-3.5 rounded-xl hover:bg-primary-container transition-colors text-sm flex items-center justify-center gap-2 shadow-sm shadow-primary/20">
                            <span class="material-symbols-outlined text-sm">rocket_launch</span>
                            Create Account
                        </button>
                    </div>
                </div>
            </form>

            <p class="text-center text-on-surface-variant text-xs mt-8">
                Already have an account?
                <a href="{{ route('login') }}" class="text-accent font-semibold hover:underline">Sign in</a>
            </p>

            <p class="text-center text-outline-variant text-xs mt-4">© 2026 AssetNova · Digital Foreman</p>
        </div>
    </div>

<script>
// ── Step navigation ──────────────────────────────────────────────
const steps     = document.querySelectorAll('.step');
const dot1      = document.getElementById('dot-1');
const dot2      = document.getElementById('dot-2');
const stepLabel = document.getElementById('step-label');
const formTitle = document.getElementById('form-title');
const formSub   = document.getElementById('form-subtitle');

function goToStep(n) {
    steps.forEach((s, i) => s.classList.toggle('active', i + 1 === n));
    dot1.className = n >= 1
        ? 'w-2.5 h-2.5 rounded-full bg-primary transition-all duration-300'
        : 'w-2 h-2 rounded-full bg-outline-variant transition-all duration-300';
    dot2.className = n >= 2
        ? 'w-2.5 h-2.5 rounded-full bg-primary transition-all duration-300'
        : 'w-2 h-2 rounded-full bg-outline-variant transition-all duration-300';
    stepLabel.textContent = `Step ${n} of 2`;
    if (n === 1) {
        formTitle.textContent = 'Your Company';
        formSub.textContent   = 'Tell us a bit about your organisation';
    } else {
        formTitle.textContent = 'Your Account';
        formSub.textContent   = 'Set up admin login credentials';
    }
}

document.getElementById('next-btn').addEventListener('click', () => {
    const companyName = document.getElementById('company_name').value.trim();
    if (!companyName) {
        document.getElementById('company_name').focus();
        document.getElementById('company_name').classList.add('ring-2','ring-error');
        return;
    }
    document.getElementById('company_name').classList.remove('ring-2','ring-error');
    goToStep(2);
});

document.getElementById('back-btn').addEventListener('click', () => goToStep(1));

// If server returned errors, jump to step 2 immediately
@if($errors->has('email') || $errors->has('password') || $errors->has('name'))
    document.addEventListener('DOMContentLoaded', () => goToStep(2));
@endif

// ── Logo drag & drop ─────────────────────────────────────────────
const logoInput    = document.getElementById('logo');
const dropZone     = document.getElementById('drop-zone');
const dropPlaceholder = document.getElementById('drop-placeholder');
const dropPreview  = document.getElementById('drop-preview');
const previewImg   = document.getElementById('logo-preview-img');
const previewName  = document.getElementById('logo-preview-name');

logoInput.addEventListener('change', () => showPreview(logoInput.files[0]));

['dragenter','dragover'].forEach(ev => {
    dropZone.addEventListener(ev, e => { e.preventDefault(); dropZone.classList.add('drag-over'); });
});
['dragleave','drop'].forEach(ev => {
    dropZone.addEventListener(ev, e => { e.preventDefault(); dropZone.classList.remove('drag-over'); });
});
dropZone.addEventListener('drop', e => {
    const file = e.dataTransfer.files[0];
    if (file && file.type.startsWith('image/')) {
        const dt = new DataTransfer();
        dt.items.add(file);
        logoInput.files = dt.files;
        showPreview(file);
    }
});

function showPreview(file) {
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => { previewImg.src = e.target.result; };
    reader.readAsDataURL(file);
    previewName.textContent = file.name;
    dropPlaceholder.classList.add('hidden');
    dropPreview.classList.remove('hidden');
    dropPreview.classList.add('flex');
}

function clearLogo(e) {
    e.stopPropagation();
    logoInput.value = '';
    previewImg.src  = '';
    previewName.textContent = '';
    dropPlaceholder.classList.remove('hidden');
    dropPreview.classList.add('hidden');
    dropPreview.classList.remove('flex');
}

// ── Password strength ─────────────────────────────────────────────
const pwdInput    = document.getElementById('admin_password');
const strengthBar = document.getElementById('strength-bar');
const strengthLbl = document.getElementById('strength-label');

pwdInput.addEventListener('input', () => {
    const v = pwdInput.value;
    let score = 0;
    if (v.length >= 8)  score++;
    if (/[A-Z]/.test(v)) score++;
    if (/[0-9]/.test(v)) score++;
    if (/[^A-Za-z0-9]/.test(v)) score++;
    const widths = ['0%','25%','50%','75%','100%'];
    const colors = ['','#ba1a1a','#f97316','#eab308','#16a34a'];
    const labels = ['','Weak','Fair','Good','Strong'];
    strengthBar.style.width = widths[score];
    strengthBar.style.background = colors[score];
    strengthLbl.textContent = score > 0 ? labels[score] : '';
    strengthLbl.style.color = colors[score];
});

// ── Toggle password visibility ────────────────────────────────────
function togglePwd() {
    const input = document.getElementById('admin_password');
    const icon  = document.getElementById('pwd-icon');
    if (input.type === 'password') { input.type = 'text'; icon.textContent = 'visibility_off'; }
    else                           { input.type = 'password'; icon.textContent = 'visibility'; }
}
</script>
</body>
</html>
