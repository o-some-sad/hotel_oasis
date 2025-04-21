<?php

use App\Http\Controllers\ApprovedClientsController;
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


// Route::get('/approved-clients', [ApproveClientsController::class, 'approvedClients'])->middleware(['auth', 'verified'])->name('approved-clients');

Route::resource("approved-clients", ApprovedClientsController::class);

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';


//Route::middleware(['auth'])->group(function() {
//    Route::resource('receptionists', ManageReceptionistController::class);
//});
Route::get('/managers', [ManagerController::class, 'index'])
    ->middleware('auth')
    ->name('managers.index');

Route::resource('receptionists', ManageReceptionistController::class)->middleware('auth');

// Ban/unban routes for receptionists
Route::post('receptionists/{id}/ban', [ManageReceptionistController::class, 'ban'])
    ->middleware('auth')
    ->name('receptionists.ban');

Route::post('receptionists/{id}/unban', [ManageReceptionistController::class, 'unban'])
    ->middleware('auth')
    ->name('receptionists.unban');
