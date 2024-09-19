<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "grocerydash";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$response = ['success' => false];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : null;
    $vegetable_name = $conn->real_escape_string(trim($_POST['vegetable_name']));
    $size = $conn->real_escape_string(trim($_POST['size']));
    $price = $conn->real_escape_string(trim($_POST['price']));
    if ($id) {
        // Update existing row
        $stmt = $conn->prepare("UPDATE vegetables SET name = ?, size = ?, price = ? WHERE id = ?");
        $stmt->bind_param("sssi", $vegetable_name, $size, $price, $id);
    } else {
        // Insert new row
        $stmt = $conn->prepare("INSERT INTO vegetables (name, size, price) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $vegetable_name, $size, $price);
    }
    if ($stmt->execute()) {
        if (!$id) {
            $id = $conn->insert_id; // Get the ID of the newly inserted row
        }
        $response['success'] = true;
        $response['id'] = $id;
    } else {
        $response['error'] = $stmt->error;
    }
    $stmt->close();
}

$conn->close();

echo json_encode($response);
