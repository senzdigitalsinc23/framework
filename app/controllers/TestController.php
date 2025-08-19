<?php

namespace App\Controllers;

use App\Core\Request;
use App\Core\Response;
use App\Models\User;
use Services\EmailService;

class TestController
{
    protected EmailService $mail;
    protected Response $response;

    public function __construct(EmailService $mail, Response $response) {
        $this->response = $response;
        $this->mail = $mail;
    }

    public function index()
    {
        $sent = $this->mail->send('senzu.dogi23@gmail.com', 'You are receiving a test mail', 'Mail Testing');

        if ($sent) {
            return json_encode(['success' => true, 'message' => 'Email sent successfully']);
        }
        return json_encode(['success' => false, 'message' => 'Email not sent']);
    }

}
