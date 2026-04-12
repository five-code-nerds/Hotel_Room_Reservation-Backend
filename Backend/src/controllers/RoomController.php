<?php

namespace Src\Controllers;

use Src\Exceptions\ValidationException;
use Src\Services\RoomService;

class RoomController
{
    private RoomService $roomService;

    public function __construct()
    {
        $this->roomService = new RoomService();
    }
    public function getAllRooms()
    {
        $result = $this->roomService->getAllRooms();

        echo json_encode([
            'status' => 'success',
            'message' => $result['message'],
            'data' => $result['data']
        ]);
    }
    public function getAvailableRooms()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        $check_in = htmlspecialchars(trim($data['check_in'])) ?? null;
        $check_out = htmlspecialchars(trim($data['check_out'])) ?? null;

        if (!$check_in || !$check_out) {
            throw new ValidationException("Check-in and Check-out dates are required");
        }

        $result = $this->roomService->getAvailableRooms($check_in, $check_out);

        echo json_encode([
            'status' => 'success',
            'message' => $result['message'],
            'data' => $result['data']
        ]);
    }

    public function updateRoomPrice()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        $price = htmlspecialchars(trim($data['price'])) ?? null;
        $room_catagory = htmlspecialchars(trim($data['room_catagory'])) ?? null;
        $beds = htmlspecialchars(trim($data['beds'])) ?? null;

        if (!$price) {
            throw new ValidationException("Price is required");
        }

        if (!$room_catagory) {
            throw new ValidationException("Room category is required");
        }

        if (!$beds) {
            throw new ValidationException("Number of beds is required and must be an integer");
        }

        $result = $this->roomService->updateRoomPrice((float)$price, $room_catagory, (int)$beds);

        echo json_encode([
            'status' => 'success',
            'message' => $result['message'],
            'data' => $result['data'],
        ]);
    }
    public function disableRoom()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        $status = htmlspecialchars(trim($data['status'])) ?? null;
        $roomNumber = htmlspecialchars(trim($data['room_number'])) ?? null;


        if (!$roomNumber) {
            throw new ValidationException("Room number is required");
        }

        if (!$status || !in_array($status, ['available', 'unavailable'])) {
            throw new ValidationException("Status is required and must be 'available' or 'unavailable'");
        }

        $result = $this->roomService->updateRoomStatus($roomNumber, $status);

        echo json_encode([
            'status' => 'success',
            'message' => $result['message'],
            'data' => $result['data']
        ]);
    }
    public function createRoomType() {

        $data = json_decode(file_get_contents("php://input"), true);

        $price = htmlspecialchars(trim($data['price'])) ?? null ?? null;
        $room_catagory = htmlspecialchars(trim($data['room_catagory'])) ?? null;
        $beds = htmlspecialchars(trim($data['beds'])) ?? null;

        if (!$price || !is_numeric($price)) {
            throw new ValidationException("Price is required");
        }
        if (!$room_catagory) {
            throw new ValidationException("Room category is required");
        }
        if (!$beds || !is_numeric($beds)) {
            throw new ValidationException("Number of beds is required and must be an integer");
        }
        $result = $this->roomService->createRoomType($room_catagory, $beds, $price);
        echo json_encode([
            'status' => 'success',
            'message' => $result['message'],
            'data' => $result['data']
        ]);
    }
    public function createRoom() {

        $data = json_decode(file_get_contents("php://input"), true);

        $roomNumber = htmlspecialchars(trim($data['room_number'])) ?? null ?? null;
        $roomTypeId = htmlspecialchars(trim($data['room_type_id'])) ?? null;
        $imageUrl = htmlspecialchars(trim($data['image_url'])) ?? null;

        if (!$roomNumber || !is_numeric($roomNumber)) {
            throw new ValidationException("Room number is required");
        }
        if (!$roomTypeId || !is_numeric($roomTypeId)) {
            throw new ValidationException("Room type id is required");
        }
        if ($imageUrl && !filter_var($imageUrl, FILTER_VALIDATE_URL)) {
            throw new ValidationException('Invalid url');
        }
        $result = $this->roomService->createRoom($roomNumber, $roomTypeId, $imageUrl);

        echo json_encode([
            'status' => 'success',
            'message' => $result['message'],
            'data' => $result['data']
        ]);
    
    }
}