<?php

namespace Src\Models;

use PDO;
use PDOException;
use Src\Core\Database;
use Src\Exceptions\DatabaseException;

class Reservation
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function getConnection() {
        return $this->db;
    }
    public function makeReservation($reservationData)
    {
        try {
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
                    'reservation_id' => $this->db->lastInsertId(),
                    'user_id' => $reservationData['user_id'],
                    'room_id' => $reservationData['room_id'],
                    'check_in' => $reservationData['check_in'],
                    'check_out' => $reservationData['check_out'],
                    'number_of_guests' => $reservationData['number_of_guests']
                ]
            ];
        } catch (PDOException $e) {
            throw new DatabaseException($e->getMessage());
        }
    }

    public function updateReservationStatus($reservationId, $status)
    {
        try {
            $stmt = $this->db->prepare(
            "UPDATE reservations
             SET status = ?
             WHERE id = ? AND status = 'booked'"
            );
            return $stmt->execute([$status, $reservationId]);
        } catch (PDOException $e) {
            throw new DatabaseException($e->getMessage());
        }
    }
    public function cancelReservationByUserId($reservationId)
    {
        try {
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
        } catch (PDOException $e) {
            throw new DatabaseException($e->getMessage());
        }
    }
    public function cancelReservationByUserEmail($guestEmail)
    {
        try {

            $stmt = $this->db->prepare(
                "UPDATE reservations
             SET status = 'cancelled'
             WHERE guest_email = ? AND status = 'booked'"
            );

            $stmt->execute([$guestEmail]);
            return [
                'data' => [
                    'guest_email' => $guestEmail
                ]
            ];
        } catch (PDOException $e) {
            throw new DatabaseException($e->getMessage());
        }
    }
    public function getReservationsByUserId($userId)
    {
        try {
            $stmt = $this->db->prepare(
                "SELECT 
             reservations.*, rooms.room_number, room_types.room_category, room_types.price
             FROM reservations
             JOIN rooms ON reservations.room_id = rooms.id
             JOIN room_types ON rooms.room_type_id = room_types.id
             WHERE reservations.user_id = ?
             ORDER BY reservations.check_in ASC"
            );

            $stmt->execute([$userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new DatabaseException($e->getMessage());
        }
    }

    public function getReservationByUserEmail($email)
    {
        try {
            $stmt = $this->db->prepare(
                "SELECT 
             reservations.*, rooms.room_number, room_types.room_category, room_types.price
             FROM reservations
             JOIN rooms ON reservations.room_id = rooms.id
             JOIN room_types ON rooms.room_type_id = room_types.id
             WHERE reservations.guest_email = ?
             ORDER BY reservations.check_in ASC"
            );

            $stmt->execute([$email]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new DatabaseException($e->getMessage());
        }
    }
    public function getAllReservations()
    {
        try {
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
            room_types.room_category,
            room_types.price
            FROM reservations
            JOIN rooms ON reservations.room_id = rooms.id
            JOIN room_types ON rooms.room_type_id = room_types.id
            LEFT JOIN users ON reservations.user_id = users.id
            ORDER BY reservations.check_in ASC"
            );

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new DatabaseException($e->getMessage());
        }
    }
}
