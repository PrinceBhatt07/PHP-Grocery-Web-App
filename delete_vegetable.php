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
    $id = intval($_POST['id']); // Ensure ID is an integer
    if ($id) {
        $stmt = $conn->prepare("DELETE FROM vegetables WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            $response['success'] = true;
        } else {
            $response['error'] = $stmt->error;
        }

        $stmt->close();
    } else {
        $response['error'] = 'Invalid ID';
    }
}
$conn->close();
echo json_encode($response);
