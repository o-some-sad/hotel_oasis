<?php

namespace App\Console\Commands;

use App\Jobs\SendLoginReminderEmail;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\Dispatchable;


class SendLoginReminderEmails extends Command
{
    protected $signature = 'send:login-reminders';
    protected $description = 'Send login reminder emails to users who haven\'t logged in for a month';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $users = User::where('last_login_in', '<', now()->subMonth())->get();


        foreach ($users as $user) {
            SendLoginReminderEmail::dispatch($user);
        }

        $this->info('Login reminders sent to ' . $users->count() . ' users.');
    }
}
