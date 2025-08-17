<?php
namespace App\Jobs;

use App\Core\Job;

class SendEmailJob implements Job
{
    protected string $to;
    protected string $message;

    public function __construct(string $to, string $message)
    {
        $this->to = $to;
        $this->message = $message;
    }

    public function handle(): void
    {
        // Here you can integrate real mailer, but we'll just log
        file_put_contents(__DIR__ . '/../../storage/logs/emails.log',
            "Sent email to {$this->to}: {$this->message}\n", FILE_APPEND);
    }
}
