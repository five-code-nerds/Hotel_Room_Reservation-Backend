<?php

namespace Src\Models;
use Src\Core\Database;
use PDO;

class Reservation
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function makeReservation($reservationData)
    {
        $stmt = $this->db->prepare(
            "INSERT INTO reservations 
             (user_id, room_id, check_in, check_out, number_of_guests, guest_name, guest_email, guest_phone)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
        );

        $stmt->execute([
            $reservationData['user_id'],
            $reservationData['room_id'],
            $reservationData['check_in'],
            $reservationData['check_out'],
            $reservationData['number_of_guests'],
            $reservationData['guest_name'],
            $reservationData['guest_email'],
            $reservationData['guest_phone']
        ]);
        return [
            'data' => [
                'user_id' => $reservationData['user_id'],
                'room_id' => $reservationData['room_id'],
                'check_in' => $reservationData['check_in'],
                'check_out' => $reservationData['check_out'],
                'number_of_guests' => $reservationData['number_of_guests']
            ]
        ];
    }

    public function cancelReservation($reservationId)
    {
        $stmt = $this->db->prepare(
            "UPDATE reservations
             SET status = 'cancelled'
             WHERE id = ? AND status = 'booked'"
        );

        $stmt->execute([$reservationId]);
        return [
            'data' => [
                'reservation_id' => $reservationId
            ]
        ];
    }

    public function getReservationsByUserId($userId)
    {
        $stmt = $this->db->prepare(
            "SELECT 
             reservations.*, rooms.room_number, room_types.room_catagory, room_types.price
             FROM reservations
             JOIN rooms ON reservations.room_id = rooms.id
             JOIN room_types ON rooms.room_type_id = room_types.id
             WHERE reservations.user_id = ?
             ORDER BY reservations.check_in ASC"
        );

        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getReservationByUserEmail($email) {
        $stmt = $this->db->prepare(
            "SELECT 
             reservations.*, rooms.room_number, room_types.room_catagory, room_types.price
             FROM reservations
             JOIN rooms ON reservations.room_id = rooms.id
             JOIN room_types ON rooms.room_type_id = room_types.id
             WHERE reservations.guest_email = ?
             ORDER BY reservations.check_in ASC"
        );

        $stmt->execute([$email]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllReservations()
    {
        $stmt = $this->db->prepare(
            "SELECT 
            reservations.id AS reservation_id,
            reservations.check_in,
            reservations.check_out,
            reservations.number_of_guests,
            COALESCE(users.name, reservations.guest_name) AS guest_name,
            COALESCE(users.email, reservations.guest_email) AS guest_email,
            COALESCE(users.phone, reservations.guest_phone) AS guest_phone,
            rooms.room_number,
            room_types.room_catagory,
            room_types.price
            FROM reservations
            JOIN rooms ON reservations.room_id = rooms.id
            JOIN room_types ON rooms.room_type_id = room_types.id
            LEFT JOIN users ON reservations.user_id = users.id
            ORDER BY reservations.check_in ASC"
        );

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
