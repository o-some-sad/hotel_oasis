<?php

namespace App\Http\Controllers;
use App\Http\Requests\StoreReceiptionistRequest;
use App\Http\Requests\UpdateReceptionistRequest;
use App\Http\Resources\Resources\ReceptionistResource;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ManageReceptionistController extends Controller
{
    public function index() {
        if (auth()->user()->role === 'admin' || auth()->user()->role === 'manager') {

            $receptionists = User::where('role', 'receptionist')->with('manager')->paginate(10);
            
            if ($receptionists->isEmpty()) {
                return Inertia::render('manageReceptionists/Index', [
                    'pagination' => [
                        'data' => [],
                        'links' => [],
                        'current_page' => 1,
                        'last_page' => 1,
                        'from' => null,
                        'to' => null,
                        'total' => 0,
                    ],
                    'message' => 'There are no receptionist yet.',
                ]);
            }

            // Transform the receptionists using the resource
            $resourceCollection = ReceptionistResource::collection($receptionists);
            
            // Get the pagination information directly from Laravel's paginator
            return Inertia::render('manageReceptionists/Index', [
                'pagination' => [
                    'data' => $resourceCollection->toArray(request()),
                    'links' => $receptionists->toArray()['links'],
                    'current_page' => $receptionists->currentPage(),
                    'last_page' => $receptionists->lastPage(),
                    'from' => $receptionists->firstItem(),
                    'to' => $receptionists->lastItem(),
                    'total' => $receptionists->total(),
                ],
            ]);
        }
        abort(403);
    }


    public function create()
    {
        if(auth()->user()->role ==='admin' || auth()->user()->role ==='manager') {
            return Inertia::render('manageReceptionists/Create');
        }
        abort(403);
    }


    public function show($id)
    {
        if (auth()->user()->role === 'admin' || auth()->user()->role === 'manager') {
            $receptionist = User::where('id', $id)
                ->where('role', 'receptionist')
                ->with('manager')
                ->first();

            if (!$receptionist) {
                abort(404);
            }

            return Inertia::render('manageReceptionists/Show', [
                'receptionist' => $receptionist,
            ]);


        }

        abort(403);
    }


    public function store(StoreReceiptionistRequest $request)
    {
        $request->authorize();
        $validated = $request->validated();
        if ($request->hasFile('avatar_image')) {
            $path = $request->file('avatar_image')->store('receptionists', 'public');
            $validated['avatar_img'] = $path;
        }

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'national_id' => $validated['national_id'],
            'avatar_img' => $validated['avatar_img'] ,
            'gender' => $validated['gender']  ,
            'created_by' => auth()->id(),
            'mobile' => $validated['mobile'],
            'country' => $validated['country'],
            'role' => 'receptionist',
            'approved_by' => auth()->id()

        ]);

        return Inertia::location(route('receptionists.index'));
    }


    public function edit($id)
    {
        $receptionist = User::find($id);
        if (!$receptionist) {
            return Inertia::render('manageReceptionists/Index', [
                'message' => 'Receptionist not found.',
            ]);
        }
        return Inertia::render('manageReceptionists/Edit', [
            'receptionist' => $receptionist,
        ]);
    }



    public function update(UpdateReceptionistRequest $request, $id)
    {
        $request->authorize();
        $receptionist = User::findOrFail($id);
        $validated = $request->validated();

        if (isset($validated['banned_at'])) {
            $bannedAt = $validated['banned_at'] === null ? null : now();
            $receptionist->update(['banned_at' => $bannedAt]);

            $message = $bannedAt ? 'Receptionist banned successfully.' : 'Receptionist unbanned successfully.';
            return redirect()->route('receptionists.index')->with('message', $message);
        }

        if ($request->hasFile('avatar_img')) {
            if ($receptionist->avatar_img) {
                Storage::disk('public')->delete($receptionist->avatar_img);
            }

            $path = $request->file('avatar_img')->store('receptionists', 'public');
            $validated['avatar_img'] = $path;
        }else{
            unset( $validated['avatar_img'] );
        }

        $dirtyData = collect($validated)->filter(function ($value, $key) use ($receptionist) {
            return $receptionist->$key !== $value;
        })->toArray();

        if (!empty($dirtyData)) {
            $receptionist->update($dirtyData);
        }

        return redirect()->route('receptionists.index')->with('message', 'Receptionist updated successfully.');
    }

    public function destroy($id,UpdateReceptionistRequest $request)
    {
        $request->authorize();
        $receptionist = User::findOrFail($id);
        if ($receptionist->avatar_img) {
            Storage::disk('public')->delete($receptionist->avatar_img);
        }
        $receptionist->delete();
        return redirect()->route('receptionists.index')->with('message', 'Receptionist deleted successfully.');
    }

    public function ban($id)
    {
        $receptionist = User::findOrFail($id);

        if (auth()->user()->role !== 'admin' && auth()->id() !== $receptionist->created_by) {
            return redirect()->back()->with('error', 'You are not authorized to ban this receptionist.');
        }

        $receptionist->ban([
            'comment' => 'Banned by ' . auth()->user()->name,
            'created_by_id' => auth()->id(),
        ]);

        return redirect()->route('receptionists.index')->with('message', 'Receptionist banned successfully.');
    }

    public function unban($id)
    {
        $receptionist = User::findOrFail($id);

        if (auth()->user()->role !== 'admin' && auth()->id() !== $receptionist->created_by) {
            return redirect()->back()->with('error', 'You are not authorized to unban this receptionist.');
        }

        $receptionist->unban();

        return redirect()->route('receptionists.index')->with('message', 'Receptionist unbanned successfully.');
    }
}
