<?php

namespace Src\Controllers;

use DateTime;
use Src\Exceptions\ValidationException;
use Src\Services\PaymentService;
use Src\Services\ReservationService;

class BookingController
{
    private ReservationService $reservationService;
    private PaymentService $paymentService;

    public function __construct()
    {
        $this->reservationService = new ReservationService();
    }
    public function book()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        $user = $_REQUEST['user'];
        $reservationData = [
            'room_id' => trim($data['room_id'] ?? null),
            'check_in' => trim($data['check_in'] ?? null),
            'check_out' => trim($data['check_out'] ?? null),
            'number_of_guests' => trim($data['number_of_guests']) ?? null,
            'status' => 'pending_payment'
        ];
        if (!$reservationData['room_id'] || !filter_var($reservationData['room_id'], FILTER_VALIDATE_INT)) {
            throw new ValidationException("Room id is required and must be an integer");
        }
        if (!$reservationData['check_in']) {
            throw new ValidationException("Check in date is required");
        }
        if (!$reservationData['check_out']) {
            throw new ValidationException("Check out date is required");
        }

        $check_in_date = DateTime::createFromFormat('Y-m-d', $reservationData['check_in']);
        if (!$check_in_date || $check_in_date->format('Y-m-d') !== $reservationData['check_in']) {
            throw new ValidationException("Invalid date format and must be yyyy-mm-dd");
        }

        $check_out_date = DateTime::createFromFormat('Y-m-d', $reservationData['check_out']);
        if (!$check_out_date || $check_out_date->format('Y-m-d') !== $reservationData['check_out']) {
            throw new ValidationException("Invalid date format and must be yyyy-mm-dd");
        }

        if (!$reservationData['number_of_guests'] || !filter_var($reservationData['number_of_guests'], FILTER_VALIDATE_INT)) {
            throw new ValidationException("Number of guests is required and must be an integer");
        }
        if ($user) {
            $data = [
                'user_id' => $user->sub,
                'guest_name' => null,
                'guest_email' => null,
                'guest_phone' => null
            ];
            $reservationData =  array_merge($reservationData, $data);
        } else {
            $data = [
                'user_id' => null,
                'guest_name' => trim($data['guest_name'] ?? null),
                'guest_email' => trim($data['guest_email'] ?? null),
                'guest_phone' => trim($data['guest_phone'] ?? null)
            ];
            if (!$data['guest_name']) {
                throw new ValidationException("Guest name is required");
            }
            if (!$data['guest_email']) {
                throw new ValidationException("Guest email is required");
            }
            if (!$data['guest_phone']) {
                throw new ValidationException("Phone number is required");
            }
            if (!preg_match("/^[a-zA-Z ]+$/", $data['guest_name'])) {
                throw new ValidationException("Name must contain only letters and space");
            }
            if (!filter_var($data['guest_email'], FILTER_VALIDATE_EMAIL)) {
                throw new ValidationException("Invalid email format");
            }
            if (!preg_match("/^(09|07)\d{8}$/", $data['guest_phone'])) {
                throw new ValidationException("Phone must start with 09 or 07 and have 10 digits");
            }
            $reservationData = array_merge($reservationData, $data);
        }

        $result  = $this->reservationService->makeReservation($reservationData);

        http_response_code(302);
        echo json_encode([
            "message" => "Redirect to payment gateway",
            "checkout_url" => $result['checkout_url'],
            "transaction_ref" => $result['transaction_ref']
        ]);
    }

    public function cancel()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        $user = $_REQUEST['user'];
        if ($user) {
            $reservationData = ['user_id' => $user->sub];
            $result = $this->reservationService->cancelReservation($reservationData);
        } else {
            $email = trim($data['guest_email'] ?? null);
            if (!$email) {
                throw new ValidationException("Email is required");
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new ValidationException("Invalid email format");
            }
            $reservationData = ['guest_email' => $email];
            $result = $this->reservationService->cancelReservation($reservationData);
        }
        echo json_encode([
            'status' => 'success',
            'message' => $result['message'],
            'data' => $result['data']
        ]);
    }
    
}
