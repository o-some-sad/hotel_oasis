<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\StoreManagerRequest;

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

    }
    public function create()
    {
        return Inertia::render('Manager/Create');
    }
    public function store(StoreManagerRequest $request)
    {
    }

}
