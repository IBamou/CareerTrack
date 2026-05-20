<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestEmailCommand extends Command
{
    protected $signature = 'test:email {email? : The email address to send the test to}';

    protected $description = 'Send a test email to verify mail configuration';

    public function handle()
    {
        $email = $this->argument('email') ?? User::first()?->email;

        if (!$email) {
            $this->error('No email provided and no users found.');
            return 1;
        }

        Mail::raw('This is a test email from CareerTrack. Your mail configuration is working!', function ($message) use ($email) {
            $message->to($email)
                ->subject('CareerTrack: Test Email');
        });

        $this->info("Test email sent to {$email}!");
    }
}
