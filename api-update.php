<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: PUT');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

// Get the data from the request
$data = json_decode(file_get_contents("php://input"), true);

$student_id = $data['id'] ?? null; // Ensure you have an ID to update the specific record
$name = $data['name'] ?? null;
$age = $data['age'] ?? null;
$city = $data['city'] ?? null;
$subject = $data['subject'] ?? null;

@include "config.php";

// Check if the ID and all required fields are provided
if ($student_id && $name && $age && $city && $subject) {
    // Use a prepared statement to prevent SQL injection
    $stmt = $conn->prepare("UPDATE student SET name = ?, age = ?, city = ?, subject = ? WHERE id = ?");
    $stmt->bind_param("sissi", $name, $age, $city, $subject, $student_id);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo json_encode(array('message' => 'Student Record Updated.', 'status' => true));
        } else {
            echo json_encode(array('message' => 'No Record Updated. The provided ID may not exist.', 'status' => false));
        }
    } else {
        echo json_encode(array('message' => 'SQL Query Failed.', 'status' => false));
    }

    // Close the statement
    $stmt->close();
} else {
    echo json_encode(array('message' => 'Missing required fields.', 'status' => false));
}

// Close the database connection
mysqli_close($conn);
?>
