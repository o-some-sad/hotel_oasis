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
        $data = Reservation::with("room")->where("client_id", "=", auth()->user()->id)->get()->toArray();
        return Inertia::render("ClientReservations/index", compact("data"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $availableCapacities = array_unique(array_map(fn($room) => $room['capacity'], Room::where("reserved", '=', false)->get()->toArray()));
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
        //grab the room
        $room = Room::find($validated["room"])->where("reserved", "=", false)->first();
        // create a reservation
        $reservation = Reservation::create([
            'room_id' => $room->id,
            'client_id' => auth()->user()->id,
            'accompany_number' => $validated["accompany_number"],
            'paid_price' => $room->price * $validated["duration"],
        ]);

        // checkout
        $checkout_session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'mode' => 'payment',
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'usd',
                        'unit_amount' => $room->price, // PRICES ARE STORED IN CENTS
                        'product_data' => [
                            'name' => 'Oasis Hotel Room',
                        ]
                    ],
                    'quantity' => $validated['duration'],
                ]
            ],
            'success_url' => route('payment.success', ['id' => $reservation->id]),
            'cancel_url' =>  route('payment.cancel', ['id'=> $reservation->id]),
            'metadata' => [
                'reservation_id' => $reservation->id
            ]
        ]);
        $reservation->checkout_session = $checkout_session->id;
        $reservation->save();
        return Inertia::location($checkout_session->url);

        // $room->update([
        //     'reserved' => true,
        // ]);

        // dd($reservation);



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


    public function success(Request $request)
    {
        $reservation = Reservation::find($request->query('id'));
        if ($reservation == null) {
            $request->session()->put('flashes', 'Reservation not found');
            return to_route('payment.error');
        }
        if ($reservation->payment_status != "pending") {
            $request->session()->put("flashes","Payment is not pending, please contact support");
            return to_route("payment.error");
        }
        $session = \Stripe\Checkout\Session::retrieve("" . $reservation->checkout_session);
        if ($session->payment_status !== "paid") {
            $request->session()->put("flashes","Payment is not paid, please contact support");
            return to_route("payment.error");
        }
        $reservation->payment_status = "paid";
        $reservation->save();
        $room = Room::find($reservation->room_id);
        $room->reserved = true;
        $room->save();
        return Inertia::render("ClientReservations/success", [
            "reservation_id" => $reservation->id,
            "room_number" => $room->number
        ]);
    }

    public function error(Request $request){
        $props = [];
        if( $request->session()->has("flashes") ){
            $props["flashes"] = $request->session()->get("flashes");
            $request->session()->remove("flashes");
        }
        return Inertia::render("ClientReservations/error", $props);
    }
}
