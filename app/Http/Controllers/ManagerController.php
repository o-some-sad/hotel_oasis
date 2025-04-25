<?php

namespace App\Http\Controllers;
use App\Models\User;
use Inertia\Inertia;
use App\Http\Requests\StoreManagerRequest;
use App\Http\Requests\UpdateManagerRequest;
use Illuminate\Support\Facades\Storage;

class ManagerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(auth()->user()->role !== 'admin') {
            abort(403);
        }
        
        $managers = User::select([
            'id',
            'name',
            'email',
            'national_id',
            'avatar_img',
            'mobile',
            'country',
            'gender',
            'is_approved',
            'banned_at',
            'created_at',
        ])->where('role', 'manager')
        ->latest()
        ->paginate(10);

        return Inertia::render('manageManagers/Index', [
            'pagination' => [
                'data' => $managers->items(),
                'links' => $managers->toArray()['links'],
                'current_page' => $managers->currentPage(),
                'last_page' => $managers->lastPage(),
                'from' => $managers->firstItem(),
                'to' => $managers->lastItem(),
                'total' => $managers->total(),
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if(auth()->user()->role !== 'admin') {
            abort(403);
        }
        
        return Inertia::render('manageManagers/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreManagerRequest $request)
    {
        try {
            $validated = $request->validated();
            $validated['role'] = 'manager';
            $validated['mobile'] = (string) $validated['mobile'];
            $validated['password'] = bcrypt($validated['password']);
            $validated['is_approved'] = true;
            $validated['created_by'] = auth()->id();
            $validated['last_login_in'] = now();
            
            if ($request->hasFile('avatar_img')) {
                $validated['avatar_img'] = $request->file('avatar_img')->store('managers', 'public');
            }
            
            User::create($validated);
            
            return redirect()->route('managers.index')->with('message', 'Manager created successfully.');
        } catch(\Exception $e) {
            \Log::error('Error creating manager: ' . $e->getMessage());
            return back()->with('error', 'Failed to create manager. Please try again.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $manager)
    {
        if(auth()->user()->role !== 'admin') {
            abort(403);
        }
        
        $avatar_url = $manager->avatar_img ? Storage::url($manager->avatar_img) : null;
        
        return Inertia::render('manageManagers/Show', [
            'manager' => $manager,
            'avatar_url' => $avatar_url
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $manager)
    {
        if(auth()->user()->role !== 'admin') {
            abort(403);
        }
        
        return Inertia::render('manageManagers/Edit', [
            'manager' => $manager
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateManagerRequest $request, User $manager)
    {
        try {
            $validated = $request->validated();
    
            if (!empty($validated['password'])) {
                $validated['password'] = bcrypt($validated['password']);
            } else {
                unset($validated['password']);
            }
    
            // Handle avatar image update
            if ($request->hasFile('avatar_img')) {
                // Delete old image if it exists
                if ($manager->avatar_img && Storage::disk('public')->exists($manager->avatar_img)) {
                    Storage::disk('public')->delete($manager->avatar_img);
                }
    
                // Store new image
                $validated['avatar_img'] = $request->file('avatar_img')->store('managers', 'public');
            }

            $manager->update($validated);
    
            return redirect()->route('managers.index')->with('message', 'Manager updated successfully.');
        } catch (\Exception $e) {
            \Log::error('Error updating manager: ' . $e->getMessage());
            return back()->with('error', 'Failed to update manager. Please try again.');
        }
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $manager)
    {
        try {
            if(auth()->user()->role !== 'admin') {
                abort(403);
            }
            
            if ($manager->avatar_img) {
                Storage::disk('public')->delete($manager->avatar_img);
            }
            
            $manager->delete();
            return redirect()->route('managers.index')->with('message', 'Manager deleted successfully.');
        } catch(\Exception $e) {
            \Log::error('Error deleting manager: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete manager. Please try again.');
        }
    }
}
