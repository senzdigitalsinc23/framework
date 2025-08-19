<?php
namespace Services;

use App\Core\Config;
use App\Core\NotificationService;
use Twilio\Rest\Client;

class SMSService implements NotificationService
{
    protected Client $client;
    protected string $from;

    public function __construct()
    {
        Config::load(dirname(__DIR__) . '/config');

        // Get DB config from your config helper
        $from = Config::get('sms.host');
        $sid   = Config::get('sms.name');
        $token = Config::get('db_user');

        $this->client = new Client($sid, $token);
        $this->from = $from;
    }

    public function send(string $to, string $message): bool
    {
        try {
            $this->client->messages->create($to, [
                'from' => $this->from,
                'body' => $message
            ]);
            return true;
        } catch (\Exception $e) {
            error_log("SMS send failed: " . $e->getMessage());
            return false;
        }
    }
}
