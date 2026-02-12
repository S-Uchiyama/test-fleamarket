<?php

use App\Http\Controllers\ItemController;
use App\Http\Controllers\ItemLikeController;
use App\Http\Controllers\ItemCommentController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\MypageController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', [ItemController::class, 'index'])->name('items.index');
Route::get('/item/{item}', [ItemController::class, 'show'])->name('items.show');

// --- 要認証ルート ---
Route::middleware('auth')->group(function () {
    // マイページ
    Route::get('/mypage', [MypageController::class, 'index'])->name('mypage.index');
    // いいね
    Route::post('/item/{item}/like', [ItemLikeController::class, 'toggle'])->name('items.like');
    // コメント投稿
    Route::post('/item/{item}/comments', [ItemCommentController::class, 'store'])->name('items.comments.store');
    // プロフィール関連
    Route::get('/mypage/profile', [ProfileController::class, 'edit'])->name('mypage.profile.edit');
    Route::post('/mypage/profile', [ProfileController::class, 'update'])->name('mypage.profile.update');
    // 出品関連
    Route::get('/sell', [ItemController::class, 'create'])->name('items.create');
    Route::post('/sell', [ItemController::class, 'store'])->name('items.store');
    // 購入関連
    Route::get('/purchase/{item}', [PurchaseController::class, 'show'])->name('purchase.show');
    Route::post('/purchase/{item}', [PurchaseController::class, 'store'])->name('purchase.store');
    Route::get('/purchase/{item}/success', [PurchaseController::class, 'success'])->name('purchase.success');
    Route::get('/purchase/{item}/cancel', [PurchaseController::class, 'cancel'])->name('purchase.cancel');
     // 送付先住所変更画面
    Route::get('/purchase/address/{item}', [PurchaseController::class, 'editAddress'])->name('purchase.address.edit');
    Route::post('/purchase/address/{item}', [PurchaseController::class, 'updateAddress'])->name('purchase.address.update');
});
