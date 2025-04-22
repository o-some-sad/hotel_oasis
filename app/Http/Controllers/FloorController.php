<?php

namespace App\Http\Controllers;

use App\Models\Floor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class FloorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $isAdmin = $user->role === 'admin'; // Directly check the role attribute
        
        $query = Floor::query()
            ->with('creator:id,name')
            ->select('id', 'name', 'number', 'created_by');
            
        // If not admin, show only floors created by this manager
        if (!$isAdmin) {
            $query->where('created_by', $user->id);
        }
        
        $floors = $query->paginate(10);
        
        // Add action flag to determine if the current user can edit/delete the floor
        $floors->getCollection()->transform(function ($floor) use ($user, $isAdmin) {
            $floor->action = $isAdmin || $floor->created_by === $user->id;
            return $floor;
        });
        
        return Inertia::render('manageFloors/Index', [
            'data' => $floors->items(),
            'links' => $floors->links(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('manageFloors/CreateFloor');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:3',
        ]);
        
        // Generate a unique 4-digit floor number
        $number = $this->generateUniqueFloorNumber();
        
        Floor::create([
            'name' => $validated['name'],
            'number' => $number,
            'created_by' => Auth::id(),
        ]);
        
        return redirect()->route('floors.index')->with('success', 'Floor created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $floor = Floor::findOrFail($id);
        
        // Check if the user can edit this floor
        $user = Auth::user();
        if ($user->role !== 'admin' && $floor->created_by !== $user->id) {
            abort(403, 'Unauthorized action.');
        }
        
        return Inertia::render('manageFloors/EditFloor', [
            'floor' => $floor,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $floor = Floor::findOrFail($id);
        
        // Check if the user can update this floor
        $user = Auth::user();
        if ($user->role !== 'admin' && $floor->created_by !== $user->id) {
            abort(403, 'Unauthorized action.');
        }
        
        $validated = $request->validate([
            'name' => 'required|string|min:3',
        ]);
        
        $floor->update([
            'name' => $validated['name'],
            // Number cannot be modified as per requirements
        ]);
        
        return redirect()->route('floors.index')->with('success', 'Floor updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $floor = Floor::findOrFail($id);
        
        // Check if the user can delete this floor
        $user = Auth::user();
        if ($user->role !== 'admin' && $floor->created_by !== $user->id) {
            abort(403, 'Unauthorized action.');
        }
        
        $floor->delete();
        
        return redirect()->route('floors.index')->with('success', 'Floor deleted successfully.');
    }
    
    /**
     * Generate a unique 4-digit floor number.
     */
    private function generateUniqueFloorNumber(): string
    {
        do {
            // Generate a 4-digit random number
            $number = str_pad(mt_rand(1000, 9999), 4, '0', STR_PAD_LEFT);
        } while (Floor::where('number', $number)->exists());
        
        return $number;
    }
}
