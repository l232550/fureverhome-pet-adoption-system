<?php

include "db.php";

$email = $_POST['email'];
$password = $_POST['password'];
$fname  = $_POST['fname'];
$lname  = $_POST['lname'];
$phone  = $_POST['phone'];
$address  = $_POST['address'];
$role = $_POST['role'];

$sql="INSERT INTO users (email,password,fname,lname,phone,address,role) VALUES ('$email','$password','$fname','$lname','$phone','$address','$role')";

$result = mysqli_query($conn,$sql);

    if ($result) {
        echo "success";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
?>