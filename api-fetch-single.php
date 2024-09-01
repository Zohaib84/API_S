<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Get the data from the request
$data = json_decode(file_get_contents("php://input"), true);
$student_id = $data['id'] ?? null;

@include "config.php";

if ($student_id) {
    $sql = "SELECT * FROM student WHERE id = {$student_id}";
    $result = mysqli_query($conn, $sql) or die(json_encode(array('message' => 'SQL Query Failed.', 'status' => false)));

    if (mysqli_num_rows($result) > 0) {
        $output = mysqli_fetch_assoc($result); // Fetch the single row
        echo json_encode($output);
    } else {
        echo json_encode(array('message' => 'No Record Found.', 'status' => false));
    }
} else {
    echo json_encode(array('message' => 'Invalid ID.', 'status' => false));
}

// Close the database connection
mysqli_close($conn);
?>
