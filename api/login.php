<?php
session_start();
include "db.php";

$email = $_POST['email'];
$password = $_POST['password'];
$role = $_POST['role'];

$sql = "SELECT * FROM users WHERE email='$email' AND password='$password' AND role='$role'";

$result = mysqli_query($conn, $sql);

if(mysqli_num_rows($result) > 0){
    $user = mysqli_fetch_assoc($result);
    $_SESSION['user'] = $email;
    $_SESSION['role'] = $role;
    $_SESSION['user_id'] = $user['id'];  // ✅ correct
    echo $role;
} else {
    echo "error";
}
?>