<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

// Get the data from the request
$data = json_decode(file_get_contents("php://input"), true);

$name = $data['name'] ?? null;
$age = $data['age'] ?? null;
$city = $data['city'] ?? null;
$subject = $data['subject'] ?? null;

@include "config.php";

// Check if all required fields are provided
if ($name && $age && $city && $subject) {
    // Use a prepared statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO student (name, age, city, subject) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("siss", $name, $age, $city, $subject);

    if ($stmt->execute()) {
        echo json_encode(array('message' => 'Student Record Inserted.', 'status' => true));
    } else {
        echo json_encode(array('message' => 'Student Record Not Inserted.', 'status' => false));
    }

    // Close the statement
    $stmt->close();
} else {
    echo json_encode(array('message' => 'Missing required fields.', 'status' => false));
}

// Close the database connection
mysqli_close($conn);
?>
