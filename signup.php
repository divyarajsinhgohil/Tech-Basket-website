<?php
// Initialize variables
$username = $email = $password = $mobile_number = "";
$usernameErr = $emailErr = $passwordErr = $mobile_numberErr = "";

// Process form data when the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate username
    if (empty($_POST["username"])) {
        $usernameErr = "Username is required";
    } else {
        $username = test_input($_POST["username"]);
    }

    // Validate email
    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
    } else {
        $email = test_input($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
        }
    }

    // Validate mobile number
    if (empty($_POST["mobile_number"])) {
        $mobile_numberErr = "Mobile number is required";
    } else {
        $mobile_number = test_input($_POST["mobile_number"]);
        // Validate the mobile number (simple check for numeric and length)
        if (!preg_match("/^[0-9]{10}$/", $mobile_number)) {
            $mobile_numberErr = "Invalid mobile number format (must be 10 digits)";
        }
    }

    // Validate password
    if (empty($_POST["password"])) {
        $passwordErr = "Password is required";
    } else {
        $password = test_input($_POST["password"]);
    }

    // If no errors, proceed with database insertion
    if (empty($usernameErr) && empty($emailErr) && empty($mobile_numberErr) && empty($passwordErr)) {
        // Database connection parameters
        $servername = "localhost";
        $dbUsername = "root"; // Replace with your database username
        $dbPassword = "";     // Replace with your database password
        $dbname = "project_2025";

        // Create connection
        $conn = new mysqli($servername, $dbUsername, $dbPassword, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Prepare and bind
        $stmt = $conn->prepare("INSERT INTO info (username, email, mobile_number, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $email, $mobile_number, $hashedPassword);

        // Execute the statement
        if ($stmt->execute()) {
            // Redirect to index.php after successful signup
            header("Location: index.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close the statement and connection
        $stmt->close();
        $conn->close();
    }
}

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
    <title>Signup Page</title>
    <style>
        /* General styling */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f7f7f7;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        /* Signup container */
        .signup-form {
            background-color: #ffffff;
            width: 100%;
            max-width: 400px;
            padding: 30px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            box-sizing: border-box;
        }

        /* Headings */
        .signup-form h1 {
            font-size: 24px;
            font-weight: 500;
            margin-bottom: 20px;
            text-align: center;
            color: #333;
        }

        /* Input groups */
        .input-group {
            margin-bottom: 20px;
        }

        .input-group label {
            font-size: 14px;
            font-weight: bold;
            display: block;
            margin-bottom: 8px;
            color: #555;
        }

        .input-group input {
            width: 100%;
            padding: 12px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            transition: border-color 0.3s;
        }

        .input-group input:focus {
            border-color: #4caf50;
            outline: none;
        }

        /* Error messages */
        .error {
            font-size: 12px;
            color: #e74c3c;
            margin-top: 5px;
        }

        /* Button */
        button.submit-btn {
            width: 100%;
            padding: 12px;
            background-color: #4caf50;
            border: none;
            color: #fff;
            font-size: 16px;
            font-weight: bold;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button.submit-btn:hover {
            background-color: #45a049;
        }

        /* Additional text */
        .additional-text {
            margin-top: 20px;
            font-size: 14px;
            color: #555;
            text-align: center;
        }

        .additional-text a {
            text-decoration: none;
            color: #0066c0;
            font-weight: bold;
        }

        .additional-text a:hover {
            text-decoration: underline;
        }

        /* Responsive Design */
        @media (max-width: 480px) {
            .signup-form {
                padding: 20px;
            }

            button.submit-btn {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" class="signup-form">
        <h1>Create Account</h1>
        <div class="input-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" value="<?php echo $username; ?>" required>
            <span class="error"><?php echo $usernameErr; ?></span>
        </div>
        <div class="input-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo $email; ?>" required>
            <span class="error"><?php echo $emailErr; ?></span>
        </div>
        <div class="input-group">
            <label for="mobile_number">Mobile Number</label>
            <input type="text" id="mobile_number" name="mobile_number" value="<?php echo $mobile_number; ?>" required>
            <span class="error"><?php echo $mobile_numberErr; ?></span>
        </div>
        <div class="input-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
            <span class="error"><?php echo $passwordErr; ?></span>
        </div>
        <button type="submit" class="submit-btn">Sign Up</button>
        <div class="additional-text">
            Already have an account? <a href="login.php">Login here</a>
        </div>
    </form>
</body>
</html>
