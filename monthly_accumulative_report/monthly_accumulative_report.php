<?php
// include_once '../session_checker.php'; // Include the session checker

session_start();

// Check if user is not logged in, then redirect to login page
if (!isset($_SESSION["username"])) {
    header("Location: ../login.html");
    exit();
}


// Database configuration
$servername = "localhost";
$username = "root";
$password = ""; // Leave this empty if no password is set
$dbname = "finance_app";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$data = array(); // Initialize data array

if (isset($_GET["month"]) && !empty($_GET["month"])) {
    $selected_month = date('Y-m', strtotime($_GET["month"])); // Ensure correct date format

    // Prepare and execute SQL query to retrieve data for the selected month
    $sql = "SELECT cc_code, cc_description, SUM(amount) AS total_amount, COUNT(*) AS num_bills FROM daily_expenses WHERE DATE_FORMAT(date, '%Y-%m') = ? GROUP BY cc_code";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $selected_month);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    $stmt->close();
}

$conn->close();

// Include the HTML template
include('monthly_accumulative_report.html');
?>
