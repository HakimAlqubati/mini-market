<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GroceryController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SubGroupController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Voyager\MainGroupController;
use App\Http\Controllers\Voyager\OrderController;
use App\Http\Controllers\Voyager\ProductController;
use App\Http\Controllers\Voyager\SliderController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

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

Route::get('logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);


Route::get('/', function () {
    return Redirect::to('/admin');
    return view('welcome');
});

Route::post('/store-main-group', [MainGroupController::class, 'store']);
Route::put('/update-main-group/{id}', [MainGroupController::class, 'update']);

Route::put('/update-order/{id}', [OrderController::class, 'update']);


Route::post('/processing-order/{id}', [OrderController::class, 'proccess']);
Route::post('/cancel-order/{id}', [OrderController::class, 'cancel']);
Route::post('/complete-order/{id}', [OrderController::class, 'complete']);

Route::post('/update-order-add-notes/{id}', [OrderController::class, 'addNotes']);




Route::post('/set-english-locale', [OrderController::class, 'setEnglish']);
Route::post('/set-arabic-locale', [OrderController::class, 'setArabic']);
Route::get('generate-pdf/{id}', [OrderController::class, 'generatePDF']);



Route::get('/admin/report', [OrderController::class, 'report']);

Route::get('/admin/report-basedon-product', [OrderController::class, 'reportBasedOnProduct']);

Route::get('/admin/report-customer', [OrderController::class, 'reportCustomer']);
Route::get('/admin/search-report-customer', [OrderController::class, 'searchReportCustomer']);

Route::post('/add-product', [ProductController::class, 'store']);
Route::put('/update-product/{id}', [ProductController::class, 'update']);
Route::post('/active-product/{id}', [ProductController::class, 'activeProduct']);
Route::post('/an-active-product/{id}', [ProductController::class, 'anActiveProduct']);


Route::get('/admin/create-products-colors/{id}', [ProductController::class, 'createColors']);

Route::post('/add-colors-unit-price/{id}', [ProductController::class, 'addColors']);
Route::get('/products/export', [ProductController::class, 'export']);


Route::post('/add-slider', [SliderController::class, 'store']);
Route::put('/update-slider/{id}', [SliderController::class, 'update']);




Route::get('/dashboard/customers', [DashboardController::class, 'customerPage']);



Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();


    // Route::post('admin', [DashboardController::class, 'index'])->name('dashboard');
});
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
$currentURL =   explode('/', URL::current());



Route::get('/test-sub', [SubGroupController::class, 'test']);

Route::get('/admin/notifications/create', [NotificationController::class, 'create']);
Route::get('/admin/topic-notifications/create', [NotificationController::class, 'topicCreate']);
Route::post('/send-notifi', [NotificationController::class, 'webSend']);
Route::post('/topic-notifi', [NotificationController::class, 'topicSend']);


Route::get('/locale/ar', function () {
    App::setLocale('ar');
    // return redirect()->back();
});
Route::get('/locale/en', function () {
    App::setLocale('en');
    // return redirect()->back();
});


Route::view('/grocery', 'grocery');
Route::post('/grocery/post', [GroceryController::class, 'store']);

Route::get('logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);



Route::get('/get-sub-group-of-main', [SubGroupController::class, 'getSubGroupOfMain']);
