<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ApprovedClientsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
    public function show(Request $request, string $id)
    {
        $client = User::find($id)->where("role", "client");
        if($request->user()->role != "admin") {
            $client = $client->where("approved_by", $request->user()->id);
        }
        $client = $client->get()->first();

        if($client == null) {
            return redirect()->route("approved-clients.index");
        }
        //TODO: show latest reservations for this client (waiting for reservations to be implemented)

        return Inertia::render("ApprovedClients/ShowDetails", compact("id", "client"));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
