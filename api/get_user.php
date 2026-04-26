<?php
$required_role = 'shelter-staff';
include "check_auth_api.php";
include "db.php";

$user_id = $_SESSION['user_id'];

$result = $conn->query("SELECT * FROM users WHERE id='$user_id'");
$user = $result->fetch_assoc();

echo json_encode([
    'status' => 'success',
    'user' => [
        'fname' => $user['fname'],
        'lname' => $user['lname'],
        'email' => $user['email'],
        'phone' => $user['phone'],
        'address' => $user['address']
    ]
]);
$conn->close();
?>