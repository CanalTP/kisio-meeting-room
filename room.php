<?php

include('common/core.php');

function getScheduleSlots($data)
{
    for ($i = 0; $i < strlen($data->daily_availability); $i++) {
        $slots[] = [
            'availability' => ($data->daily_availability[$i] == '0')
        ];
    }

    return $slots;
}

$email = $_GET['email'];
$rooms = json_decode(file_get_contents('config/rooms.json'));

foreach ($rooms->rooms as $room) {
    if ($room->email == $email) {
        $roomView = $room;
    }
}

$client = new GuzzleHttp\Client();
$res = $client->get('http://apoc.zibok.org:8000/booking/room/'.$email);

$roomData = json_decode($res->getBody()->getContents());

$timeSlots = getScheduleSlots($roomData);

$roomView->slots = $timeSlots;
$roomView->available = $roomData->is_room_available;

$datetime = new DateTime();
$minutes = $datetime->format('H') * 60 + $datetime->format('i');
$cursor_position = ($minutes / 1440) * 100;

if (isset($roomData->current_meeting)) {
    $roomView->organizer = $roomData->current_meeting->organizer;
}
if (isset($roomData->suggested_rooms)) {
    $roomView->suggested_rooms = $roomData->suggested_rooms;
}

echo $twig->render('room.twig', [
    'room' => $roomView,
    'cursor_position' => $cursor_position
]);
