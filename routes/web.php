<?php

use App\Http\Controllers\FacebookController;
use App\Http\Controllers\OAuthShopifyController;
use App\Http\Controllers\PlanSubscriptionController;
use App\Http\Controllers\Youtube0AuthController;
use App\Http\Middleware\Authenticate;
use App\Livewire\ContactSupport;
use App\Livewire\Onboarding;
use Illuminate\Support\Facades\Route;

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


Route::get('/install', [OAuthShopifyController::class, 'install'])->name('shopify.install');
Route::get('/callback', [OAuthShopifyController::class, 'handleCallback'])->name('shopify.callback');


Route::get('/onboarding', Onboarding::class)->middleware('auth')->name('onboarding');

Route::get('/', ContactSupport::class)->name('index');

Route::prefix('youtube')->group(function () {
    Route::get('auth', [Youtube0AuthController::class, 'redirectToYouTube'])->name('youtube.auth');
    Route::get('4424df2f-d85a-459c-ae31-1c6fb7847c77/callback', [Youtube0AuthController::class, 'handleYoutubeCallback'])->name('youtube.callback');
});

Route::middleware(Authenticate::class)->group(function () {
    Route::get('/plan/subscribe/{plan}', [PlanSubscriptionController::class, 'subscribe'])->name('plans.subscribe');
});

Route::get('shop-url', [OAuthShopifyController::class, 'shopUrl'])->name('shop-url');

Route::stripeWebhooks('stripe/webhook');

Route::prefix('facebook')->group(function () {
    Route::get('auth', [FacebookController::class, 'redirectToFacebook'])->name('facebook.auth');
    Route::get('4424df2f-d85a-459c-ae31-1c6fb7847c77/callback', [FacebookController::class, 'handleFacebookCallback'])->name('facebook.callback');
});
