<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

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
        //
        
        // dd($managers);
        return Inertia::render('Manager/Index', [
            'managers' => User::select([
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
            ->paginate(10),
        ]);
    //     return response()->json(
    //         User::select([
    //             'id',
    //             'name',
    //             'email',
    //             'national_id',
    //             'avatar_img',
    //             'mobile',
    //             'country',
    //         ])->where('role', 'manager')
    //         ->latest()
    //         ->paginate(10)
    //     );
    // }
    }
}
