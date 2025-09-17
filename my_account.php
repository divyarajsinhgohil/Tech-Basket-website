<?php
session_start(); // Start the session

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}

// Database connection parameters
$servername = "localhost";
$username = "root"; // Replace with your database username
$password = "";     // Replace with your database password
$dbname = "project_2025";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user information from session
$email = $_SESSION['email'];

// Fetch user details from database
$sql = "SELECT * FROM info WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "<p>Error: User information not found.</p>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Process form data to update username and mobile number
    $newUsername = test_input($_POST['username']);
    $newMobileNumber = test_input($_POST['mobile_number']);
    
    // Validate mobile number format (ensure it's numeric and 10 digits)
    if (!preg_match("/^[0-9]{10}$/", $newMobileNumber)) {
        echo "<p style='color: red;'>Invalid mobile number format. Please enter a 10-digit number.</p>";
    } else {
        // Update the user's information in the database
        $updateSql = "UPDATE info SET username = ?, mobile_number = ? WHERE email = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("sss", $newUsername, $newMobileNumber, $email);
        
        if ($updateStmt->execute()) {
            echo "<p style='color: green;'>Information updated successfully.</p>";
            // Refresh user data
            $user['username'] = $newUsername;
            $user['mobile_number'] = $newMobileNumber;
        } else {
            echo "<p style='color: red;'>Error updating information: " . $updateStmt->error . "</p>";
        }
        $updateStmt->close();
    }
}

$stmt->close();
$conn->close();

// Function to sanitize input data
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Include previous styles from the original account.php here */

        .account-container {
            max-width: 600px;
            margin: 50px auto;
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        .input-group {
            margin-bottom: 15px;
            text-align: left;
        }

        .input-group label {
            font-size: 14px;
            font-weight: bold;
        }

        .input-group input {
            width: 100%;
            padding: 8px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .buttons {
            margin-top: 30px;
        }

        .btn {
            display: inline-block;
            margin: 10px 5px;
            padding: 12px 25px;
            text-decoration: none;
            background-color: #007bff;
            color: #fff;
            border-radius: 4px;
            transition: background-color 0.3s, transform 0.2s;
            font-size: 16px;
        }

        .btn.logout {
            background-color: #dc3545;
        }

        .btn:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }

    </style>
</head>
<body>
    <div class="account-container">
        <h1>My Account</h1>
        <p>Welcome, <strong><?php echo htmlspecialchars($user['username']); ?></strong>!</p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
        <p><strong>Member Since:</strong> <?php echo htmlspecialchars($user['created_at']); ?></p>

        <!-- Edit Form for Username and Mobile Number -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <div class="input-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
            </div>
            <div class="input-group">
                <label for="mobile_number">Mobile Number</label>
                <input type="text" id="mobile_number" name="mobile_number" value="<?php echo htmlspecialchars($user['mobile_number']); ?>" required>
            </div>
            <button type="submit" class="btn">Update Information</button>
        </form>

        <div class="buttons">
            <a href="index.php" class="btn"><i class="fas fa-home"></i> Back to Home</a>
            <a href="logout.php" class="btn logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </div>
</body>
</html>
