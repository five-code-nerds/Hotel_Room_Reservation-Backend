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
    public function getReservation()
    {
        $user = $_REQUEST['user'];
        if ($user) {
            $userId = $_REQUEST['user']->sub;
            $result = $this->reservationService->getReservationById($userId);
        } else {
            $data = json_decode(file_get_contents("php://input"), true);
            $email = trim($data['guest_email'] ?? null);
            if (!$email) {
                throw new ValidationException("Email is required");
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new ValidationException("Invalid email format");
            }
            $result = $this->reservationService->getReservationByEmail($email);
        }
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