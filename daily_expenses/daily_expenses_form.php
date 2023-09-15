<?php
session_start();

// Check if user is not logged in, then redirect to login page
if (!isset($_SESSION["username"])) {
    header("Location: ../login.html");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Expenses - Finance App</title>
    <link rel="stylesheet" href="css/sytles.css">
</head>
<body>
    <header>
        <div class="logo">
            <img src="../images/Logo.png" alt="Mafkoora Logo">
        </div>
        <nav>
            <ul>
                <li><a href="../monthly_accumulative_report/monthly_accumulative_report.php">Accumulative Report</a></li>
                <li><a href="../bills_numbers_report/bills_numbers_report.php">Bills & Numbers Report</a></li>
                <li><a href="../logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>

    <div class="content">
            <div class="form-container">
                <h1>Daily Expenses</h1>
                <form id="expense-form" action="store_expense.php" method="POST">
                    <div class="form-group">
                        <label for="cc_code">CC Code:</label>
                        <div class="cc-code-container">
                            <!-- dropdown field for code selection -->
                            <select id="cc_code" name="cc_code" required>
                                <!-- options here... -->
                                <option value="">Select Code</option>
                                <option value="01">01</option>
                                <option value="02">02</option>
                                <option value="03">03</option>
                                <option value="04">04</option>
                                <option value="05">05</option>
                                <option value="06">06</option>
                                <option value="07">07</option>
                                <option value="08">08</option>
                                <option value="09">09</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                                <option value="13">13</option>
                                <option value="14">14</option>
                                <option value="15">15</option>
                                <option value="16">16</option>
                                <option value="17">17</option>
                                <option value="18">18</option>
                                <option value="19">19</option>
                                <option value="20">20</option>
                                <option value="21">21</option>
                                <option value="22">22</option>
                                <option value="23">23</option>
                                <option value="24">24</option>
                                <option value="25">25</option>
                                <option value="26">26</option>
                                <option value="27">27</option>
                                <option value="28">28</option>
                                <option value="29">29</option>
                                <option value="30">30</option>
                                <option value="31">31</option>
                                <option value="32">32</option>
                                <option value="33">33</option>
                                <option value="34">34</option>
                                <option value="35">35</option>
                                <option value="36">36</option>
                                <option value="37">37</option>
                            </select>
                            <div class="cc-description" id="cc_description">Description</div>
                            <!-- Inside your form -->
                            <input type="hidden" id="cc_description_hidden" name="cc_description" value="">

                        </div>
                    </div>
                    <div class="form-group">
                        <label for="date">Date:</label>
                        <input type="date" id="date" name="date" value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="ref_bill">Ref/Bill No:</label>
                        <input type="text" id="ref_bill" name="ref_bill" required>
                    </div>
                    <div class="form-group">
                        <label for="amount">Amount:</label>
                        <input type="number" id="amount" name="amount" required>
                    </div>
                    <div class="form-group">
                        <label for="comment">Comment:</label>
                        <textarea id="comment" name="comment"></textarea>
                    </div>
                    <div class="button-container">
                        <button type="button" onclick="submitForm()">Submit</button>
                    </div>
                </form>
            </div>
            <div class="total-amount">
                <h2>Total Amount for Current Month</h2>
                <?php
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

                $currentMonth = date("m");
                $currentYear = date("Y");
                $firstDay = date("Y-m-01");
                $lastDay = date("Y-m-t");
                
                $positiveTotal = 0;
                $negativeTotal = 0;
                
                // Prepare and execute SQL query to retrieve data for the current month
                $sql = "SELECT amount, cc_description FROM daily_expenses WHERE date BETWEEN ? AND ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ss", $firstDay, $lastDay);
                $stmt->execute();
                $result = $stmt->get_result();
                
                while ($row = $result->fetch_assoc()) {
                    if (substr($row['cc_description'], -1) === '+') {
                        $positiveTotal += floatval($row['amount']);
                    } elseif (substr($row['cc_description'], -1) === '-') {
                        $negativeTotal += floatval($row['amount']);
                    }
                }
                
                $stmt->close();
                
                $totalAmount = $positiveTotal - $negativeTotal;
                
                echo '<p id="total-amount-value">+' . number_format($positiveTotal, 2) . '</p>';
                echo '<p id="total-amount-value">-' . number_format($negativeTotal, 2) . '</p>';
                ?>
            </div>
            
        </div>
        <div id="popup" class="popup">
            <p id="popup-message"></p>
        </div>
    
    <script src="js/scripts.js"></script>        

</body>
</html>
