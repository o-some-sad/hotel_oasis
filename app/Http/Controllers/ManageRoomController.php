<?php

namespace App\Http\Controllers;
namespace App\Http\Controllers;
use App\Http\Requests\StoreRoomRequest;
use App\Http\Requests\UpdateRoomRequest;
use App\Http\Resources\Resources\RoomResource;
use App\Models\Floor;
use App\Models\Room;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ManageRoomController extends Controller
{
  public function index()
  {
    if (auth()->user()->role === 'admin' || auth()->user()->role === 'manager') {

      $rooms = Room::with('creator')->paginate(10);
      if ($rooms->isEmpty()) {
        return Inertia::render('manageRooms/Index', [
          'pagination' => [
            'data' => [],
            'links' => [],
          ],
          'message' => 'There are no rooms yet.',
        ]);
      }

      return Inertia::render('manageRooms/Index', [
        'pagination' => RoomResource::collection($rooms)->response()->getData(true),
      ]);
    }

    abort(403);
  }


    /**
     */
    public function create()
    {
        if(auth()->user()->role ==='admin' || auth()->user()->role ==='manager') {

                $floors = Floor::select('id', 'name')->get();

                return Inertia::render('manageRooms/Create', [
                    'floors' => $floors
                ]);

        }
        abort(403);
    }


    public function show($id)
    {
        if (auth()->user()->role === 'admin' || auth()->user()->role === 'manager') {
            $room = Room::with(['creator', 'floor'])->where('id', $id)->first();

            if (!$room) {
                abort(404);
            }

            return Inertia::render('manageRooms/Show', [
                'room' => new RoomResource($room),
            ]);
        }

        abort(403);
    }


    public function store(StoreRoomRequest $request)
  {
    $request->authorize();
    $validated = $request->validated();

    $room = Room::create([
      'number' => random_int(1000, 9999),
      'capacity' => $validated['capacity'],
      'price' => intval($validated['price'] * 100),
      'floor_id' => $validated['floor_id'] ,
      'created_by' => auth()->id(),
    ]);
    return Inertia::location(route('rooms.index'));
  }

  public function edit($id,UpdateRoomRequest $request)
  {
    $request->authorize();
    $room = Room::findOrFail($id);
    $floors = Floor::all();
    return Inertia::render('manageRooms/Edit', [
      'room' => $room,
        'floors' => $floors,
    ]);
  }

    public function update(Request $request, $id)
    {
//        $request->authorize();

        $room = Room::findOrFail($id);
        $validated = $request->all();
//        $dirtyData = collect($validated)->filter(function ($value, $key) use ($room) {
//            return $room->$key != $value;
//        })->toArray();

        try {
//            if (!empty($dirtyData)) {
//                $room->update($dirtyData);
           $room= $room->update($validated);
//
//
            dd($room);
        } catch (\Exception $e) {
            dd($e->getMessage());
        }

        return redirect()->route('rooms.index')->with('message', 'Room updated successfully.');
    }


  public function destroy($id,UpdateRoomRequest $request)
  {
    $request->authorize();
    $room = Room::findOrFail($id);

    if($room['reserved']=== false){
      $room->delete();
    }
    else{
      return redirect()->route('rooms.index')->with('message', 'you can not delete this room.');

    }
    return redirect()->route('rooms.index')->with('message', 'Room deleted successfully.');
  }

}
