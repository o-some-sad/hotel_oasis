<?php

namespace App\Http\Controllers;
use Inertia\Inertia;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\ReceptionistResource;
use App\Http\Requests\ReceiptionistRequest;
use Illuminate\Database\Eloquent\SoftDeletes;



class ManageReceptionistController extends Controller
{
    public function index()
    {

            $receptionists = User::role('admin')->with('manager')->where('deleted_at', null)->get();
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
            return Inertia::render('manageReceptionists/Create');

    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(ReceiptionistRequest $request)
    {
        if (auth()->user()->role !== 'admin') {
        abort(403);
    }
        $validated = $request->validated();
        if ($request->hasFile('avatar_image')) {
            $path = $request->file('avatar_image')->store('receptionists', 'public');
            $validated['avatar_img'] = $path;
        } else {
            if ($validated['gender'] === 'Male') {
                $validated['avatar_img'] = 'https://media.istockphoto.com/id/1142192548/vector/man-avatar-profile-male-face-silhouette-or-icon-isolated-on-white-background-vector.jpg?s=612x612&w=0&k=20&c=DUKuRxK9OINHXt3_4m-GxraeoDDlhNuCbA9hp6FotFE=';
            } elseif ($validated['gender'] === 'Female') {
                $validated['avatar_img'] = 'https://www.shutterstock.com/image-vector/business-woman-icon-avatar-symbol-600nw-790518412.jpg';
            }
        }
        $validated['password'] = \Hash::make($validated['password']);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'national_id' => $validated['national_id'],
            'avatar_img' => $validated['avatar_img'],
            'gender' => $validated['gender'] ?? null,
            'created_by' => auth()->id(),
            'mobile' => $validated['mobile'],
            'country' => $validated['country'],
            'role'=>'receptionist',
        ]);

        return Inertia::render('manageReceptionists/Index');

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
    public function edit($id)
    {
//        $user = User::findOrFail($id);
//
//        return Inertia::render('Receptionists/Edit', [
//            'user' => $user,
//        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
        ]);

        $user = User::findOrFail($id);
        $user->update($request->only('name', 'email'));

        return redirect()->route('receptionists.index')->with('message', 'User updated successfully!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
