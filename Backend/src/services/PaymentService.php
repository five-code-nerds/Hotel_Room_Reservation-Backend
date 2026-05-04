<?php

namespace Src\Services;

use Src\Core\PaymentHandler\PaymentHandler;
use Src\Exceptions\UnauthorizedException;
use Src\Exceptions\ValidationException;
use Src\Models\Payment;
use Src\Models\Room;
use Src\Models\Reservation;

class PaymentService
{
    private Payment $paymentModel;
    private Room $roomModel;
    private Reservation $reservationModel;

    public function __construct()
    {
        $this->paymentModel = new Payment();
        $this->roomModel = new Room();
        $this->reservationModel = new Reservation();
    }

 
    public function initiatePayment($reservation)
    {
        $amount = $this->roomModel->getRoomPrice($reservation['room_id']);

        $transaction_ref = "tx_" . uniqid();

        $this->paymentModel->createPayment([
            "reservation_id" => $reservation['reservation_id'],
            "amount" => $amount,
            "payment_method" => "chapa",
            "transaction_ref" => $transaction_ref,
            "payment_status" => "pending"
        ]);

        $checkoutUrl = $this->sendToChapa($transaction_ref,$amount);

        return [
            "transaction_ref" => $transaction_ref,
            "checkout_url" => $checkoutUrl
        ];
    }

    private function sendToChapa($transaction_ref, $amount)
    {
        $data = [
            "amount" => $amount,
            "currency" => "ETB",
            "email" => "test@example.com",
            "first_name" => "Guest/User",
            "tx_ref" => $transaction_ref,
            "callback_url" => "https://wapperjawed-saturnina-ontogenic.ngrok-free.dev/payment/webhook",
        ];

        $curlHandler = curl_init("https://api.chapa.co/v1/transaction/initialize");

        curl_setopt($curlHandler, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer " . PaymentHandler::getPaymentSecret(),
            "Content-Type: application/json"
        ]);

        curl_setopt($curlHandler, CURLOPT_POST, 1);
        curl_setopt($curlHandler, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($curlHandler, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($curlHandler);
        $result = json_decode($response, true);

        if (!isset($result['status']) || $result['status'] !== 'success') {
            throw new ValidationException("Payment initialization failed");
        }

        return $result['data']['checkout_url'];
    }

    public function handleWebhook($request)
    {
        $payload = $request->all();

        $secretHash = PaymentHandler::getPaymentSecretHash();
        $receivedSignature = $request->header('X-Chapa-Signature');

        if ($receivedSignature !== $secretHash) {
            throw new UnauthorizedException("Invalid Signature");
        }

        $transaction_ref = $payload['tx_ref'] ?? null;
        $status = $payload['status'] ?? null;

        if (!$transaction_ref) {
            throw new ValidationException("Invalid payload");
        }

        $verify = $this->verifyPayment($transaction_ref);

        if (!isset($verify['status']) || $verify['status'] !== 'success') {
            throw new ValidationException("Payment not verified");
        }

        $payment = $this->paymentModel->findPaymentByTransactionRef($transaction_ref);

        if ($payment) {
            $this->paymentModel->updatePaymentStatus(
                $transaction_ref,
                $status === "success" ? "paid" : "failed"
            );
            $this->reservationModel->updateReservationStatus(
                $payment["reservation_id"],
                $status === "success" ? "confirmed" : "failed"
            );
        }

        return [
            "status" => $status === "success" ? "success" : "Failed",
            "data" => $transaction_ref
        ];

    }

    private function verifyPayment($transaction_ref)
    {
        $curlHandler = curl_init("https://api.chapa.co/v1/transaction/verify/" . $transaction_ref);

        curl_setopt($curlHandler, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer " . PaymentHandler::getPaymentSecret()
        ]);

        curl_setopt($curlHandler, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($curlHandler);

        return json_decode($response, true);
    }

    public function refundPayment($transaction_ref){
        
    }
}
?>