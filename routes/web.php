<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MedicineController;
use App\Http\Controllers\Admin\PharmacyController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\OpenClawController;
use App\Http\Controllers\Shop\ShopController;
use App\Http\Controllers\Shop\CartController;
use App\Http\Controllers\Shop\CheckoutController;


require __DIR__.'/auth.php';

Route::middleware(['auth'])->group(function () {

    Route::get('/profile', [ProfileController::class,'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class,'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class,'destroy'])->name('profile.destroy');

    Route::prefix('admin')
        ->name('admin.')
        ->group(function () {

            Route::get('/', [DashboardController::class,'index'])->name('dashboard');
            Route::prefix('openclaw')
                ->name('openclaw.')
                ->middleware('role:admin')
                ->group(function () {

                    Route::get('/', [OpenClawController::class, 'index'])->name('index');
                    Route::get('/status', [OpenClawController::class, 'status'])->name('status');
                    Route::post('/run', [OpenClawController::class, 'run'])->name('run');

                });
            Route::resource('categories', CategoryController::class)->except('show');

            Route::resource('medicines', MedicineController::class);

            Route::post(
                'medicines/{medicine}/adjust-stock',
                [MedicineController::class,'adjustStock']
            )->name('medicines.adjustStock');

            Route::resource('pharmacies', PharmacyController::class);

            Route::resource('orders', OrderController::class)
                ->except(['edit','update','destroy']);

            Route::patch(
                'orders/{order}/status',
                [OrderController::class,'updateStatus']
            )->name('orders.updateStatus');

            Route::get('invoices', [InvoiceController::class,'index'])->name('invoices.index');
            Route::get('invoices/{invoice}', [InvoiceController::class,'show'])->name('invoices.show');
            Route::get('invoices/{invoice}/pdf', [InvoiceController::class,'pdf'])->name('invoices.pdf');

            Route::post(
                'invoices/{invoice}/payments',
                [InvoiceController::class,'registerPayment']
            )->name('invoices.registerPayment');

            Route::prefix('reports')->name('reports.')->group(function () {

                Route::get('sales', [ReportController::class,'sales'])->name('sales');
                Route::get('inventory', [ReportController::class,'inventory'])->name('inventory');
                Route::get('low-stock', [ReportController::class,'lowStock'])->name('low-stock');

            });

            Route::middleware('role:admin')->group(function () {

                Route::resource('users', UserController::class)
                    ->except('show');

            });

        });

});


// ========================================================
// فروشگاه
// ========================================================

Route::prefix('shop')->name('shop.')->group(function () {

    // صفحات عمومی
    Route::get('/', [ShopController::class, 'home'])->name('home');
    Route::get('/products', [ShopController::class, 'products'])->name('products');
    Route::get('/search', [ShopController::class, 'search'])->name('search');
    Route::get('/product/{medicine}', [ShopController::class, 'show'])->name('product');

    // سبد خرید
    Route::get('/cart', [CartController::class, 'index'])->name('cart');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/cart/coupon', [CartController::class, 'applyCoupon'])->name('cart.coupon');

    // ورود
    Route::get('/login', fn() => view('shop.auth.login'))->name('login');

    // علاقه‌مندی‌ها
    Route::get('/wishlist', fn() => view('shop.wishlist.index'))->name('wishlist');

    // صفحات نیازمند لاگین
    Route::middleware('auth')->group(function () {

        Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
        Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

        Route::get('/order/success', fn() => view('shop.checkout.success'))
            ->name('order.success');

        Route::get('/my-orders', fn() => view('shop.orders.index'))
            ->name('orders');

    });

});


// ========================================================
// صفحه اصلی سایت
// ========================================================

// اگر میخواهی صفحه اصلی فروشگاه باشد:
Route::get('/', fn() => redirect()->route('shop.home'));
