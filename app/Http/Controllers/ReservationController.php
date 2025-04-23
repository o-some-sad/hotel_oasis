<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReservationRequest;
use App\Http\Requests\UpdateReservationRequest;
use App\Models\Reservation;
use App\Models\Room;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render("ClientReservations/index");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $availableCapacities = array_unique(array_map(fn($room)=> $room['capacity'], Room::where("reserved", '=', false)->get()->toArray()));
        $selectedCapacity = $request->query("capacity");
        $availableRooms = [];

        if ($selectedCapacity) {
            $availableRooms = Room::with("floor")->where("reserved", "=", false)->where("capacity", "=", $selectedCapacity)->get()->toArray();
        }
        
        return Inertia::render("ClientReservations/create", compact("availableCapacities", "availableRooms"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReservationRequest $request)
    {
        $validated = $request->validated();
        $room = Room::find($validated["room"])->where("reserved","=", false)->first();
        $reservation = Reservation::create([
            'room_id' => $room->id,
            'client_id' => auth()->user()->id,
            'accompany_number' => $room->capacity - 1,
            'paid_price' => $room->price * $validated["duration"],
        ]);

        $room->update([
            'reserved' => true,
        ]);

        dd($reservation);
        
        // $checkout_session = \Stripe\Checkout\Session::create([
        //     'payment_method_types' => ['card'],
        //     'mode' => 'payment',
        //     'line_items' => [[
        //         'price_data'=> [
        //             'currency'=> 'usd',
        //             'unit_amount'=> 1000,
        //             'product_data'=> [
        //                 'name'=> 'Oasis Hotel Room',
        //             ]
        //         ],
        //         'quantity'=> 30
        //     ]],
        //     'success_url' => "http://localhost:8000/success",
        //     'cancel_url' => "http://localhost:8000/cancel",
        // ]);
          
        //   header("HTTP/1.1 303 See Other");
        //   header("Location: " . $checkout_session->url);
        //   exit;

    }

    /**
     * Display the specified resource.
     */
    public function show(Reservation $reservation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reservation $reservation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReservationRequest $request, Reservation $reservation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reservation $reservation)
    {
        //
    }
}
