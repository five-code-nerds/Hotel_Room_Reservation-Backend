<?php

namespace Src\Services;

use Src\Exceptions\ValidationException;
use Src\Models\Reservation;
use Src\Models\Payment;
use Src\Models\Room;

class ReservationService
{
    private Room $roomModel;
    private Reservation $reservationModel;
    private Payment $paymentModel;
    private PaymentService $paymentService;

    public function __construct()
    {
        $this->roomModel = new Room();
        $this->reservationModel = new Reservation();
        $this->paymentModel = new Payment();
        $this->paymentService = new PaymentService();
    }

    public function getReservationById($userId)
    {
        $result = $this->reservationModel->getReservationByUserId($userId);
        return [
            'message' => $result ? 'User reservation info retrieved' : 'No reservation found',
            'data' => $result
        ];
    }
    public function getReservationByEmail($email)
    {
        $result = $this->reservationModel->getReservationByUserEmail($email);
        return [
            'message' => $result ? 'User reservation info retrieved' : 'No reservation found',
            'data' => $result
        ];
    }
    public function getAllReservations() {
        $result = $this->reservationModel->getAllReservations();
        return [
            'message' => 'All reservations infos retrieved',
            'data' => $result
        ];
    }
    public function makeReservation($reservationData)
    {
        if ($reservationData['user_id']) {
            $isUserReservedBefore = $this->reservationModel->getReservationByUserId($reservationData['user_id']);
            if ($isUserReservedBefore) {
                throw new ValidationException("User already has reservations");
        
            }
        } else {
            $isUserReservedBefore = $this->reservationModel->getReservationByUserEmail($reservationData['guest_email']);
            if ($isUserReservedBefore) {
                throw new ValidationException("User already has reservations");
            }
        }
        $room = $this->roomModel->isRoomAvailable($reservationData['room_id']);
        if (!$room || $room['status'] !== 'available') {
            throw new ValidationException("Room is not currently available");
        }
        if (!$this->roomModel->canBeReserved($reservationData['room_id'], $reservationData['check_in'], $reservationData['check_out'])) {
            throw new ValidationException("Room is not available for the selected dates");
        }

        return $this->reservationModel->makeReservation($reservationData);

    }
    public function cancelReservation($reservationData)
    {
        if ($reservationData['user_id']) {

            $result = $this->reservationModel->cancelReservationByUserId(
                $reservationData['user_id']
            );

            $payment = $this->paymentModel->findPaymentByTransactionRef(
                $result['transaction_ref'] ?? null
            );
        } else {

            $result = $this->reservationModel->cancelReservationByUserEmail(
                $reservationData['guest_email']
            );

            $payment = $this->paymentModel->findPaymentByTransactionRef(
                $result['transaction_ref'] ?? null
            );
        }

        if ($payment && $payment["payment_status"] === "paid") {
            $this->paymentService->refundPayment($payment["transaction_ref"]);
        }

        return [
            'message' => $result ? 'Reservation cancelled' : 'No active reservation found',
            'data' => $result ? ($reservationData['user_id'] ?? $reservationData['guest_email']) : null
        ];
    }

    public function confirmReservation($reservationId)
    {
        $this->reservationModel->updateReservationStatus($reservationId, "confirmed");
    }
}
?>