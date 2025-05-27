<?php
header('Content-Type: application/json');

$host = "localhost";
$user = "root";
$password = "";
$dbname = "db_rusan";

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Database connection failed."]));
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["success" => false, "message" => "Invalid request method."]);
    exit;
}

$required_fields = [
    'empId',
    'empName',
    'designation',
    'department',
    'dateOfJoining',
    'resignationDate',
    'lastDayOfWork',
    'exitInterviewDate',
    'reasonLeavingSelect',
    'likes',
    'dislikes',
    'expectations',
    'managerName',
    'leadership',
    'communication',
    'professionalism',
    'teamwork',
    'transparency',
    'timemanagement',
    'empathy',
    'goodlistener',
    'micromanager',
    'consistentfeedback',
    'location1',
    'department1',
    'organization',
    'rejoinReason',
    'companyCulture',
    'benefits',
    'policies',
    'employeeDevelopment',
    'recognition'
];

// Check required fields except optional ones like recommend and recommendReason
foreach ($required_fields as $field) {
    if (!isset($_POST[$field]) || $_POST[$field] === '') {
        echo json_encode(["success" => false, "message" => "Missing field: $field"]);
        exit;
    }
}

// Sanitize inputs
$emp_id = $conn->real_escape_string($_POST['empId']);
$emp_name = $conn->real_escape_string($_POST['empName']);
$designation = $conn->real_escape_string($_POST['designation']);
$department = $conn->real_escape_string($_POST['department']);
$date_of_joining = $conn->real_escape_string($_POST['dateOfJoining']);
$resignation_date = $conn->real_escape_string($_POST['resignationDate']);
$last_day_of_work = $conn->real_escape_string($_POST['lastDayOfWork']);
$exit_interview_date = $conn->real_escape_string($_POST['exitInterviewDate']);

$reason_leaving = $conn->real_escape_string($_POST['reasonLeavingSelect']);
$reason_other = isset($_POST['reasonOther']) ? $conn->real_escape_string($_POST['reasonOther']) : '';
$likes = $conn->real_escape_string($_POST['likes']);
$dislikes = $conn->real_escape_string($_POST['dislikes']);
$expectations = $conn->real_escape_string($_POST['expectations']);

$manager_name = $conn->real_escape_string($_POST['managerName']);
$leadership = (int)$_POST['leadership'];
$communication = (int)$_POST['communication'];
$professionalism = (int)$_POST['professionalism'];
$teamwork = (int)$_POST['teamwork'];
$transparency = (int)$_POST['transparency'];
$timemanagement = (int)$_POST['timemanagement'];
$empathy = (int)$_POST['empathy'];
$goodlistener = (int)$_POST['goodlistener'];
$micromanager = (int)$_POST['micromanager'];
$consistentfeedback = (int)$_POST['consistentfeedback'];

$location_suggestion = $conn->real_escape_string($_POST['location1']);
$department_suggestion = $conn->real_escape_string($_POST['department1']);
$organization_suggestion = $conn->real_escape_string($_POST['organization']);
$rejoin = isset($_POST['rejoin']) ? $conn->real_escape_string($_POST['rejoin']) : '';
$rejoin_reason = $conn->real_escape_string($_POST['rejoinReason']);
$recommend = isset($_POST['recommend']) ? $conn->real_escape_string($_POST['recommend']) : '';
$recommend_reason = isset($_POST['recommendReason']) ? trim($_POST['recommendReason']) : '';

$company_culture_rating = (int)$_POST['companyCulture'];
$benefits_rating = (int)$_POST['benefits'];
$policies_rating = (int)$_POST['policies'];
$employee_development_rating = (int)$_POST['employeeDevelopment'];
$recognition_rating = (int)$_POST['recognition'];

// If recommend = 'no', recommendReason is required
if ($recommend === 'no' && $recommend_reason === '') {
    echo json_encode(["success" => false, "message" => "Missing field: recommendReason"]);
    exit;
}

$sql = "INSERT INTO rusan_feedback (
    emp_id, emp_name, designation, department,
    date_of_joining, resignation_date, last_day_of_work, exit_interview_date,
    reason_leaving, reason_other, likes, dislikes, expectations,
    manager_name, leadership, communication, professionalism,
    teamwork, transparency, timemanagement, empathy, goodlistener, micromanager, consistentfeedback,
    location_suggestion, department_suggestion, organization_suggestion,
    rejoin, rejoin_reason, recommend, recommend_reason,
    company_culture_rating, benefits_rating, policies_rating, employee_development_rating, recognition_rating
) VALUES (
    '$emp_id', '$emp_name', '$designation', '$department',
    '$date_of_joining', '$resignation_date', '$last_day_of_work', '$exit_interview_date',
    '$reason_leaving', '$reason_other', '$likes', '$dislikes', '$expectations',
    '$manager_name', $leadership, $communication, $professionalism,
    $teamwork, $transparency, $timemanagement, $empathy, $goodlistener, $micromanager, $consistentfeedback,
    '$location_suggestion', '$department_suggestion', '$organization_suggestion',
    '$rejoin', '$rejoin_reason', '$recommend', '$recommend_reason',
    $company_culture_rating, $benefits_rating, $policies_rating, $employee_development_rating, $recognition_rating
)";

if ($conn->query($sql) === TRUE) {
    echo json_encode(["success" => true, "message" => "Feedback submitted successfully."]);
} else {
    echo json_encode(["success" => false, "message" => "Database error: " . $conn->error]);
}

$conn->close();
