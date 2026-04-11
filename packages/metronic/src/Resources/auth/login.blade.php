@extends('isotope::guest')

@section('title', 'Login')

@push('css')
<link rel="stylesheet" href="{{ asset('isotope/metronic/css/login.css') }}">
<style>
    .lp-brand-logo {
        width: auto !important;
        height: 180px !important;
        max-width: 280px !important;
        object-fit: contain !important;
        filter: drop-shadow(0 16px 40px rgba(59,130,246,0.35)) !important;
    }
    .lp-logo-ring {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 220px;
        height: 220px;
        border-radius: 50%;
        background: radial-gradient(circle at 40% 40%, rgba(255,255,255,0.9), rgba(219,234,254,0.6));
        box-shadow: 0 0 0 12px rgba(59,130,246,0.08), 0 0 0 24px rgba(59,130,246,0.04), 0 20px 60px rgba(59,130,246,0.2);
        margin-bottom: 0.5rem;
        animation: logoBreath 5s ease-in-out infinite alternate;
    }
    .lp-card-logo {
        width: auto !important;
        height: 100px !important;
        max-width: 200px !important;
        object-fit: contain !important;
        background: transparent !important;
        box-shadow: none !important;
        padding: 0 !important;
        margin-bottom: 0.75rem !important;
    }
</style>
@endpush

@section('content')

<div class="lp-wrapper">
    <div class="lp-mobile-header">
        <div class="lp-mh-inner">
            <a href="/">
                <img
                    src="{{ isset(settings()->company_logo) ? Storage::url(settings()->company_logo) : asset('isotope/metronic/img/isotopeit.png') }}"
                    alt="{{ settings()->application_name ?? 'Bioshah' }}"
                    class="lp-mh-logo"
                />
            </a>
            <div class="lp-mh-text">
                <div class="lp-mh-name">{{ settings()->application_name ?? 'Bioshah' }}</div>
                <div class="lp-mh-slogan">{{ settings()->application_slogan ?? 'Premium salon and wellness experience dedicated to redefining beauty standards through artisanal techniques and luxury care.' }}</div>
            </div>
        </div>
        <div class="lp-mh-chips">
            <span class="lp-chip"><span class="lp-chip-dot"></span> Secure & Private</span>
            <span class="lp-chip"><span class="lp-chip-dot"></span> 24/7 Access</span>
        </div>
    </div>

    {{-- ============================================================
         LEFT PANEL — shown only on DESKTOP (>= 768px)
         ============================================================ --}}
    <div class="lp-left">
        <div class="lp-blob lp-blob-1"></div>
        <div class="lp-blob lp-blob-2"></div>
        <div class="lp-blob lp-blob-3"></div>

        <div class="lp-brand">
            <a href="/" class="lp-logo-ring">
                <img
                    src="{{ isset(settings()->company_logo) ? Storage::url(settings()->company_logo) : asset('isotope/metronic/img/isotopeit.png') }}"
                    alt="{{ settings()->application_name ?? 'Bioshah' }}"
                    class="lp-brand-logo"
                />
            </a>
            <div class="lp-app-name">{{ settings()->application_name ?? 'Bioshah' }}</div>

            @if(settings()->application_slogan ?? false)
                <div class="lp-slogan">{{ settings()->application_slogan }}</div>
            @else
                <div class="lp-slogan">Premium salon and wellness experience dedicated to redefining beauty standards through artisanal techniques and luxury care.</div>
            @endif

            <div class="lp-chips">
                <span class="lp-chip"><span class="lp-chip-dot"></span> Secure & Private</span>
                <span class="lp-chip"><span class="lp-chip-dot"></span> 24/7 Access</span>
            </div>
        </div>
    </div>

    {{-- ============================================================
         RIGHT PANEL — Login Form (always visible)
         ============================================================ --}}
    <div class="lp-right">
        <div class="lp-card">

            {{-- Card header --}}
            <div class="lp-card-header">
                <img
                    src="{{ isset(settings()->login_logo) ? Storage::url(settings()->login_logo) : asset('isotope/metronic/img/isotope_p2.png') }}"
                    alt="Logo"
                    class="lp-card-logo"
                />
                <div class="lp-card-title">Welcome Back</div>
                <div class="lp-card-subtitle">
                    Sign in to {{ settings()->application_name ?? 'Bioshah' }}<br>
                    with your credentials
                </div>
            </div>

            {{-- Divider --}}
            <div class="lp-divider">Secure Login</div>

            {{-- Form --}}
            <form action="{{ route(tenant() ? 'login' : 'owner.login') }}" method="post" autocomplete="on">
                @csrf

                {{-- Email --}}
                <div class="lp-form-group">
                    <label class="lp-label" for="lp-email">Email Address</label>
                    <div class="lp-input-wrap">
                        <svg class="lp-input-icon" width="17" height="17" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        <input
                            id="lp-email"
                            type="text"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="Email"
                            class="lp-input"
                            autocomplete="email"
                            required
                        />
                    </div>
                    @if ($errors->get('email'))
                        <div class="lp-error">
                            @foreach ((array) $errors->get('email') as $message)
                                <li>{{ $message }}</li>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Password --}}
                <div class="lp-form-group">
                    <label class="lp-label" for="lp-password">Password</label>
                    <div class="lp-input-wrap">
                        <svg class="lp-input-icon" width="17" height="17" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                        </svg>
                        <input
                            id="lp-password"
                            type="password"
                            name="password"
                            placeholder="Enter your password"
                            class="lp-input"
                            autocomplete="current-password"
                            required
                        />
                    </div>
                    @if ($errors->get('password'))
                        <div class="lp-error">
                            @foreach ((array) $errors->get('password') as $message)
                                <li>{{ $message }}</li>
                            @endforeach
                        </div>
                    @endif
                </div>


                {{-- Submit --}}
                <button type="submit" class="lp-btn">
                    Sign In
                    <svg class="lp-btn-icon" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12h14M12 5l7 7-7 7"/>
                    </svg>
                </button>
            </form>

            {{-- Trust indicators --}}
            <div class="lp-trust">
                <div class="lp-trust-item">
                    <span class="lp-trust-dot"></span> SSL Secured
                </div>
                <div class="lp-trust-item">
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#64748B" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                    </svg>
                    Data Protected
                </div>
            </div>
        </div>
    </div>

</div>

@endsection