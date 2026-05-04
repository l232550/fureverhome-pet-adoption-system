<?php
session_start();
include "db.php";
header("Content-Type: application/json");

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "unauthorized"]);
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT message, is_read, created_at 
                         FROM notifications 
                         WHERE user_id = ? 
                         ORDER BY created_at DESC 
                         LIMIT 5");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$notifs = [];
while ($row = $result->fetch_assoc()) {
    $notifs[] = $row;
}

echo json_encode($notifs);
?>