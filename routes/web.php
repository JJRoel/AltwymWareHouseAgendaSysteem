<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController;
Route::get('/bookings', [BookingController::class, 'index']);
Route::post('/bookings', [BookingController::class, 'store']);
Route::put('/bookings/{id}', [BookingController::class, 'update']);
Route::delete('/bookings/{id}', [BookingController::class, 'destroy']);

use App\Http\Controllers\GroupController;
Route::get('/groups', [GroupController::class, 'index']);

Route::get('/calendar', function () {
    return view('calender.index');
});

use App\Http\Controllers\AdministrationController;

Route::get('/items', [AdministrationController::class, 'index'])->name('administration.index');
Route::patch('/items/updateDescription/{id}', [AdministrationController::class, 'updateDescription'])->name('administration.updateDescription');
Route::patch('/items/updateStatus/{id}', [AdministrationController::class, 'updateStatus'])->name('administration.updateStatus');

