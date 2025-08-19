<?php

namespace App\Controllers;

use App\Core\Queue;
use App\Core\Request;
use App\Core\Response;
use App\Models\User;
use Jobs\GenerateReportJob;
use Services\EmailService;
use Services\Reports\ReportFactory;
use Services\SMSService;

class TestController
{
    protected EmailService $mail;
    protected SMSService $sms;
    protected Response $response;

    public function __construct(SMSService $sms, Response $response) {
        $this->response = $response;
        $this->sms = $sms;
    }

    public function mail()
    {
        $sent = $this->mail->send('senzu.dogi23@gmail.com', 'You are receiving a test mail', 'Mail Testing');

        if ($sent) {
            return json_encode(['success' => true, 'message' => 'Email sent successfully']);
        }
        return json_encode(['success' => false, 'message' => 'Email not sent']);
    }

    
    public function sms()
    {
        $sent = $this->sms->send('+233242737120', 'You are receiving a test SMS');

        if ($sent) {
            return json_encode(['success' => true, 'message' => 'Email sent successfully']);
        }
        return json_encode(['success' => false, 'message' => 'Email not sent']);
    }

    public function pdfReport()
    {
        $data = [
            ['ID', 'Name', 'Score'],
            [1, 'Alice', 95],
            [2, 'Bob', 88],
        ];

    $type = $_GET['type'] ?? 'pdf';
    $filePath = __DIR__ . "/../../storage/report_$type." . $type;

    $job = new GenerateReportJob($data, $type, $filePath);

    $queue = new Queue();
    $queue->push($job);

    return "Report generation queued. Check later at /report/download?type=$type";
    }

}
