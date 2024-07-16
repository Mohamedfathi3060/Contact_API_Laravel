<?php

use App\Http\Controllers\ContactsController;
use App\Http\Controllers\EmailsController;
use App\Http\Controllers\PhonesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Route::get('/contact',[ContactsController::class,'index'])->name('contact.index');
//Route::get('/contact/{contact}',[ContactsController::class,'show'])->name('contact.show');
//Route::post('/contact',[ContactsController::class,'store'])->name('contact.store');
//Route::patch('/contact/{contact}',[ContactsController::class,'update'])->name('contact.update');
//Route::delete('/contact/{contact}',[ContactsController::class,'destroy'])->name('contact.destroy');

// TODO use apiResource instead
Route::apiResource('/contact',ContactsController::class);
// Shallow to remove contact ID in update / delete
Route::apiResource('/contact.email', EmailsController::class)
    ->except('show')
    ->shallow();
Route::apiResource('/contact.phone', PhonesController::class)->shallow();














Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
