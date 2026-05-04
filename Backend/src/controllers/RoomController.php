<?php

namespace Src\Controllers;

use DateTime;
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
        $check_in = trim($data['check_in'] ?? null);
        $check_out = trim($data['check_out'] ?? null);

        if (!$check_in || !$check_out) {
            throw new ValidationException("Both Check-in and Check-out dates are required");
        }
        $check_in_date = DateTime::createFromFormat('Y-m-d', $check_in);
        if ($check_in_date->format('Y-m-d')) {
            throw new ValidationException("Invalid date format and must be yyyy-mm-dd");
        }

        $check_out_date = DateTime::createFromFormat('Y-m-d', $check_out);
        if ($check_out_date->format('Y-m-d')) {
            throw new ValidationException("Invalid date format and must be yyyy-mm-dd");
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
        $price = trim($data['price'] ?? null);
        $room_catagory = trim($data['room_catagory'] ?? null);
        $beds = trim($data['beds'] ?? null);

        if (!$price || !filter_var($price, FILTER_VALIDATE_FLOAT)) {
            throw new ValidationException("Price is required and must be a number");
        }

        if (!$room_catagory || !in_array($room_catagory, ['normal', 'vip'])) {
            throw new ValidationException("Room category is required and must be either normal or vip");
        }

        if (!$beds || !filter_var($beds, FILTER_VALIDATE_INT)) {
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
        $status = trim($data['status'] ?? null);
        $roomNumber = trim($data['room_number'] ?? null);


        if (!$roomNumber || !filter_var($roomNumber, FILTER_VALIDATE_INT)) {
            throw new ValidationException("Room number is required and must be an interger");
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

        $price = trim($data['price'] ?? null);
        $room_catagory = trim($data['room_catagory'] ?? null);
        $beds = trim($data['beds'] ?? null);

        if (!$price || !filter_var($price, FILTER_VALIDATE_FLOAT)) {
            throw new ValidationException("Price is required and must be a number");
        }
        if (!$room_catagory || !in_array($room_catagory, ['normal', 'vip'])) {
            throw new ValidationException("Room category is required");
        }
        if (!$beds || !filter_var($beds, FILTER_VALIDATE_INT)) {
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

        $roomNumber = trim($data['room_number'] ?? null);
        $roomTypeId = trim($data['room_type_id'] ?? null);
        $imageUrl = trim($data['image_url'] ?? null);

        if (!$roomNumber || !filter_var($roomNumber, FILTER_VALIDATE_INT)) {
            throw new ValidationException("Room number is required and must be an interger");
        }
        if (!$roomTypeId || !filter_var($roomTypeId, FILTER_VALIDATE_INT)) {
            throw new ValidationException("Room type id is required and must be an interger");
        }
        // if (!$imageUrl) {
        //     throw new ValidationException('Image url is required');
        // }
        // if (!filter_var($imageUrl, FILTER_VALIDATE_URL)) {
        //     throw new ValidationException('Invalid url');
        // }
        $result = $this->roomService->createRoom($roomNumber, $roomTypeId, $imageUrl);

        echo json_encode([
            'status' => 'success',
            'message' => $result['message'],
            'data' => $result['data']
        ]);
    
    }
}
