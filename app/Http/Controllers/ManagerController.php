<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;

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
        $managers = User::where('role', 'manager')->get();
        dd($managers);
        return view('manager.index', compact('managers'));
    }
}
