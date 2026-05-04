<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
include "db.php";

$sql = "SELECT id, fname, lname, pet_name, request_date, status 
        FROM adoption_requests
        ORDER BY request_date DESC";

$result = mysqli_query($conn, $sql);

$applications = [];
while ($row = mysqli_fetch_assoc($result)) {
    $applications[] = $row;
}

echo json_encode(["status" => "success", "applications" => $applications]);
?>