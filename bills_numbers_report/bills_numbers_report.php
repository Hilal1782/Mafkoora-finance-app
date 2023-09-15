<?php

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

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["start_date"]) && isset($_GET["end_date"])) {
    $start_date = $_GET["start_date"];
    $end_date = $_GET["end_date"];

    // Validate and sanitize input
    $start_date = mysqli_real_escape_string($conn, $start_date);
    $end_date = mysqli_real_escape_string($conn, $end_date);

    // Prepare and execute SQL query to retrieve data for the selected date range
    $sql = "SELECT *, ROW_NUMBER() OVER (ORDER BY date) AS sno FROM daily_expenses WHERE date BETWEEN ? AND ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $start_date, $end_date);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    $stmt->close();
}

$conn->close();

// Include the HTML template
include('bills_numbers_report.html');
?>
