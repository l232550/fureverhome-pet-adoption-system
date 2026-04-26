<?php
$required_role = 'shelter-staff';
include "check_auth_api.php";
include "db.php";

$staff_id = $_SESSION['user_id'];

$result = $conn->query("SELECT * FROM pets WHERE created_by='$staff_id' ORDER BY created_at DESC");

$pets = [];
while($row = $result->fetch_assoc()){
    $pets[] = $row;
}

echo json_encode(['status' => 'success', 'pets' => $pets]);
$conn->close();
?>