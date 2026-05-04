<?php
include "db.php";

if (!isset($_GET['id'])) {
    echo json_encode(["error" => "No pet ID"]);
    exit();
}

$id = intval($_GET['id']);

$stmt = $conn->prepare("SELECT * FROM pets WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo json_encode(["error" => "Pet not found"]);
    exit();
}

$pet = $result->fetch_assoc();

header("Content-Type: application/json");
echo json_encode($pet);
?>