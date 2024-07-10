<?php
include "../auth/connect.php";

$conn = connect();
function validateToken($token)
{
    $decodedToken = base64_decode($token);
    $dataCheck = 'Authorization header not found';
    return $decodedToken === $dataCheck;
}

$headers = getallheaders();
$authHeader = isset($headers['Authorization']) ? $headers['Authorization'] : '';

if (!$authHeader) {
    http_response_code(401);
    echo json_encode(["message" => "Authorization header not found"]);
    exit;
}

if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
    $token = $matches[1];
} else {
    http_response_code(401);
    echo json_encode(["message" => "Invalid Authorization header format"]);
    exit;
}

if (!validateToken($token)) {
    http_response_code(401);
    echo json_encode(["message" => "Invalid token"]);
    exit;
}

// Get the category from the query parameter
$category = isset($_GET['category']) ? $_GET['category'] : '';

if (empty($category)) {
    http_response_code(400);
    echo json_encode(["message" => "Category parameter is missing"]);
    exit;
}

//QXV0aG9yaXphdGlvbiBoZWFkZXIgbm90IGZvdW5k


$sql = "SELECT * FROM channel WHERE category = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $category);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $data = $result->fetch_all(MYSQLI_ASSOC);

    header('Content-type: application/json');
    echo json_encode($data);
} else {
    echo json_encode(["message" => "No Data found"]);
}