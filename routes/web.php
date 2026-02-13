<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;

Route::get('/', [AccountController::class, 'index'])->name('index');
Route::post('/store', [AccountController::class, 'store'])->name('store');
Route::get('/delete/{id}',[AccountController::class,'delete'])->name('delete');
Route::get('/installment',[AccountController::class,'installment'])->name('installment');

Route::post('/purpose-store',[AccountController::class,'purposeAdd'])->name('purposeStore');
Route::post('/installment-store',[AccountController::class,'installmentStore'])->name('installmentStore');

Route::get('/salary',[AccountController::class,'salary'])->name('salary');
Route::get('/history',[AccountController::class,'history'])->name('history');
