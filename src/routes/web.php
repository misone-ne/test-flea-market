<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;

Route::get('/', [ItemController::class, 'index'])->name('index');

Route::get('/item/{item_id}', [ItemController::class, 'show'])->name('item.show');