<?php

use App\Http\Controllers\Api\AnalyticsController;
use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\BlogController;
use App\Http\Controllers\Api\ConversationController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\EventLogController;
use App\Http\Controllers\Api\NotificationAnalyticsController;
use App\Http\Controllers\Api\WishListController;
use Illuminate\Support\Facades\Route;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->group(function () {

    Route::prefix('customer')->group(function () {
        Route::post('create', [CustomerController::class, 'create']);
    });

    Route::prefix('log')
        ->controller(EventLogController::class)
        ->group(function () {
            Route::post('abandoned-cart', 'logCartAbandoned');
            Route::post('resolved-cart', 'logCartResolved');
            Route::post('app-terminated', 'logAppTerminated');
        });

    Route::prefix('general-notification')
        ->controller(EventLogController::class)
        ->group(function () {
            Route::post('read-notification', 'readNotification');
            Route::post('sale-notification', 'saleNotification');
        });

    Route::prefix('banner')
        ->controller(BannerController::class)
        ->group(function () {
            Route::get('list', 'list');
        });
    Route::prefix('message')
        ->controller(ConversationController::class)
        ->group(function () {
            Route::post('new', 'send');
            Route::get('conversation', 'conversation');
        });

    Route::prefix('wish-list')
        ->controller(WishListController::class)
        ->group(function () {
            Route::get('/', 'list');
            Route::post('store', 'store');
        });

    Route::prefix('analytics')
        ->controller(AnalyticsController::class)
        ->group(function () {
            Route::post('store', 'store');
            Route::get('/search-product-analytics', 'getSearchProductAnalytics');
            Route::get('/sale-product-analytics', 'getSalesProductAnalytics');
            Route::get('/view-product-analytics', 'getViewProductAnalytics');
        });

    Route::prefix('notification-analytics')
        ->controller(NotificationAnalyticsController::class)
        ->group(function () {
            Route::get('/notifications', 'getNotificationAnalytics');
            Route::get('/cart-abandonment', 'getCartAbandonmentNotificationAnalytics');
        });
});

Route::prefix('blog')
    ->controller(BlogController::class)
    ->group(function () {
        Route::get('list', 'allBlogs');
    });

Route::get('get-media/{uuid}',function ($uuid){
    $file = Media::whereUuid($uuid)->first();
    return response()->json($file->getFullUrl());
});

