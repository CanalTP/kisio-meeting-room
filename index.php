<?php

include('common/core.php');

$rooms = json_decode(file_get_contents('config/rooms.json'));

echo $twig->render('list.twig', [
    'rooms' => $rooms->rooms
]);
