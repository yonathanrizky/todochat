<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ConfigAppController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginChatController;
use App\Http\Controllers\PrintMemoController;
use App\Http\Controllers\DashboardCustomerController;

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

Route::get('/login', [LoginChatController::class, 'index'])->name('login');
Route::post('/login', [LoginChatController::class, 'login'])->name('login');
Route::get('/register', [RegisterController::class, 'index'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register');

Route::group(['middleware' => 'auth:web'], function ()
{
    Route::get('/', [DashboardCustomerController::class, 'index'])->name('dashboard');
    Route::get('/logout', [LoginChatController::class, 'logout'])->name('web.logout');
    Route::resource('chat', ChatController::class);
    Route::post('/search', [SearchController::class, 'handle'])->name('search');
});

Route::prefix('admin')->group(
    function ()
    {
        Route::get('/login', [LoginController::class, 'index'])->name('admin.login');
        Route::post('/login', [LoginController::class, 'login'])->name('admin.login');

        Route::group(['middleware' => 'auth:admin'], function ()
        {
            Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
            Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

            Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
            Route::put('/profile/{id}', [ProfileController::class, 'update'])->name('profile.update');

            Route::get('/print/{id}', [PrintMemoController::class, 'show'])->name('print');

            Route::group(['middleware' => 'admin'], function ()
            {
                Route::resource('product', ProductController::class);
                Route::resource('user', UserController::class);
                Route::resource('customer', CustomerController::class);
                Route::get('config-app', [ConfigAppController::class, 'index'])->name('config-app');
                Route::post('config-app', [ConfigAppController::class, 'store'])->name('config-app.store');
            });
        });
    }
);
