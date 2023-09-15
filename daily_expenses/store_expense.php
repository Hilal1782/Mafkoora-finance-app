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
$password = "";
$dbname = "finance_app";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cc_code = $_POST["cc_code"];
    $cc_description = $_POST["cc_description"];
    $date = $_POST["date"];
    $ref_bill = $_POST["ref_bill"];
    $amount = $_POST["amount"];
    $comment = $_POST["comment"];

    // Validate and sanitize input
    $cc_code = mysqli_real_escape_string($conn, $cc_code);
    $cc_description = mysqli_real_escape_string($conn, $cc_description);
    $ref_bill = mysqli_real_escape_string($conn, $ref_bill);
    $amount = floatval($amount); // Convert to float
    $comment = mysqli_real_escape_string($conn, $comment);

    // Validate date format (YYYY-MM-DD)
    if (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $date) || !strtotime($date)) {
        echo "Invalid date format.";
        exit;
    }

    // Prepare and execute SQL query
    $sql = "INSERT INTO daily_expenses (cc_code, cc_description, date, ref_bill, amount, comment) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $cc_code , $cc_description, $date, $ref_bill, $amount, $comment);

    // Check if query was successful
    if ($stmt->execute()) {
        $response = array("success" => true, "message" => "Data submitted successfully.");
    } else {
        $response = array("success" => false, "message" => "Error: " . $stmt->error);
    }
    
    $stmt->close();

    // Send response (in JSON format) back to the front end
    header("Content-Type: application/json");
    echo json_encode($response);
}

$conn->close();
?>
