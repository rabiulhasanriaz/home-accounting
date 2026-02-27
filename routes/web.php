<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;

Route::get('/', [AccountController::class, 'index'])->name('index');
Route::post('/store', [AccountController::class, 'store'])->name('store');
Route::get('/delete/{id}',[AccountController::class,'delete'])->name('delete');
Route::get('/installment',[AccountController::class,'installment'])->name('installment');

Route::post('/purpose-store',[AccountController::class,'purposeAdd'])->name('purposeStore');
Route::post('/installment-store',[AccountController::class,'installmentStore'])->name('installmentStore');
Route::get('/installment/{id}', [AccountController::class, 'editAjax'])->name('installment.editAjax');
Route::post('/installment/{id}', [AccountController::class, 'updateAjax'])->name('installment.updateAjax');

Route::get('/salary',[AccountController::class,'salary'])->name('salary');
Route::post('/salary-store',[AccountController::class,'salaryStore'])->name('salaryStore');

Route::get('/history',[AccountController::class,'history'])->name('history');
Route::get('/spender-details/{id}',[AccountController::class,'spenderDetails'])->name('spenderDetails');

Route::get('/utility',[AccountController::class,'utility'])->name('utility');
Route::post('/utility-store',[AccountController::class,'utilityStore'])->name('utilityStore');

Route::get('/history/pdf', [AccountController::class, 'downloadHistoryPdf']);
Route::get('/history/{year}/{month}/pdf', [AccountController::class, 'downloadMonthlyPdf'])
    ->where(['year' => '\d{4}', 'month' => '\d{1,2}']);
