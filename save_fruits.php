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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate input data
    $id = isset($_POST['id']) ? intval($_POST['id']) : null;
    $fruit_name = $conn->real_escape_string(trim($_POST['fruit_name']));
    $veggies = $conn->real_escape_string(trim($_POST['veggies']));
    $size = $conn->real_escape_string(trim($_POST['size']));
    $price = $conn->real_escape_string(trim($_POST['price']));

    if ($id) {
        // Update existing row
        $stmt = $conn->prepare("UPDATE fruits SET name = ?, veggies = ?, size = ?, price = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $fruit_name, $veggies, $size, $price, $id);
    } else {
        // Insert new row
        $stmt = $conn->prepare("INSERT INTO fruits (name, veggies, size, price) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $fruit_name, $veggies, $size, $price);
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




