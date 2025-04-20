<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ManageReceptionistController;
use App\Http\Middleware\EnsureUserHasRole;

Route::get('/admin', function () {
    // ...
})->middleware([EnsureUserHasRole::class, 'ensureUserHasRole:admin']);

Route::get('/manager', function () {
    // ...
})->middleware([EnsureUserHasRole::class, 'ensureUserHasRole:manager']);

Route::get('/receptionist', function () {
    // ...
})->middleware([EnsureUserHasRole::class, 'ensureUserHasRole:receptionist']);

Route::get('/client', function () {
    // ...
})->middleware([EnsureUserHasRole::class, 'ensureUserHasRole:client']);


Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';


//Route::middleware(['auth'])->group(function() {
//    Route::resource('receptionists', ManageReceptionistController::class);
//});

Route::resource( 'receptionists', ManageReceptionistController::class)->middleware(['auth', 'can:manage-receptionists']);
