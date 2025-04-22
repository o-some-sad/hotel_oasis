<?php

namespace App\Http\Controllers;
namespace App\Http\Controllers;
use App\Http\Requests\StoreRoomRequest;
use App\Http\Requests\UpdateRoomRequest;
use App\Http\Resources\Resources\RoomResource;
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
            return Inertia::render('manageRooms/Create');
        }
        abort(403);
    }


  public function store(StoreRoomRequest $request)
  {
    $request->authorize();
    $validated = $request->validated();

    $room = Room::create([
      'number' => $validated['number'],
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
    return Inertia::render('manageRooms/Edit', [
      'room' => $room,
    ]);
  }


  public function update(UpdateRoomRequest $request, $id)
  {
    $request->authorize();

    $room = Room::findOrFail($id);
    $validated = $request->validated();

    $dirtyData = collect($validated)->filter(function ($value, $key) use ($room) {
      return $room->$key !== $value;
    })->toArray();

    if (!empty($dirtyData)) {
      $room->update($dirtyData);
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
