<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */
Route::group([], function () {
  $locales = collect(LaravelLocalization::getSupportedLocales())->map(function ($properties, $localeCode) {
    return [
      'code' => $localeCode,
      'native' => $properties['native'],
      'url' => LaravelLocalization::getLocalizedURL($localeCode, null, [], true),
      'emoji' => $properties['emoji'],
    ];
  });
  Inertia::share([
    'locales' => $locales,
    'active_locale_code' => LaravelLocalization::getCurrentLocale(),
  ]);

  Route::get('/', function () {
    if (auth()->check()) {
      if (in_array(auth()->user()->type, ['super admin', 'admin'])) {
        return redirect(route('admin.dashboard'));
      } else if (auth()->user()->type === 'client') {
        return redirect(route('home'));
      }
    }
    return redirect(route('login'));

  });

  require __DIR__ . '/auth.php';

  Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('profile/photo/update', [ProfileController::class, 'updateProfilePhoto'])->name('profile.photo.update');

    // User
    Route::group(['middleware' => 'clients-only'], function () {
      Route::get('home', [HomeController::class, 'index'])->name('home');

      Route::get('user/profile', [ProfileController::class, 'myProfile'])->name('user.profile.me');

    });

    // Admin
    Route::group(['middleware' => 'admins-only', 'prefix' => 'admin'], function () {
      Route::get('dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

      // Products Management
      Route::post('products', [ProductController::class, 'store'])->name('admin.products.store');
      Route::get('products/{product}/show', [ProductController::class, 'show'])->name('admin.products.show');
      Route::get('products/{product}/edit', [ProductController::class, 'edit'])->name('admin.products.edit');
      Route::delete('products/mass-destroy', [ProductController::class, 'massDestroy'])->name('admin.products.massDestroy');
      Route::post('products/photo/update', [ProductController::class, 'updateProductPhotos'])->name('admin.products.photo.update');
      Route::delete('products/images/{image}', [ProductController::class, 'destroyProductPhoto'])->name('admin.products.photo');
      Route::patch('products/{product}', [ProductController::class, 'update'])->name('admin.products.update');
      Route::delete('products/{product}', [ProductController::class, 'destroy'])->name('admin.products.destroy');

      // Brands Management
      Route::get('brands', [BrandController::class, 'index'])->name('admin.brands');
      Route::post('brands', [BrandController::class, 'store'])->name('admin.brands.store');
      Route::get('brands/{brand}/edit', [BrandController::class, 'edit'])->name('admin.brands.edit');
      Route::delete('brands/mass-destroy', [BrandController::class, 'massDestroy'])->name('admin.brands.massDestroy');
      Route::patch('brands/{brand}', [BrandController::class, 'update'])->name('admin.brands.update');
      Route::delete('brands/{brand}', [BrandController::class, 'destroy'])->name('admin.brands.destroy');

      // Categories Management
      Route::get('categories', [CategoryController::class, 'index'])->name('admin.categories');
      Route::post('categories', [CategoryController::class, 'store'])->name('admin.categories.store');
      Route::get('categories/{category}/edit', [CategoryController::class, 'edit'])->name('admin.categories.edit');
      Route::delete('categories/mass-destroy', [CategoryController::class, 'massDestroy'])->name('admin.categories.massDestroy');
      Route::patch('categories/{category}', [CategoryController::class, 'update'])->name('admin.categories.update');
      Route::delete('categories/{category}', [CategoryController::class, 'destroy'])->name('admin.categories.destroy');

      // Currencies Management
      Route::get('currencies', [CurrencyController::class, 'index'])->name('admin.currencies');
      Route::post('currencies', [CurrencyController::class, 'store'])->name('admin.currencies.store');
      Route::get('currencies/{currency}/edit', [CurrencyController::class, 'edit'])->name('admin.currencies.edit');
      Route::delete('currencies/mass-destroy', [CurrencyController::class, 'massDestroy'])->name('admin.currencies.massDestroy');
      Route::patch('currencies/{currency}', [CurrencyController::class, 'update'])->name('admin.currencies.update');
      Route::delete('currencies/{currency}', [CurrencyController::class, 'destroy'])->name('admin.currencies.destroy');
    });

  });

});
Route::fallback(function () {
  return redirect('/');
});