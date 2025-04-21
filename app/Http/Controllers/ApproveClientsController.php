<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ApproveClientsController extends Controller
{
    //
    public function approvedClients(Request $request)
    {
        //! Assumption: Admin can always see all approved clients
        $role = $request->user()->role;
        $users = User::where('role', 'client');
        if($role != 'admin') {
            $users =  $users->where('approved_by', $request->user()->id);
        }
        $users = $users->get()->toArray();

        return Inertia::render("ApprovedClients/index", [
            "data"=> $users
        ]);
    }
}
