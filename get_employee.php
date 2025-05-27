<?php
header('Content-Type: application/json');

// DB connection
$host = "localhost";
$user = "root";
$password = "";
$dbname = "db_rusan";

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Database connection failed."]);
    exit;
}

if (isset($_GET['empId'])) {
    $empId = $conn->real_escape_string($_GET['empId']);
    $sql = "SELECT * FROM `employee_details` WHERE emp_id = '$empId'";
    $result = $conn->query($sql);

    if ($result && $row = $result->fetch_assoc()) {
        echo json_encode([
            "success" => true,
            "emp_name" => $row['emp_name'],
            "designation" => $row['designation'],
            "department" => $row['department'],
            "date_of_joining" => $row['date_of_joining'],
            "resignation_date" => $row['resignation_date'],
            "last_day_of_work" => $row['last_day_of_work'],
            "exit_interview_date" => $row['exit_interview_date']
        ]);
    } else {
        echo json_encode(["success" => false, "message" => "Employee not found."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request."]);
}

$conn->close();
