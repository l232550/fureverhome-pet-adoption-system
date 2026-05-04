<?php
include "db.php";
header("Content-Type: application/json");

$page  = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$limit = 6;
$offset = ($page - 1) * $limit;

// Read filters from POST body
$filters = json_decode(file_get_contents("php://input"), true);

$conditions = ["availability = 'available'"];
$params     = [];
$types      = "";

if (!empty($filters['search'])) {
    $conditions[] = "name LIKE ?";
    $params[]     = "%" . $filters['search'] . "%";
    $types       .= "s";
}

if (!empty($filters['type'])) {
    $conditions[] = "type = ?";
    $params[]     = $filters['type'];
    $types       .= "s";
}

if (!empty($filters['gender'])) {
    $conditions[] = "gender = ?";
    $params[]     = $filters['gender'];
    $types       .= "s";
}

if (!empty($filters['age'])) {
    switch ($filters['age']) {
        case "Under 1 Year":
            $conditions[] = "(age LIKE '%months%')";
            break;
        case "1-3 Years":
            $conditions[] = "(age REGEXP '^[1-3] year')";
            break;
        case "3-7 Years":
            $conditions[] = "(age REGEXP '^[3-7] year')";
            break;
        case "7+ Years":
            $conditions[] = "(CAST(age AS UNSIGNED) >= 7 AND age LIKE '%year%')";
            break;
    }
}

$where = implode(" AND ", $conditions);

// Count total for pagination
$countSql = "SELECT COUNT(*) as total FROM pets WHERE $where";
if ($params) {
    $stmt = $conn->prepare($countSql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $total = $stmt->get_result()->fetch_assoc()['total'];
} else {
    $total = $conn->query($countSql)->fetch_assoc()['total'];
}

// Fetch page of pets
$sql = "SELECT id, name, type, breed, age, gender, description, availability, image_url
        FROM pets WHERE $where ORDER BY id DESC LIMIT ? OFFSET ?";

$pageParams = array_merge($params, [$limit, $offset]);
$pageTypes  = $types . "ii";

$stmt = $conn->prepare($sql);
$stmt->bind_param($pageTypes, ...$pageParams);
$stmt->execute();
$result = $stmt->get_result();

$pets = [];
while ($row = $result->fetch_assoc()) {
    if (empty($row['image_url'])) {
        $row['image_url'] = "uploads/pets/default.jpg";
    }
    $pets[] = $row;
}

echo json_encode([
    "pets"       => $pets,
    "total"      => (int) $total,
    "page"       => $page,
    "limit"      => $limit,
    "totalPages" => (int) ceil($total / $limit)
]);
?>