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
        $page = $request->query("page",1);
        //! Assumption: Admin can always see all approved clients
        $role = $request->user()->role;
        $pagination = User::where('role', 'client');
        if($role != 'admin') {
            $pagination =  $pagination->where('approved_by', $request->user()->id);
        }
        $pagination = $pagination->paginate(10, ["*"], "page", $page);

        return Inertia::render("ApprovedClients/index", [
            "pagination"=> $pagination
        ]);
    }
}
