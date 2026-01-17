<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;

Route::get('/', [AccountController::class, 'index'])->name('index');
Route::post('/store', [AccountController::class, 'store'])->name('store');
Route::get('/installment',[AccountController::class,'installment'])->name('installment');

Route::post('/purpose-store',[AccountController::class,'purposeAdd'])->name('purposeStore');
