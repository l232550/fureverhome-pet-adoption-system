<?php
session_start();

if (!isset($_SESSION['user'])) {
    echo "unauthorized";
    exit();
}

$required_role = $_GET['role'];

if($required_role && $_SESSION['role'] !== $required_role){
    echo "unauthorized";
    exit();
}

echo "authorized";
?>