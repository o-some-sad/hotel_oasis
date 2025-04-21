<?php

namespace App\Http\Controllers;
namespace App\Http\Controllers;
use App\Http\Resources\Resources\ReceptionistResource;
use App\Models\Room;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ManageRoomController extends Controller
{
        public function index()
    {
        if(auth()->user()->role ==='admin' || auth()->user()->role ==='manager') {

            $rooms = Room::with('creator')->get();
            if ($rooms->isEmpty()) {
                return Inertia::render('manageRooms/Index', [
                    'rooms' => [],
                    'message' => 'No rooms found.'
                ]);
            }
            return Inertia::render('manageRooms/Index', [
                'rooms' => ReceptionistResource::collection($rooms)->toArray(request())
            ]);
        }
        abort(403);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if(auth()->user()->role ==='admin' || auth()->user()->role ==='manager') {
            return Inertia::render('manageRoom/Create');
        }
        abort(403);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Room $room)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Room $room)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Room $room)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Room $room)
    {
        //
    }
}
