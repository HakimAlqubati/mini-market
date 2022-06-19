<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\API\ForgotPasswordController;
use App\Http\Controllers\API\ResetPasswordController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\MainGroupController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderItemsController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SubGroupController;
use App\Http\Controllers\SliderController;
use App\Mail\UserSubscribed;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\NotificationController;
use App\Models\User;
use Carbon\Carbon;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::post('registration', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

// Route::post('login', [AuthController::class, 'login']);


Route::get('main-groups', [MainGroupController::class, 'index']);

Route::get('sub-groups', [SubGroupController::class, 'index']);

Route::get('products', [ProductController::class, 'index']);
Route::get('get-order-details', [OrderItemsController::class, 'index']);

Route::get('get-slider-images', [SliderController::class, 'index']);

Route::middleware(['auth:api'])->group(function () {
    Route::post('/update-user', [AuthController::class, 'update']);
    Route::post('/add-address', [AddressController::class, 'store']);
    Route::get('/get-address', [AddressController::class, 'getAddress']);
    Route::post('/add-order', [OrderController::class, 'store']);
    Route::get('/get-orders', [OrderController::class, 'index']);
    Route::get('/paginate/orders', [OrderController::class, 'paginateIndex']);
    Route::post('/send-noti', [OrderController::class, 'sendNotification']);
    Route::post('/update-state', [OrderController::class, 'updateState']);
    Route::get('/notifications', function () {
        return auth()->user()->unreadnotifications()->get();
    });

    Route::post('/send-notifi', [NotificationController::class, 'send']);
    Route::get('/get-customers', function () {
        return User::where('role_id', 3)->get();
    });
    Route::post('/noti-mark-as-read', function (Request $request) {
        $notification = DB::table('notifications')->where('notifiable_id', auth()->user()->id)->where('read_at', null)->get();
        $orderid = $request->order_id;

        foreach ($notification as $key => $value) {

            if ($orderid == json_decode($value->data)->order_id) {
                DB::table('notifications')->where('id', $value->id)->update(['read_at' => Carbon::now()]);
            }
        }
    });
});


Route::post('/password/forgot', [AuthController::class, 'forgotPassword']);
Route::post('/password/reset', [AuthController::class, 'reset']);

// Route::post('password/forgot-password', [ForgotPasswordController::class, 'sendResetLinkResponse'])->name('passwords.sent');
// Route::post('password/reset', [ResetPasswordController::class, 'sendResetResponse'])->name('passwords.reset');


Route::middleware('auth:api', 'verified')->get('/user', function (Request $request) {
    // Route::middleware('auth:api')->get('/user', function (Request $request) {


    return $request->user();
});


Route::post('verify-email', [AuthController::class, 'verify'])->middleware('auth:api');

Route::get('colors', [ColorController::class, 'index']);
// Route::post('email/verification-notification', [EmailVerificationController::class, 'sendVerificationEmail'])->middleware('auth:api');

// Route::get('verify-email/{id}/{hash}', [EmailVerificationController::class, 'verify'])->name('verification.verify')->middleware('auth:api');

// Route::middleware('auth:api', 'verified')->get('/user', function (Request $request) {
//     return $request->user();
// });
