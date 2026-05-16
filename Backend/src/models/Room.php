<?php

namespace Src\Models;

use PDO;
use PDOException;
use Src\Core\Database;
use Src\Exceptions\DatabaseException;

class Room
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function getAllRooms()
    {
        try {
            $stmt = $this->db->prepare(
                "SELECT
                rooms.id,
                rooms.room_number,
                rooms.status,
                room_types.room_category AS room_type,
                room_types.beds,
                room_types.price AS room_price
             FROM rooms
             JOIN room_types ON rooms.room_type_id = room_types.id"
            );

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new DatabaseException($e->getMessage());
        }
    }

    public function createRoomType($roomCategory, $beds, $price)
    {
        try {
            $stmt = $this->db->prepare(
                "INSERT INTO room_types (room_category, beds, price) VALUES (?, ?, ?)"
            );

            return $stmt->execute([$roomCategory, $beds, $price]);
        } catch (PDOException $e) {
            throw new DatabaseException($e->getMessage());
        }
    }

    public function createRoom($roomNumber, $roomTypeId, $imageUrl = null)
    {
        try {
            $stmt = $this->db->prepare(
                "INSERT INTO rooms (room_number, room_type_id, image_url) VALUES (?, ?, ?)"
            );

            return $stmt->execute([$roomNumber, $roomTypeId, $imageUrl]);
        } catch (PDOException $e) {
            throw new DatabaseException($e->getMessage());
        }
    }

    public function isAvailable($roomId, $checkIn, $checkOut)
    {
        try {
            $stmt = $this->db->prepare(
                "SELECT * FROM reservations
             WHERE room_id = ? AND status = 'booked'
               AND (check_in < ? AND check_out > ?)"
            );

            $stmt->execute([$roomId, $checkOut, $checkIn]);
            return $stmt->rowCount() === 0;
        } catch (PDOException $e) {
            throw new DatabaseException($e->getMessage());
        }
    }

    public function getRoomPrice($roomId) {
        try {
            $stmt = $this->db->prepare(
            "SELECT room_types.price FROM room_types
            JOIN rooms ON rooms.room_type_id = room_types.id WHERE rooms.id = ? LIMIT 1"
            );
            $stmt->execute([$roomId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new DatabaseException($e->getMessage());
        } 
    }
    public function getRoomStatus($roomId, $status) {
        try {
            $stmt = $this->db->prepare(
            "SELECT * FROM rooms
            WHERE id = ? AND status = ? LIMIT 1"
            );
            $stmt->execute([$roomId, $status]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new DatabaseException($e->getMessage());
        }
    }
    public function roomType($roomTypeId) {
        try {
            $stmt = $this->db->prepare(
                "SELECT * FROM room_types WHERE id = ? LIMIT 1"
            );
            $stmt->execute([$roomTypeId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new DatabaseException($e->getMessage());
        }
    }
    public function roomTypeExist($roomCategory, $beds)
    {
        try {
            $stmt = $this->db->prepare(
                "SELECT * FROM room_types WHERE room_category = ? AND beds = ? LIMIT 1"
            );
            $stmt->execute([$roomCategory, $beds]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new DatabaseException($e->getMessage());
        }
    }
    public function updateRoomStatus($roomNumber, $status)
    {
        try {
            $stmt = $this->db->prepare(
                "UPDATE rooms SET status = ? WHERE room_number = ?"
            );

            return $stmt->execute([$status, $roomNumber]);
        } catch (PDOException $e) {
            throw new DatabaseException($e->getMessage());
        }
    }

    public function updateRoomPrice($price, $roomCategory, $beds)
    {
        try {
            $stmt = $this->db->prepare(
                "UPDATE room_types SET price = ? WHERE room_category = ? AND beds = ?"
            );

            return $stmt->execute([$price, $roomCategory, $beds]);
        } catch (PDOException $e) {
            throw new DatabaseException($e->getMessage());
        }
    }
}
