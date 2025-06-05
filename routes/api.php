<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Api\VoucherController;
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/vouchers', [voucherController::class, 'index']);         
Route::get('/vouchers/{id}', [VoucherController::class, 'show']);     
Route::post('/vouchers', [VoucherController::class, 'store']);        
Route::put('/vouchers/{id}', [VoucherController::class, 'update']);   
Route::delete('/vouchers/{id}', [VoucherController::class, 'destroy']); 
