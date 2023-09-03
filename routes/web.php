<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChartController;
use App\Http\Controllers\admin\OrderController;
use App\Http\Controllers\CostumerController;
use App\Http\Controllers\admin\HomeController;
use App\Http\Controllers\admin\UsersController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\admin\TempImageController;
use App\Http\Controllers\admin\AdminLoginController;
use App\Models\Order;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });





Route::group(['prefix' => 'account'], function () {
    Route::group(['middleware' => 'guest'], function () {
        Route::get('/login', [AuthController::class, 'login'])->name('login');
        Route::post('/authenticate', [AuthController::class, 'authenticate'])->name('login.authenticate');
        Route::get('/register', [AuthController::class, 'register'])->name('register');
        Route::post('/register-proses', [AuthController::class, 'registerProses'])->name('register.proses');
    });
    Route::group(['middleware' => 'auth'], function () {
    });
});

Route::group(['prefix' => 'admin'], function () {
    Route::group(['middleware' => 'admin.guest'], function () {
        Route::get('/login', [AdminLoginController::class, 'index'])->name('admin.login');
        Route::post('/authenticate', [AdminLoginController::class, 'authenticate'])->name('admin.authenticate');
    });


    Route::group(['middleware' => 'admin.login'], function () {
        Route::get('/dashboard', [HomeController::class, 'index'])->name('admin.dashboard');
        Route::get('/logout', [HomeController::class, 'logout'])->name('admin.logout');


        Route::get('/users', [UsersController::class, 'index'])->name('user.index');
        Route::get('/orders', [OrderController::class, 'index'])->name('order.index');

        //product Routes
        Route::get('/product', [ProductController::class, 'index'])->name('product.index');
        Route::get('/product/create', [ProductController::class, 'create'])->name('product.create');
        Route::post('/product/store', [ProductController::class, 'store'])->name('product.store');
        Route::get('/product/{product}/edit', [ProductController::class, 'edit'])->name('product.edit');
        Route::put('/product/{product}', [ProductController::class, 'update'])->name('product.update');
        Route::delete('/product/{product}', [ProductController::class, 'distroy'])->name('product.delete');

        //temp-image-upload
        Route::post('/upload_temp_images', [TempImageController::class, 'create'])->name('temp-images.create');
    });
});

//
Route::get('/', [CostumerController::class, 'index'])->name('costumer.index');


//chart
Route::get('/cart', [ChartController::class, 'chart'])->name('costumer.chart');
Route::post('/add-to-chart', [ChartController::class, 'addToChart'])->name('costumer.addToCart');
Route::post('/update-chart', [ChartController::class, 'updateCart'])->name('costumer.updateCart');
Route::post('/delete-item', [ChartController::class, 'deleteItem'])->name('costumer.deleteItem');
Route::get('/checkout', [ChartController::class, 'checkout'])->name('costumer.checkout');
Route::post('/proses-checkout', [ChartController::class, 'prosesCheckout'])->name('costumer.prosesCheckout');
Route::get('/invoice/{order_id}', [ChartController::class, 'invoice'])->name('order.invoice');
