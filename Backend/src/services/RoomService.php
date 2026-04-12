<?php

namespace Src\Services;

use Src\Models\Room;
use Src\Exceptions\ValidationException;

class RoomService
{
    private Room $roomModel;

    public function __construct()
    {
        $this->roomModel = new Room();
    }

    public function getAllRooms()
    {
        $result = $this->roomModel->getAllRooms();
        return [
            'message' => 'All rooms retrieved',
            'data' => $result
        ];
    }

    public function getAvailableRooms($check_in, $check_out)
    {
        $allRooms = $this->roomModel->getAllRooms();
        $availableRooms = [];

        foreach ($allRooms as $room) {
            if ($this->roomModel->isAvailable($room['id'], $check_in, $check_out)) {
                $availableRooms[] = $room;
            }
        }
        if (empty($availableRooms)) {
            return [
                'message' => 'No available rooms retrieved',
                'data' => $availableRooms
            ];
        }
        return [
            'message' => 'All available rooms retrieved',
            'data' => $availableRooms
        ];
    }

    public function updateRoomStatus(string $roomNumber, string $status)
    {
        $this->roomModel->updateRoomStatus($roomNumber, $status);
        return [
            'message' => 'Room status updated',
            'data' => null
        ];
    }

    public function updateRoomPrice(float $price, string $roomCategory, int $beds)
    {
        $this->roomModel->updateRoomPrice($price, $roomCategory, $beds);
        return [
            'message' => 'Room price updated',
            'data' => null
        ];
    }

    public function createRoomType($room_category, $beds, $price) {
        $this->roomModel->createRoomType($room_category, $beds, $price);
        return [
            'message' => 'Room type created',
            'data' => null
        ];
    }

    public function createRoom($roomNumber, $roomTypeId, $imageUrl)
    {
        $this->roomModel->createRoom($roomNumber , $roomTypeId, $imageUrl);
        return [
            'message' => 'Room created',
            'data' => null
        ];
    }
}
?>