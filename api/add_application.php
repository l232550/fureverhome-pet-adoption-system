<?php
$required_role = 'adopter';
include "check_auth_api.php";
include "db.php";

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status'=>'error','message'=>'Method not allowed']);
    exit;
}

$pet_id     = intval($_POST['pet_id'] ?? 0);
$pet_name   = trim($conn->real_escape_string($_POST['pet_name'] ?? ''));
$first_name = trim($conn->real_escape_string($_POST['first_name'] ?? ''));
$last_name  = trim($conn->real_escape_string($_POST['last_name'] ?? ''));
$email      = trim($conn->real_escape_string($_POST['email'] ?? ''));
$phone      = trim($conn->real_escape_string($_POST['phone'] ?? ''));
$address    = trim($conn->real_escape_string($_POST['address'] ?? ''));
$home_type  = trim($conn->real_escape_string($_POST['home_type'] ?? ''));
$other_pets = trim($conn->real_escape_string($_POST['other_pets'] ?? ''));
$children   = trim($conn->real_escape_string($_POST['children'] ?? ''));
$reason     = trim($conn->real_escape_string($_POST['reason'] ?? ''));
$experience = trim($conn->real_escape_string($_POST['experience'] ?? ''));
$user_id    = $_SESSION['user_id'];

if (!$pet_id || !$first_name || !$last_name || !$email) {
    echo json_encode(['status'=>'error','message'=>'Please fill required fields']);
    exit;
}

$check = $conn->query("SELECT id FROM adoption_requests WHERE pet_id='$pet_id' AND user_id='$user_id'");
if ($check->num_rows > 0) {
    echo json_encode(['status'=>'error','message'=>'You already applied for this pet']);
    exit;
}

$sql = "INSERT INTO adoption_requests
        (pet_id, pet_name, fname, lname, email, phone, address, home_type, other_pets, children, reason, experience, status, user_id)
        VALUES
        ('$pet_id','$pet_name','$first_name','$last_name','$email','$phone','$address','$home_type','$other_pets','$children','$reason','$experience','pending','$user_id')";

if ($conn->query($sql)) {
    echo json_encode(['status'=>'success','message'=>'Application submitted successfully']);
} else {
    echo json_encode(['status'=>'error','message'=>$conn->error]);
}
?>