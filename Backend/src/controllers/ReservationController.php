<?php

namespace Src\Controllers;

use Src\Exceptions\ValidationException;
use Src\Services\ReservationService;

class ReservationController
{
    private ReservationService $reservationService;

    public function __construct()
    {
        $this->reservationService = new ReservationService();
    }

    public function book()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        $user = $_REQUEST['user'] ?? "";
        $reservationData = [
            'room_id' => (int)$data['room_id'],
            'check_in' => htmlspecialchars(trim($data['check_in'])),
            'check_out' => htmlspecialchars(trim($data['check_out'])),
            'number_of_guests' => (int)$data['number_of_guests']
        ];

        if($user) {
            $data = [
                'user_id' => (int)$user->user_id,
                'guest_name' => null,
                'guest_email' => null,
                'guest_phone' => null
            ];
            $reservationData =  array_merge($reservationData, $data);
        } else {
            $data = [
                'user_id' => null,
                'guest_name' => htmlspecialchars(trim($data['guest_name'])),
                'guest_email' => filter_var(trim($data['guest_email']), FILTER_VALIDATE_EMAIL),
                'guest_phone' => htmlspecialchars(trim($data['guest_phone']))
            ];
            $reservationData = array_merge($reservationData, $data);
        }

        $result = $this->reservationService->makeReservation($reservationData);

        echo json_encode([
            'status' => 'success',
            'message' => $result['message'],
            'data' => $result['data']
        ]);
    }

    public function cancel()
    {
        $user = $_REQUEST['user'] ?? "";
        if ($user) {
            $reservation = $this->reservationService->getReservation($user->user_id);
            if (!$reservation) {
                throw new ValidationException("Reservation not found");
            }
            $reservation_id = $reservation['data']['reservation_id'];
            if ($reservation['data']['user_id'] !== $user->user_id) {
                throw new ValidationException("You are not allowed to cancel this reservation");
            }
            $result = $this->reservationService->cancelReservation((int)$reservation_id);
        } else  {
            $data = json_decode(file_get_contents("php://input"), true);
            $email = htmlspecialchars(trim($data['guest_email'])) ?? "";
            if (!$email) {
                throw new ValidationException("Email is required");
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new ValidationException("Invalid email format");
            }
            $reservation = $this->reservationService->getReservationByEmail($email);
            if (!$reservation) {
                throw new ValidationException("Reservation not found");
            }
            $reservation_id = $reservation['data']['reservation_id'];
            if ($reservation['data']['user_id'] !== $user->user_id) {
                throw new ValidationException("You are not allowed to cancel this reservation");
            }
            $result = $this->reservationService->cancelReservation((int)$reservation_id);
        }
        echo json_encode([
            'status' => 'success',
            'data' => $result['message']
        ]);
    }
    public function getReservation()
    {
        $userId = (int)$_REQUEST['user']->id ?? null;
        if (!$userId) {
            throw new ValidationException("User ID is required");
        }
        $result = $this->reservationService->getReservation($userId);

        echo json_encode([
            'status' => 'success',
            'message' => $result['message'],
            'data' => $result['data']
        ]);
    }
    public function getAllReservations()
    {
        $result = $this->reservationService->getAllReservations();

        echo json_encode([
            'status' => 'success',
            'message' => $result['message'],
            'data' => $result['data']
        ]);
    }
}
?>