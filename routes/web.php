<?php

use App\Http\Controllers\ApprovedClientsController;
use Illuminate\Http\Response;
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


//TODO: move to api.php after installing Laravel Sanctum
Route::get('/api/countries', function (Response $response) {
    $result = DB::table('lc_countries_translations')
        ->join('lc_countries', 'lc_countries.id', '=', 'lc_countries_translations.lc_country_id')
        ->select('lc_countries_translations.name', 'lc_countries.id', 'lc_countries.iso_alpha_3')
        ->get();
    return response()->json($result)
        ->header('Cache-Control', 'public, max-age=31536000')
        ->header('Expires', gmdate('D, d M Y H:i:s', time() + 31536000) . ' GMT');
});

Route::resource("approved-clients", ApprovedClientsController::class)->middleware(["auth", "verified"]);

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
