<?php   //for frontend checking
session_start();

// Prevent browser from caching protected pages
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

if (!isset($_SESSION['user'])) {
    echo "unauthorized";
    exit();
}

$required_role = $_GET['role'] ?? null;

if($required_role && $_SESSION['role'] !== $required_role){
    echo "unauthorized";
    exit();
}

echo "authorized";
?>