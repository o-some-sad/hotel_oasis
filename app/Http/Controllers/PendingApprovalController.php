<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Inertia\Inertia;

class PendingApprovalController extends Controller
{
    public function index()
    {
        $pagination = User::where('approved_by', null)
            ->paginate(10);
        
        return Inertia::render('pendingApproval/Index', [
            'pagination' => $pagination
        ]);
    }

    public function approve($id)
    {
        $user = User::find($id);
        $user->approved_by = auth()->user()->id;
        $user->save();
    }
}
