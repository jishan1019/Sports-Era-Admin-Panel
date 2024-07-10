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

$sql = "SELECT * FROM settings";
$result = $conn->query($sql);

if (mysqli_num_rows($result) > 0) {
    $data = $result->fetch_all(MYSQLI_ASSOC);

    header('Content-type: application/json');
    echo json_encode($data);
} else {
    echo json_encode(["message" => "No Data found"]);
}