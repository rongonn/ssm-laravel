@extends('isotope::guest')

@section('title', 'Welcome to Style Studio Mart')

@push('css')
<style>
    .hero-section {
        background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), 
                    url('https://images.unsplash.com/photo-1560066984-138dadb4c035?auto=format&fit=crop&q=80&w=2000');
        background-size: cover;
        background-position: center;
        height: 80vh;
        display: flex;
        align-items: center;
        color: white;
    }
    .section-title {
        font-family: 'Playfair Display', serif;
        font-weight: 700;
        margin-bottom: 3rem;
        text-align: center;
        position: relative;
    }
    .section-title::after {
        content: '';
        width: 60px;
        height: 3px;
        background: #009ef7;
        position: absolute;
        bottom: -15px;
        left: 50%;
        transform: translateX(-50%);
    }
    .card {
        border-radius: 1rem;
        border: none;
        transition: transform 0.3s ease;
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    }
    .card:hover {
        transform: translateY(-10px);
    }
    .service-icon {
        width: 60px;
        height: 60px;
        background: #f1faff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.5rem;
        color: #009ef7;
        font-size: 1.5rem;
    }
</style>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
@endpush

@section('content')
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white py-4 sticky-top shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bolder fs-3" href="/">
            <span class="text-primary">STYLE STUDIO</span> MART
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto fw-bold">
                <li class="nav-item"><a class="nav-link px-3" href="#services">Services</a></li>
                <li class="nav-item"><a class="nav-link px-3" href="#products">Products</a></li>
                <li class="nav-item"><a class="nav-link px-3" href="#team">Team</a></li>
                <li class="nav-item"><a class="nav-link px-3" href="#gallery">Gallery</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<header class="hero-section">
    <div class="container text-center">
        <h1 class="display-1 fw-bolder mb-4 animate__animated animate__fadeInDown">Elevate Your Style</h1>
        <p class="fs-4 mb-5 opacity-75 animate__animated animate__fadeInUp">Experience premium salon services and professional products <br> curated just for you.</p>
        <div class="animate__animated animate__fadeInUp animate__delay-1s">
            <a href="#services" class="btn btn-primary btn-lg px-5 py-3 me-3 fw-bold">Our Services</a>
            <a href="#products" class="btn btn-outline-light btn-lg px-5 py-3 fw-bold">Shop Now</a>
        </div>
    </div>
</header>

<!-- Services Section -->
<section id="services" class="py-20 bg-light">
    <div class="container">
        <h2 class="section-title">Our Signature Services</h2>
        <div class="row g-4">
            @foreach($services as $service)
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 p-4">
                    <div class="service-icon">
                        <i class="bi bi-scissors"></i>
                    </div>
                    <h3 class="fw-bold mb-3">{{ $service->name }}</h3>
                    <p class="text-muted">{{ Str::limit($service->description, 100) }}</p>
                    <div class="mt-auto d-flex justify-content-between align-items-center">
                        <span class="fw-bold text-primary">৳{{ $service->price }}</span>
                        <span class="badge bg-light-primary text-primary">{{ $service->duration }}</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Products Section -->
<section id="products" class="py-20">
    <div class="container">
        <h2 class="section-title">Featured Products</h2>
        <div class="row g-4">
            @foreach($products as $product)
            <div class="col-lg-3 col-md-6">
                <div class="card h-100 p-0 overflow-hidden text-center">
                    <img src="{{ $product->image_url ?? 'https://via.placeholder.com/300' }}" class="card-img-top" alt="{{ $product->name }}" style="height: 250px; object-fit: cover;">
                    <div class="p-4">
                        <span class="text-uppercase text-muted small fw-bold">{{ $product->brand }}</span>
                        <h4 class="fw-bold my-2">{{ $product->name }}</h4>
                        <p class="text-primary fw-bold fs-5 mb-0">৳{{ $product->price }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Team Section -->
<section id="team" class="py-20 bg-dark text-white">
    <div class="container">
        <h2 class="section-title text-white">Meet Our Experts</h2>
        <div class="row g-4 text-center">
            @foreach($team as $member)
            <div class="col-lg-3 col-md-6">
                <div class="mb-4 d-inline-block position-relative">
                    <img src="{{ $member->image_url ?? 'https://via.placeholder.com/200' }}" class="rounded-circle shadow" width="180" height="180" style="object-fit: cover;">
                </div>
                <h4 class="fw-bold mb-1">{{ $member->name }}</h4>
                <p class="text-primary fw-bold">{{ $member->role }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Gallery Section -->
<section id="gallery" class="py-20">
    <div class="container">
        <h2 class="section-title">Visual Moments</h2>
        <div class="row g-3">
            @foreach($gallery as $item)
            <div class="col-lg-4 col-md-6">
                <div class="card p-0 overflow-hidden shadow-sm">
                    <img src="{{ $item->image_url }}" class="img-fluid" alt="{{ $item->title }}" style="height: 300px; object-fit: cover;">
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="bg-white py-10 border-top">
    <div class="container text-center">
        <h3 class="fw-bolder mb-4">STYLE STUDIO MART</h3>
        <p class="text-muted mb-8">Premium Salon Experience in the Heart of the City.</p>
        <div class="d-flex justify-content-center gap-4 mb-8">
            <a href="#" class="text-muted fs-4"><i class="bi bi-facebook"></i></a>
            <a href="#" class="text-muted fs-4"><i class="bi bi-instagram"></i></a>
            <a href="#" class="text-muted fs-4"><i class="bi bi-twitter"></i></a>
        </div>
        <p class="text-muted small">&copy; {{ date('Y') }} Style Studio Mart. All rights reserved.</p>
    </div>
</footer>
@endsection
