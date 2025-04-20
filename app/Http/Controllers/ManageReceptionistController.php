<?php

namespace App\Http\Controllers;
use Inertia\Inertia;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\ReceptionistResource;


class ManageReceptionistController extends Controller
{
    public function index()
    {
        $receptionists = User::role('admin')->with('manager')->get();
        print ($receptionists);
        if ($receptionists->isEmpty()) {
            return Inertia::render('manageReceptionists/Index', [
                'receptionists' => [],
                'message' => 'No receptionists found.'
            ]);
        }
        return Inertia::render('manageReceptionists/Index', [
            'receptionists' => ReceptionistResource::collection($receptionists)->toArray(request())
        ]);

    }

    public function create()
    {

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
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
