<?php  //for backend checking
session_start();

header("Cache-Control: no-store, no-cache, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");
header('Content-Type: application/json');  // ← JSON for APIs

if (!isset($_SESSION['user']) || !isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit();
}

$required_role = $required_role ?? null;

if ($required_role && $_SESSION['role'] !== $required_role) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit();
}
?>