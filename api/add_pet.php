<?php
$required_role = 'shelter-staff'; // set role before including
include "check_auth_api.php";     // handles session + auth
include "db.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
    exit;
}

// Inputs
$pet_name    = trim($conn->real_escape_string($_POST['pet_name']      ?? ''));
$species     = trim($conn->real_escape_string($_POST['species']       ?? ''));
$breed       = trim($conn->real_escape_string($_POST['breed']         ?? ''));
$age         = trim($conn->real_escape_string($_POST['age']           ?? ''));
$gender      = trim($conn->real_escape_string($_POST['gender']        ?? ''));
$health      = trim($conn->real_escape_string($_POST['health_status'] ?? ''));
$description = trim($conn->real_escape_string($_POST['description']   ?? ''));
$staff_id    = $_SESSION['user_id'];

// Required fields
if (empty($pet_name) || empty($species) || empty($age) || empty($gender)) {
    echo json_encode(['status' => 'error', 'message' => 'Please fill in all required fields.']);
    exit;
}

// Image upload
$image_path = null;

if (isset($_FILES['pet_image']) && $_FILES['pet_image']['error'] === UPLOAD_ERR_OK) {
    $file     = $_FILES['pet_image'];
    $ext      = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = 'pet_' . time() . '_' . uniqid() . '.' . $ext;

    if (move_uploaded_file($file['tmp_name'], '../uploads/pets/' . $filename)) {
        $image_path = 'uploads/pets/' . $filename;
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Image upload failed.']);
        exit;
    }
}

// Insert
$sql = "INSERT INTO pets (name, type, breed, age, gender, health_status, description, image_url, availability, created_by)
        VALUES ('$pet_name', '$species', '$breed', '$age', '$gender', '$health', '$description', '$image_path', 'available', '$staff_id')";

if ($conn->query($sql)) {
    $new_id = $conn->insert_id;
    echo json_encode([
        'status'  => 'success',
        'message' => 'Pet added successfully!',
        'pet'     => [
            'id'        => $new_id,
            'name'      => $pet_name,
            'type'      => $species,
            'breed'     => $breed,
            'age'       => $age,
            'availability' => 'available'
        ]
    ]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed: ' . $conn->error]);
}

$conn->close();
?>