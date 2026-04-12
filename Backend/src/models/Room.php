<?php

namespace Src\Models;
use Src\Core\Database;
use PDO;

class Room
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function getAllRooms()
    {
        $stmt = $this->db->prepare(
            "SELECT
                rooms.id,
                rooms.room_number,
                rooms.status,
                room_types.room_catagory AS room_type,
                room_types.beds,
                room_types.price AS room_price
             FROM rooms
             JOIN room_types ON rooms.room_type_id = room_types.id"
        );

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createRoomType($roomCategory, $beds, $price)
    {
        $stmt = $this->db->prepare(
            "INSERT INTO room_types (room_catagory, beds, price) VALUES (?, ?, ?)"
        );

        return $stmt->execute([$roomCategory, $beds, $price]);
    }

    public function createRoom($roomNumber, $roomTypeId, $imageUrl = null)
    {
        $stmt = $this->db->prepare(
            "INSERT INTO rooms (room_number, room_type_id, image_url) VALUES (?, ?, ?)"
        );

        return $stmt->execute([$roomNumber, $roomTypeId, $imageUrl]);
    }

    public function isAvailable($roomId, $checkIn, $checkOut)
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM reservations
             WHERE room_id = ? AND status = 'booked'
               AND (check_in < ? AND check_out > ?)"
        );

        $stmt->execute([$roomId, $checkOut, $checkIn]);
        return $stmt->rowCount() === 0;
    }

    public function updateRoomStatus($roomNumber, $status)
    {
        $stmt = $this->db->prepare(
            "UPDATE rooms SET status = ? WHERE room_number = ?"
        );

        return $stmt->execute([$status, $roomNumber]);
    }

    public function updateRoomPrice($price, $roomCategory, $beds)
    {
        $stmt = $this->db->prepare(
            "UPDATE room_types SET price = ? WHERE room_catagory = ? AND beds = ?"
        );

        return $stmt->execute([$price, $roomCategory, $beds]);
    }
}
