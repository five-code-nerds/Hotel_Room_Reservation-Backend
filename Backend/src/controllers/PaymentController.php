<?php

namespace Src\Controllers;

use Src\Services\PaymentService;

class PaymentController
{
    private PaymentService $paymentService;

    public function __construct()
    {
        $this->paymentService = new PaymentService();
    }

    public function webhook()
    {
        $payload = json_decode(file_get_contents("php://input"), true);

        $result = $this->paymentService->handleWebhook($payload);

        http_response_code(200);
        echo json_encode([
            "status" => "success",
            "message" => $result['message'],
            "data" => $result['data']
        ]);
    }
}
?>
