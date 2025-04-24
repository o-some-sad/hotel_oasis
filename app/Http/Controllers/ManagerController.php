<?php

namespace App\Http\Controllers;
use App\Models\User;
use Inertia\Inertia;
use App\Http\Requests\StoreManagerRequest;
use App\Http\Requests\UpdateManagerRequest;

class ManagerController extends Controller
{
    //
    /**
     * Display a listing of the resource.
     * 
     * 
     * 
     * 
     */
    public function index()

    {
// Inertia::render('Manager/Index', [,
        // ])
        return 
            User::select([
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

    }

    public function store(StoreManagerRequest $request)
    {
        try {
            $validated = $request->validated();
            $validated['role'] = 'manager';
            $validated['mobile'] = (string) $validated['mobile'];
            $validated['password'] = bcrypt($validated['password']);
            $validated['avatar_img'] = $request->file('avatar_img')->store('avatars', 'public');
            
            User::create($validated);
            
            return redirect()->route('managers.index')->with('success', 'Manager created successfully.');
        } catch(\Exception $e) {
            \Log::error('Error creating manager: ' . $e->getMessage());
            return back()->with('error', 'Failed to create manager. Please try again.');
        }
    }
    public function show(User $manager)
    {
        return Inertia::render('Manager/Show', [
            'manager' => $manager,
        ]);
    }
    public function edit(User $manager)
    {
        return view('managers.edit', ['manager'=> $manager]);
    }
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
                if ($manager->avatar_img && \Storage::disk('public')->exists($manager->avatar_img)) {
                    \Storage::disk('public')->delete($manager->avatar_img);
                }
    
                // Store new image
                $validated['avatar_img'] = $request->file('avatar_img')->store('avatars', 'public');
            }
            \Log::info('Validated data:', $validated);

            $manager->update($validated);
    
            return redirect()->route('managers.index')->with('success', 'Manager updated successfully.');
        } catch (\Exception $e) {
            \Log::error('Error updating manager: ' . $e->getMessage());
            return back()->with('error', 'Failed to update manager. Please try again.');
        }
    }
    
    public function destroy(User $manager)
    {
        try {
            $manager->delete();
            return redirect()->route('managers.index')->with('success', 'Manager deleted successfully.');
        } catch(\Exception $e) {
            \Log::error('Error deleting manager: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete manager. Please try again.');
        }
    }


}
