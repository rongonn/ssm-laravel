<?php

use Illuminate\Support\Facades\Route;
use SSM\Http\Controllers\Admin\DashboardController as AdminDashboardController;

Route::middleware(['web'])->group(function () {
    Route::get('/', [SSM\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/services', [SSM\Http\Controllers\ServiceController::class, 'index'])->name('services');
    Route::get('/services/{id}', [SSM\Http\Controllers\ServiceController::class, 'show'])->name('services.show');
    Route::get('/products', [SSM\Http\Controllers\ProductController::class, 'index'])->name('products');
    Route::get('/products/{id}', [SSM\Http\Controllers\ProductController::class, 'show'])->name('products.show');
    Route::post('/products/{id}/reviews', [SSM\Http\Controllers\ProductController::class, 'storeReview'])->name('products.reviews.store');
    Route::get('/gallery', [SSM\Http\Controllers\GalleryController::class, 'index'])->name('gallery');
    Route::get('/about', [SSM\Http\Controllers\AboutController::class, 'index'])->name('about');
    Route::get('/about/team/{id}', [SSM\Http\Controllers\AboutController::class, 'teamMember'])->name('about.team');
    Route::get('/contact', [SSM\Http\Controllers\ContactController::class, 'index'])->name('contact');
    Route::post('/contact', [SSM\Http\Controllers\ContactController::class, 'store'])->name('contact.store');
    Route::get('/privacy-policy', [SSM\Http\Controllers\LegalController::class, 'privacy'])->name('privacy');
    Route::get('/terms-of-service', [SSM\Http\Controllers\LegalController::class, 'terms'])->name('terms');
    
    Route::middleware(['auth', 'verified'])->prefix('admin')->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
        
        // Resource Index Pages
        Route::get('/services', [AdminDashboardController::class, 'servicesIndex'])->name('admin.services');
        Route::get('/categories', [AdminDashboardController::class, 'categoriesIndex'])->name('admin.categories');
        Route::get('/products', [AdminDashboardController::class, 'productsIndex'])->name('admin.products');
        Route::get('/team', [AdminDashboardController::class, 'teamIndex'])->name('admin.team');
        Route::get('/testimonials', [AdminDashboardController::class, 'testimonialsIndex'])->name('admin.testimonials');
        Route::get('/gallery', [AdminDashboardController::class, 'galleryIndex'])->name('admin.gallery');
        Route::get('/contacts', [AdminDashboardController::class, 'contactsIndex'])->name('admin.contacts');
        Route::post('/contacts/{id}/read', [AdminDashboardController::class, 'markContactRead'])->name('admin.contacts.read');

        // CRUD routes
        Route::get('/orders', [AdminDashboardController::class, 'ordersIndex'])->name('admin.orders');
        Route::post('/orders/manual', [AdminDashboardController::class, 'manualOrderStore'])->name('admin.orders.manual');
        Route::post('/orders/{id}/manual', [AdminDashboardController::class, 'updateManualOrder'])->name('admin.orders.manual.update');
        Route::post('/orders/{id}/status', [AdminDashboardController::class, 'updateOrderStatus'])->name('admin.orders.status');
        Route::post('/store/{table}', [AdminDashboardController::class, 'store'])->name('admin.store');
        Route::post('/update/{table}/{id}', [AdminDashboardController::class, 'update'])->name('admin.update');
        Route::delete('/delete/{table}/{id}', [AdminDashboardController::class, 'destroy'])->name('admin.delete');
        Route::post('/product/{id}/toggle', [AdminDashboardController::class, 'toggleProductStatus'])->name('admin.products.toggle');
        Route::post('/product/{id}/images/add', [AdminDashboardController::class, 'addProductImage'])->name('admin.products.image.add');
        Route::post('/product/{id}/images/remove', [AdminDashboardController::class, 'removeProductImage'])->name('admin.products.image.remove');
        
        Route::get('/reviews', [AdminDashboardController::class, 'reviewsIndex'])->name('admin.reviews');
        Route::post('/reviews/{id}/toggle', [AdminDashboardController::class, 'toggleReviewStatus'])->name('admin.reviews.toggle');
    });

    // Front-end Product Purchase
    Route::get('/checkout', [SSM\Http\Controllers\ProductController::class, 'checkout'])->name('products.checkout');
    Route::post('/purchase', [SSM\Http\Controllers\ProductController::class, 'purchase'])->name('products.purchase');
});
