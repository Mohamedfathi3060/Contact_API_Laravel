<?php

use App\Http\Controllers\ContactsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//Route::get('/contact',[ContactsController::class,'index'])->name('contact.index');
//Route::get('/contact/{contact}',[ContactsController::class,'show'])->name('contact.show');
//Route::post('/contact',[ContactsController::class,'store'])->name('contact.store');
//Route::patch('/contact/{contact}',[ContactsController::class,'update'])->name('contact.update');
//Route::delete('/contact/{contact}',[ContactsController::class,'destroy'])->name('contact.destroy');

// TODO use apiResource instead
Route::apiResource('/contact',ContactsController::class);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
