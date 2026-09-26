<?php

session_start();

require_once "config/Database.php";
require_once "classes/Notification.php";

if (!isset($_SESSION['user_id'])) {
    echo json_encode([]);
    exit;
}

$database = new Database();
$pdo = $database->connect();

$notification = new Notification($pdo);

$notifications = $notification->getByUser($_SESSION['user_id']);

$unseen = array_filter($notifications, function ($item) {
    return $item['is_read'] == 0;
});

header('Content-Type: application/json');
echo json_encode(array_values($unseen));