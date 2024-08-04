<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Login</title>
    <style>
        body {
            background-color: #3498db; /* Change to your desired blue color */
            font-family: Arial, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            max-width: 400px;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .error {
            color: red;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 8px;
        }

        input, button {
            margin-bottom: 16px;
            padding: 8px;
        }

        button {
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Customer Login</h2>
        <?php if (isset($error_message)) { ?>
            <p class="error"><?= $error_message ?></p>
        <?php } ?>
        <form method="post">
            <label for="customer_email">Email:</label>
            <input type="text" name="customer_email" required>
            <label for="customer_password">Password:</label>
            <input type="password" name="customer_password" required>
            <button type="submit" name="login">Login</button>
        </form>

        <hr>

        <h2>Register</h2>
        <form method="post">
            <label for="customer_name">Name:</label>
            <input type="text" name="customer_name" required>
            <label for="customer_email">Email:</label>
            <input type="text" name="customer_email" required>
            <label for="customer_password">Password:</label>
            <input type="password" name="customer_password" required>
            <label for="customer_phone">Phone:</label>
            <input type="text" name="customer_phone" required>
            <button type="submit" name="register">Register</button>
        </form>
    </div>
</body>
</html>
<?php

require_once('db.php');

session_start();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['login'])) {
        $customer_email = $_POST['customer_email'];
        $customer_password = $_POST['customer_password'];

        $sql = "SELECT customer_id FROM customer WHERE customer_email = '$customer_email' AND customer_password = '$customer_password'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $customer_id = $row['customer_id'];

            // Store customer ID, email, and logged-in status in the session
            $_SESSION['customer_id'] = $customer_id;
            $_SESSION['customer_email'] = $customer_email;
            $_SESSION['customer_logged_in'] = true;

            header("Location: index.php");
            die();
        } else {
            $error_message = "Invalid login credentials";
        }
    } elseif (isset($_POST['register'])) {
        // Handle registration logic
        // Validate and sanitize input, and insert into the database
    
        $customer_name = $_POST['customer_name'];
        $customer_email = $_POST['customer_email'];
        $customer_password = $_POST['customer_password'];
        $customer_phone = $_POST['customer_phone'];
    
        // Validate and sanitize input
        // Note: In a real-world scenario, you should use prepared statements
    
        // Check if the email already exists
        $check_email_sql = "SELECT * FROM customer WHERE customer_email = '$customer_email'";
        $check_email_result = $conn->query($check_email_sql);
    
        if ($check_email_result->num_rows > 0) {
            // Email already exists, notify the user
            echo "<script>alert('Registeration failed, email has been used before, Please use another email.');</script>";
        } else {
            // Email doesn't exist, proceed with registration
            if (!is_numeric($customer_phone)) {
                echo "<script>alert('Please provide a valid phone number.');</script>";
            } else if (!filter_var($customer_email, FILTER_VALIDATE_EMAIL) || !strpos($customer_email, '@') || !strpos($customer_email, '.com')) {
                echo "<script>alert('Please provide a valid email address.');</script>";
            } else if (strlen($customer_password) < 6 || !preg_match('/[0-9]/', $customer_password) || !preg_match('/[^a-zA-Z0-9]/', $customer_password)) {
                echo "<script>alert('Password must be at least 6 characters long and contain at least one number and one special character.');</script>";
            } else {
                $insert_sql = "INSERT INTO customer (customer_name, customer_email, customer_password, customer_phone)
                                VALUES ('$customer_name', '$customer_email', '$customer_password', '$customer_phone')";
                $insert_result = $conn->query($insert_sql);
    
                if ($insert_result) {
                    // Registration successful, you can redirect to login page or perform any other action
                    echo "<script>alert('Registration successful! Please proceed to login.');</script>";
    
                    die();
                } else {
                    $error_message = "Registration failed";
                }
            }
        }
    }    
    
    
}

?>