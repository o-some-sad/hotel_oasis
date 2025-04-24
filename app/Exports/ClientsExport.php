<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
class ClientsExport implements FromCollection
{
    public function collection()
    {
        return User::where('role', 'client')->get(['id', 'name', 'email', 'created_at']);
    }

 
}