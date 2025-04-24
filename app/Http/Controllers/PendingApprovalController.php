<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Inertia\Inertia;

class PendingApprovalController extends Controller
{
    public function index()
    {
        $pagination = User::where('approved_by', null)->where('role', 'client')
            ->paginate(10);
        
        return Inertia::render('pendingApproval/Index', [
            'pagination' => $pagination
        ]);
    }

    public function approve($id)
    {
        User::where('id', $id)->update([
            'approved_by' => auth()->id(),
            'is_approved' => true,
        ]);
    
        return redirect()->route('pending-approvals.index');
    }
    
  
}
