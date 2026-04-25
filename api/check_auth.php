<?php
session_start();

$required_role = $_GET['role'];

if (!isset($_SESSION['user']) || $_SESSION['role'] !== $required_role) {
    echo "unauthorized";
    exit();
}
echo "authorized";
?>