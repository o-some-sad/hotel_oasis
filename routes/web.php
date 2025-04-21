<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ManageReceptionistController;
use App\Http\Controllers\ManagerController;



Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route:

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';


//Route::middleware(['auth'])->group(function() {
//    Route::resource('receptionists', ManageReceptionistController::class);
//});
Route::get('/managers', [ManagerController::class, 'index'])
    ->middleware('auth')
    ->name('managers.index');
Route::get('/managers/create', [ManagerController::class, 'create'])
    ->middleware('auth')
    ->name('managers.create');

Route::resource( 'receptionists', ManageReceptionistController::class)->middleware('auth');
