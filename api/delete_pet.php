<?php
$required_role = 'shelter-staff';
include "check_auth_api.php";
include "db.php";

$pet_id   = intval($_POST['pet_id'] ?? 0);
$staff_id = $_SESSION['user_id'];

if ($pet_id <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid pet ID']);
    exit;
}

$check = $conn->query("SELECT id FROM pets WHERE id='$pet_id' AND created_by='$staff_id'");

if ($check->num_rows === 0) {
    echo json_encode(['status' => 'error', 'message' => 'Pet not found or permission denied']);
    exit;
}

if ($conn->query("DELETE FROM pets WHERE id='$pet_id' AND created_by='$staff_id'")) {
    echo json_encode(['status' => 'success', 'message' => 'Pet removed successfully!']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed: ' . $conn->error]);
}

$conn->close();
?>