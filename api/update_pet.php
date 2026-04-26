<?php
$required_role = 'shelter-staff';
include "check_auth_api.php";
include "db.php";

$pet_id      = intval($_POST['pet_id'] ?? 0);
$staff_id    = $_SESSION['user_id'];
$pet_name    = trim($conn->real_escape_string($_POST['pet_name']      ?? ''));
$species     = trim($conn->real_escape_string($_POST['species']       ?? ''));
$breed       = trim($conn->real_escape_string($_POST['breed']         ?? ''));
$age         = trim($conn->real_escape_string($_POST['age']           ?? ''));
$gender      = trim($conn->real_escape_string($_POST['gender']        ?? ''));
$health      = trim($conn->real_escape_string($_POST['health_status'] ?? ''));
$description = trim($conn->real_escape_string($_POST['description']   ?? ''));
$availability = trim($conn->real_escape_string($_POST['availability'] ?? 'available'));

if($pet_id <= 0 || empty($pet_name)){
    echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
    exit;
}

// Check pet belongs to this staff member
$check = $conn->query("SELECT id FROM pets WHERE id='$pet_id' AND created_by='$staff_id'");
if($check->num_rows === 0){
    echo json_encode(['status' => 'error', 'message' => 'Pet not found or permission denied']);
    exit;
}

$sql = "UPDATE pets SET 
    name='$pet_name',
    type='$species',
    breed='$breed',
    age='$age',
    gender='$gender',
    health_status='$health',
    description='$description',
    availability='$availability'
    WHERE id='$pet_id' AND created_by='$staff_id'";

if($conn->query($sql)){
    echo json_encode(['status' => 'success', 'message' => 'Pet updated successfully!']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed: ' . $conn->error]);
}
$conn->close();
?>