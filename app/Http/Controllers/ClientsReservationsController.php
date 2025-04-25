<?php

namespace App\Http\Controllers;

use App\Http\Resources\ReservationResource;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ClientsReservationsController extends Controller
{
    /**
     * Display a listing of client reservations for staff members.
     */
    public function index()
    {
        // Check if user is a staff member using the role attribute
        $user = auth()->user();
        $staffRoles = ['admin', 'manager', 'receptionist'];
        
        if (!in_array($user->role, $staffRoles)) {
            abort(403, 'Unauthorized');
        }

        $reservations = Reservation::with(['client:id,name', 'room:id,number'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return Inertia::render('ClientReservations/staffIndex', [
            'data' => ReservationResource::collection($reservations),
            'links' => $reservations->onEachSide(1)->links(),
        ]);
    }

    /**
     * Display the specified reservation details.
     */
    public function show(Reservation $reservation)
    {
        // Check if user is a staff member using the role attribute
        $user = auth()->user();
        $staffRoles = ['admin', 'manager', 'receptionist'];
        
        if (!in_array($user->role, $staffRoles)) {
            abort(403, 'Unauthorized');
        }

        return Inertia::render('ClientReservations/Details', [
            'reservation' => new ReservationResource($reservation->load(['client', 'room']))
        ]);
    }
}