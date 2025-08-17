<?php

namespace App\Controllers\Api;

use App\Services\PaymentService;
use App\Core\Request;
use App\Core\Response;
use PDO;

class PaymentController
{
    public function initiate(Request $request, Response $response)
    {
        $data = $request->body();
        $gateway = PaymentService::gateway($data['gateway'] ?? 'momo');
        $result = $gateway->initiate($data);

        // Save to DB (optional)
        $pdo = db();
        $stmt = $pdo->prepare("INSERT INTO payments (user_id, amount, reference, status, gateway, purpose) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['user_id'],
            $data['amount'],
            $result['reference'],
            $result['status'],
            $data['gateway'],
            $data['purpose']
        ]);

        return $response->json($result);
    }

    public function verify(Request $request, Response $response)
    {
        $reference = $request->get('reference');
        $gatewayName = $request->get('gateway') ?? 'momo';
        $gateway = ServicesPaymentService::gateway($gatewayName);

        $result = $gateway->verify($reference);

        // Update DB
        $pdo = db();
        $stmt = $pdo->prepare("UPDATE payments SET status = ? WHERE reference = ?");
        $stmt->execute([$result['status'], $reference]);

        return $response->json($result);
    }
}
