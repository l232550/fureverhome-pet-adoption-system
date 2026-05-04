<?php
session_start();
include "db.php";
header("Content-Type: application/json");

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["error" => "unauthorized"]);
    exit;
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT aa.id, aa.status, aa.request_date AS created_at,
               p.name as pet_name, p.breed, p.id as pet_id
        FROM adoption_requests aa
        JOIN pets p ON aa.pet_id = p.id
        WHERE aa.user_id = ?
        ORDER BY aa.request_date DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$apps = [];
while ($row = $result->fetch_assoc()) {
    $apps[] = $row;
}

echo json_encode($apps);
?>