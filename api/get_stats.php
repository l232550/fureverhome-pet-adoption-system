<?php
session_start();
include "db.php";

$stats = [];

// Pets in shelter
$result = mysqli_query($conn, "SELECT COUNT(*) as count FROM pets WHERE availability != 'adopted'");
$stats['pets_in_shelter'] = mysqli_fetch_assoc($result)['count'];

// Pending applications
$result = mysqli_query($conn, "SELECT COUNT(*) as count FROM adoption_requests WHERE status = 'pending'");
$stats['pending_applications'] = mysqli_fetch_assoc($result)['count'];

// Successfully adopted
$result = mysqli_query($conn, "SELECT COUNT(*) as count FROM adoption_requests WHERE status = 'approved'");
$stats['adopted'] = mysqli_fetch_assoc($result)['count'];

echo json_encode(["status" => "success", "stats" => $stats]);
?>