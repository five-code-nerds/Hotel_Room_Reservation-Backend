<?php

namespace Src\Services;

use Src\Models\Reservation;
use Src\Models\Room;

class ReservationService
{
    private Room $roomModel;
    private Reservation $reservationModel;
    public function __construct()
    {
        $this->roomModel = new Room();
        $this->reservationModel = new Reservation();
    }

    public function getReservation($userId)
    {
        $result = $this->reservationModel->getReservationsByUserId($userId);
        return [
            'message' => 'User reservation info retrieved',
            'data' => $result
        ];
    }
    public function getReservationByEmail($email)
    {
        $result = $this->reservationModel->getReservationsByUserId($email);
        return [
            'message' => 'User reservation info retrieved',
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
        if (!$this->roomModel->isAvailable($reservationData['room_id'], $reservationData['check_in'], $reservationData['check_out'])) {
            return [
                'message' => 'Room is not available for the selected dates',
                'data' => null
            ];
        }

        $result = $this->reservationModel->makeReservation($reservationData);
        return [
            'message' => 'Room booked successfully',
            'data'=> $result['data']
        ];
    }
    public function cancelReservation($reservation_id)
    {
        $result = $this->reservationModel->cancelReservation($reservation_id);
        return [
            'message' => 'Reservation cancelled',
            'data' => $result['data']
        ];
    }
}
