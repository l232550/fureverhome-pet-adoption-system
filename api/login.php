<?php
include "db.php";

$email = $_POST['email'];
$password = $_POST['password'];
$role = $_POST['role'];

$sql = "SELECT * FROM users WHERE email='$email' AND password='$password' AND role='$role'";

$result = mysqli_query($conn, $sql);

if(mysqli_num_rows($result) > 0){
    session_start();
    $_SESSION['user'] = $email;
    $_SESSION['role'] = $role;
    echo $role;
} else {
    echo "error";
}
?>