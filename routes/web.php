<?php

use App\Http\Controllers\ApprovedClientsController;
use App\Http\Controllers\ClientsReservationsController;
use App\Http\Controllers\FloorController;
use App\Http\Controllers\ReservationController;
use App\Http\Middleware\EnsureUserHasRole;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ManageReceptionistController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\PendingApprovalController;
use App\Http\Controllers\ManageRoomController;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

/*
price_data: {
        currency: "usd",
        unit_amount: item.price * 100, //PRICE IS IN CENTS
        product_data: {
          name: item.title,
        },
      },
      quantity: item.quantity,

*/

Route::get('/my-reservations/success', [ReservationController::class, 'success'])->middleware(['auth', EnsureUserHasRole::class .':client'])->name('payment.success');
Route::get('/my-reservations/cancel', [ReservationController::class, 'cancel'])->middleware(['auth', EnsureUserHasRole::class .':client'])->name('payment.cancel');
Route::get('/my-reservations/error', [ReservationController::class, 'error'])->middleware(['auth', EnsureUserHasRole::class .':client'])->name('payment.error');


Route::resource('my-reservations', ReservationController::class)->middleware([
    'auth',
    EnsureUserHasRole::class . ":client",
]);

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

Route::get('/pending-approval/{id}/approve', [PendingApprovalController::class, 'approve'])->name('pending-approvals.approve');

Route::resource('managers', ManagerController::class)->middleware('auth');
// ->where(['role' => 'admin']);

Route::resource('receptionists', ManageReceptionistController::class)->middleware('auth');

// Ban/unban routes for receptionists
Route::post('receptionists/{id}/ban', [ManageReceptionistController::class, 'ban'])
    ->middleware('auth')
    ->name('receptionists.ban');

Route::post('receptionists/{id}/unban', [ManageReceptionistController::class, 'unban'])
    ->middleware('auth')
    ->name('receptionists.unban');

// Client management routes
// Route::resource('clients', ClientController::class)->middleware('auth');

// Ban/unban routes for clients
//excel 
Route::get('/clients/export', [ClientController::class, 'export'])->middleware('auth')->name('clients.export');

Route::post('clients/{id}/ban', [ClientController::class, 'ban'])
    ->middleware('auth')
    ->name('clients.ban');

Route::post('clients/{id}/unban', [ClientController::class, 'unban'])
    ->middleware('auth')
    ->name('clients.unban');

Route::get('/clients', [ClientController::class, 'index'])
    ->middleware('auth')
    ->name('clients.index');
Route::post('/clients', [ClientController::class, 'store'])
    ->middleware('auth')
    ->name('clients.store');
Route::put('/clients/{id}', [ClientController::class, 'update'])
    ->middleware('auth')
    ->name('clients.update');
Route::get('/clients/create', [ClientController::class, 'create'])
    ->middleware('auth')
    ->name('clients.create');
Route::get('/clients/{id}', [ClientController::class, 'show'])
    ->middleware('auth')
    ->name('clients.show');
Route::delete('/clients/{id}', [ClientController::class, 'destroy'])
    ->middleware('auth')
    ->name('clients.destroy');
Route::get('/clients/{id}/edit', [ClientController::class, 'edit'])
    ->middleware('auth')
    ->name('clients.edit');
Route::post('/clients/{id}/ban', [ClientController::class, 'ban'])
    ->middleware('auth')
    ->name('clients.ban');
Route::post('/clients/{id}/unban', [ClientController::class, 'unban'])
    ->middleware('auth')
    ->name('clients.unban');
// Show the dummy approve view
Route::get('/clients/{id}/dummy-approve', [ClientController::class, 'dummyApproveView'])->name('clients.dummy-approve.view');

// Approve action
Route::post('/clients/{client}/approve', [ClientController::class, 'approve'])->name('clients.approve');


Route::resource('pending-approvals', PendingApprovalController::class)->middleware('auth');

Route::resource('receptionists', ManageReceptionistController::class)->middleware('auth');
Route::resource('rooms', ManageRoomController::class)->middleware('auth');
Route::resource('floors', FloorController::class)->middleware(['auth', 'verified']);



Route::get('/', function () {
    return Inertia::render('Home');
});

Route::get('/booking', function () {
    return Inertia::render('Booking');
})->name('booking');

// Clients Reservations for staff (admin, manager, receptionist)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/clients-reservations', [ClientsReservationsController::class, 'index'])
        ->middleware(EnsureUserHasRole::class . ':admin,manager,receptionist')
        ->name('clients-reservations.index');
        
    Route::get('/clients-reservations/{reservation}', [ClientsReservationsController::class, 'show'])
        ->middleware(EnsureUserHasRole::class . ':admin,manager,receptionist')
        ->name('clients-reservations.show');
});


