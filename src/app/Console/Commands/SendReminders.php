<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reminder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReminderEmail;
use Illuminate\Support\Facades\Log;

class SendReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Reminders';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $start = Carbon::now()->addDays(-1);
        $end = Carbon::now()->addDays(1);
        $reminders = Reminder::with('user')->whereBetween('remind_at', [$start, $end])->where('is_remind', false)->get();
        
        foreach ($reminders as $reminder) {
            Mail::to($reminder->user->email)->queue(new ReminderEmail($reminder));
            Reminder::find($reminder->id)->update(['is_remind' => true]);
        }
    }
}
