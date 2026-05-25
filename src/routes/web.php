<?php

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\VerificationController;


// ======================
// 認証（ログイン・登録系）
// ======================
// メール認証誘導画面
Route::get('/email/verify/notice', [VerificationController::class, 'notice'])->middleware('auth')->name('verification.notice.custom');

// メールクリック処理
Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->middleware(['auth', 'signed'])->name('verification.verify');

// メール再送
Route::post('/email/verification-notification', [VerificationController::class, 'send'])->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// ======================
// 公開ページ
// ======================
Route::get('/', [ItemController::class, 'index'])->name('index');
Route::get('/item/{item_id}', [ItemController::class, 'show'])->name('item.show');


// ======================
// 認証済みユーザーのみ
// ======================
Route::middleware(['auth', 'verified'])->group(function () {

    // プロフィール
    Route::get('/mypage/profile', [ProfileController::class, 'edit'])->name('mypage.edit');
    Route::post('/mypage/profile', [ProfileController::class, 'update'])->name('mypage.update');
    Route::get('/mypage', [ProfileController::class, 'index'])->name('mypage');

    // いいね切替
    Route::post('/item/{item_id}/like', [ItemController::class, 'toggleLike'])->name('like');

    // コメント投稿
    Route::post('/item/{item_id}/comment', [ItemController::class, 'storeComment'])->name('comment.store');

    // 出品
    Route::get('/sell', [ItemController::class, 'sell'])->name('item.sell');
    Route::post('/sell', [ItemController::class, 'store'])->name('item.store');

    // 購入
    Route::get('/purchase/{item_id}', [PurchaseController::class, 'show'])->name('purchase.show');
    Route::post('/purchase/{item_id}', [PurchaseController::class, 'store'])->name('purchase.store');
    Route::post('/purchase/{item_id}/preview', [PurchaseController::class, 'preview'])
        ->name('purchase.preview');

    // 住所変更
    Route::get('/purchase/address/{item_id}', [PurchaseController::class, 'editAddress'])->name('address.edit');
    Route::post('/purchase/address/{item_id}', [PurchaseController::class, 'updateAddress'])->name('address.update');
});
