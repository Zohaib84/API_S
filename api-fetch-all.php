<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$data = json_decode(file_get_contents("php://input"), true);
$student_id = $data['id'] ?? null; // Use null coalescing operator to avoid undefined index error
@include "config.php";

$sql = "SELECT * FROM student";
$result = mysqli_query($conn, $sql) or die(json_encode(array('message' => 'SQL Query Failed.', 'status' => false)));

if (mysqli_num_rows($result) > 0) {
    $output = mysqli_fetch_all($result, MYSQLI_ASSOC); // Use MYSQLI_ASSOC with mysqli
    echo json_encode($output);
} else {
    echo json_encode(array('message' => 'No Record Found.', 'status' => false));
}

// Close the database connection if not already closed
mysqli_close($conn);
?>
