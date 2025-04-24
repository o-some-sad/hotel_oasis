<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Notifications\ClientApproved;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ClientsExport;


class ClientController extends Controller
{
    /**
     * Display a listing of clients.
     */
    public function index()
    {
        if(auth()->user()->role === 'admin' || auth()->user()->role === 'manager') {
            $clients = User::where('role', 'client')->with('manager')->paginate(10);
            return Inertia::render('manageClients/Index', [
                'pagination' => $clients,
                'clients' => $clients->items(),
            ]);
        }
        
        abort(403);
    }

    /**
     * Show the form for creating a new client.
     */
    public function create()
    {  
        if(auth()->user()->role === 'admin' || auth()->user()->role === 'manager') {
            return Inertia::render('manageClients/Create');
        }
        
        abort(403);
    }

    /**
     * Store a newly created client in storage.
     */
    public function store(StoreClientRequest $request)
    {
        $validated = $request->validated();

        if ($request->hasFile('avatar_image')) {
            $path = $request->file('avatar_image')->store('clients', 'public');
            $validated['avatar_img'] = $path;
        }

        $validated['password'] = Hash::make($validated['password']);
        $validated['role'] = 'client';
        $validated['created_by'] = auth()->id();
        $validated['is_approved'] = false;
        $validated['approved_by'] = null;

        User::create($validated);

        return redirect()->route('clients.index')->with('message', 'Client created successfully.');
    }

    /**
     * Display the specified client.
     */
    public function show($id)
    {
        if(auth()->user()->role !== 'admin' && auth()->user()->role !== 'manager') {
            abort(403);
        }
        
        $client = User::findOrFail($id);
        $avatar_url = Storage::url($client->avatar_img);
        
        return Inertia::render('manageClients/Show', [
            'client' => $client,
            'avatar_url'=> $avatar_url
        ]);
    }

    /**
     * Show the form for editing the specified client.
     */
    public function edit($id)
    {
        if(auth()->user()->role !== 'admin' && auth()->user()->role !== 'manager') {
            abort(403);
        }
        
        $client = User::findOrFail($id);
        
        return Inertia::render('manageClients/Edit', [
            'client' => $client
        ]);
    }

    /**
     * Update the specified client in storage.
     */
    public function update(UpdateClientRequest $request, $id)
    {
        $client = User::findOrFail($id);
        $validated = $request->validated();

        if ($request->hasFile('avatar_image')) {
            if ($client->avatar_img) {
                Storage::disk('public')->delete($client->avatar_img);
            }
            
            $path = $request->file('avatar_image')->store('clients', 'public');
            $validated['avatar_img'] = $path;
        }
        
        // Only update the password if it was provided
        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        } else {
            unset($validated['password']);
        }

        $client->update($validated);

        return redirect()->route('clients.index')->with('message', 'Client updated successfully.');
    }

    /**
     * Remove the specified client from storage.
     */
    public function destroy($id)
    {
        if(auth()->user()->role !== 'admin' && auth()->user()->role !== 'manager') {
            abort(403);
        }
        
        $client = User::findOrFail($id);
        
        if ($client->avatar_img) {
            Storage::disk('public')->delete($client->avatar_img);
        }
        
        $client->delete();

        return redirect()->route('clients.index')->with('message', 'Client deleted successfully.');
    }

    /**
     * Ban a client
     */
    public function ban($id)
    {
        if(auth()->user()->role !== 'admin' && auth()->user()->role !== 'manager') {
            abort(403);
        }
        
        $client = User::findOrFail($id);
        
        // Ban the client with a comment
        $client->ban([
            'comment' => 'Banned by ' . auth()->user()->name,
            'created_by_id' => auth()->id(),
        ]);

        return redirect()->route('clients.show', compact("id"))->with('message', 'Client banned successfully.');
    }

    /**
     * Unban a client
     */
    public function unban($id)
    {
        if(auth()->user()->role !== 'admin' && auth()->user()->role !== 'manager') {
            abort(403);
        }
        
        $client = User::findOrFail($id);
        
        // Unban the client
        $client->unban();

        return redirect()->route('clients.show', compact("id"))->with('message', 'Client banned successfully.');
    }

    public function approve(User $client)
    {
        try {
            // if(auth()->user()->role !== 'admin' && auth()->user()->role !== 'manager') {
            //     abort(403);
            // }
            $client->update(['is_approved' => true]);
            $client->notify(new ClientApproved());
            return redirect()->route('clients.index')->with('success', 'Client approved successfully.');
        } catch(\Exception $e) {
            \Log::error('Error approving client: ' . $e->getMessage());
            return back()->with('error', 'Failed to approve client. Please try again.');
        }
    }
    public function dummyApproveView($id)
    {
        $client = User::findOrFail($id);
        return view('clients.dummy-approve', compact('client'));
    }
    public function export()
    {
         if(auth()->user()->role !== 'admin' && auth()->user()->role !== 'manager') {
            abort(403);
        }
        return Excel::download(new ClientsExport, 'clients.xlsx');
    } 

}


     



