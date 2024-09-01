<?php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: DELETE');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

// Get the data from the request
$data = json_decode(file_get_contents("php://input"), true);
$student_id = $data['id'] ?? null;

@include "config.php";

    $sql = "delete from student WHERE id = {$student_id}";
    if (mysqli_query($conn, $sql)) {
        echo json_encode(array('message' => 'Student record deleted.', 'status' => true));
    } else {
        echo json_encode(array('message' => 'Student record not deleted.', 'status' => false));
    }


// Close the database connection
mysqli_close($conn);
?>
