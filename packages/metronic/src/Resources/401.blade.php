<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Unauthorized Access | {{ settings()->application_name }}</title>
    <link rel="shortcut icon" href="{{ isset(settings()->favicon) ? Storage::url(settings()->favicon) : asset('isotope/metronic/img/favicon.ico') }}">
    
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    
    <!-- CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { 
            font-family: 'Outfit', sans-serif; 
            background: radial-gradient(circle at top right, rgba(239, 68, 68, 0.05) 0%, transparent 40%),
                        radial-gradient(circle at bottom left, rgba(14, 71, 93, 0.05) 0%, transparent 40%),
                        #ffffff;
        }
        .error-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(254, 226, 226, 0.8);
            box-shadow: 0 25px 50px -12px rgba(220, 38, 38, 0.1);
        }
        .gradient-text-red {
            background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .brand-text {
            background: linear-gradient(135deg, #0E475D 0%, #2563eb 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-6">
    
    <div class="max-w-xl w-full text-center">
        <!-- Logo -->
        <div class="mb-12">
            @if(settings()->company_logo)
                <img src="{{ asset('storage/' . settings()->company_logo) }}" alt="{{ settings()->application_name }}" class="h-12 mx-auto object-contain">
            @else
                <span class="text-2xl font-black brand-text">{{ settings()->application_name }}</span>
            @endif
        </div>

        <!-- Error Illustration/Icon -->
        <div class="relative mb-8">
            <div class="absolute inset-0 flex items-center justify-center blur-3xl opacity-20">
                <div class="w-32 h-32 bg-red-500 rounded-full"></div>
            </div>
            <div class="relative inline-flex items-center justify-center w-24 h-24 rounded-3xl bg-red-50 border border-red-100 shadow-sm transition-transform hover:scale-110 duration-500">
                <svg class="w-12 h-12 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 00-2 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
        </div>

        <!-- Content -->
        <div class="error-card p-10 rounded-[3rem] relative overflow-hidden">
            <!-- Decorative Accent -->
            <div class="absolute top-0 right-0 w-2 h-full bg-red-500"></div>
            
            <span class="inline-block px-4 py-1.5 rounded-full bg-red-50 text-red-600 text-[10px] font-black uppercase tracking-[0.2em] mb-6">Error 401</span>
            <h1 class="text-4xl font-black text-slate-900 mb-4 leading-tight">Access <span class="gradient-text-red">Restricted</span></h1>
            <p class="text-slate-500 text-lg leading-relaxed mb-10">
                Wait, this area is private! It seems you don't have the key (necessary permissions) to enter this room. Let's get you back to safe ground.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ url('/') }}" class="w-full sm:w-auto px-8 py-4 bg-red-600 hover:bg-red-700 text-white font-bold rounded-2xl transition-all hover:scale-105 active:scale-95 shadow-lg shadow-red-900/10 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Go Back Home
                </a>
                <a href="javascript:history.back()" class="w-full sm:w-auto px-8 py-4 bg-white border border-slate-200 text-slate-600 font-bold rounded-2xl hover:bg-slate-50 transition-all flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Previous Page
                </a>
            </div>
        </div>

        <!-- Footer -->
        <p class="mt-12 text-slate-400 text-xs font-semibold uppercase tracking-widest">
            &copy; {{ date('Y') }} {{ settings()->application_name }}. Securely Powered.
        </p>
    </div>

</body>
</html>