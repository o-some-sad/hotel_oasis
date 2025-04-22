<?php

namespace App\Jobs;

use App\Mail\DailyLoginReminder;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable; // ✅ لازم دي
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendLoginReminderEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels; // ✅ لازم دي

    public $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function handle()
    {
        Log::info("Job started for user: " . $this->user->id);
        Mail::to($this->user->email)->send(new DailyLoginReminder($this->user));
        Log::info("Job completed for user: " . $this->user->id);
    }
}
