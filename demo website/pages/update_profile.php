<?php
// Include database connection file
include 'connection.php';

// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['usrname'])) {
    header("Location: login.php");
    exit();
}

// Get the current username from session
$usrname = $_SESSION['usrname'];

// Define variables and initialize with empty values
$new_email = $new_username = $new_password = "";
$success_message = $error_message = "";

// Process form data when submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['email'])) {
        $new_email = $_POST['email'];
        $sql = "UPDATE credentials SET email='$new_email' WHERE username='$usrname'";
    } elseif (!empty($_POST['username'])) {
        $new_username = $_POST['username'];
        $sql = "UPDATE credentials SET username='$new_username' WHERE username='$usrname'";
        $_SESSION['usrname'] = $new_username; // Update session username
    } elseif (!empty($_POST['password'])) {
        $new_password = $_POST['password'];
        $sql = "UPDATE credentials SET password='$new_password' WHERE username='$usrname'";
    }

    if ($conn->query($sql) === TRUE) {
        $success_message = "Profile updated successfully! Redirecting to home...";
        echo '<script type="text/javascript">
                setTimeout(function() {
                    window.location.href = "home.php";
                }, 2000);
              </script>';
    } else {
        $error_message = "Error updating profile: " . $conn->error;
    }
}

// Fetch current user data
$sql = "SELECT email, username FROM credentials WHERE username='$usrname'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #1a1a1a, #333333, #4d4d4d);
            color: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
        }
        .floating-placeholder {
            position: relative;
            margin-bottom: 20px;
        }
        .floating-placeholder input {
            border: 1px solid #333;
            padding: 1rem 0.5rem 0.5rem 0.5rem;
            border-radius: 0.25rem;
            background: transparent;
            color: white;
            width: 100%;
        }
        .floating-placeholder label {
            position: absolute;
            top: 1rem;
            left: 0.5rem;
            color: #b3b3b3;
            padding: 0 0.25rem;
            transition: all 0.2s ease;
            pointer-events: none;
        }
        .floating-placeholder input:focus + label,
        .floating-placeholder input:not(:placeholder-shown) + label {
            top: -0.75rem;
            left: 0.5rem;
            font-size: 1rem;
            color: #1e90ff;
            background: #1a1a1a;
        }
        .container {
            width: 400px;
            padding: 20px;
            background: #2d2d2d;
            border-radius: 0.25rem;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }
        button {
            width: 100%;
            padding: 10px;
            background: #1e90ff;
            border: none;
            border-radius: 0.25rem;
            cursor: pointer;
            transition: background 0.3s;
        }
        button:hover {
            background: #005bb5;
        }
        .message {
            margin-top: 20px;
            text-align: center;
        }
        .success {
            color: #4caf50;
        }
        .error {
            color: #f44336;
        }
        .dropdown-container {
            margin-bottom: 20px;
        }
        .back-to-home {
            background-color: #1e90ff;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            text-decoration: none;
            transition: background 0.3s;
            margin-top: 20px;
        }
        .back-to-home:hover {
            background-color: #005bb5;
        }
    </style>
    <script>
        function showInputField() {
            const selectedOption = document.getElementById('updateOption').value;
            const emailField = document.getElementById('emailField');
            const usernameField = document.getElementById('usernameField');
            const passwordField = document.getElementById('passwordField');

            emailField.style.display = 'none';
            usernameField.style.display = 'none';
            passwordField.style.display = 'none';

            if (selectedOption === 'email') {
                emailField.style.display = 'block';
            } else if (selectedOption === 'username') {
                usernameField.style.display = 'block';
            } else if (selectedOption === 'password') {
                passwordField.style.display = 'block';
            }
        }
    </script>
</head>
<body>
    <div class="container">
        <h2 class="text-2xl font-bold mb-6 text-center">Update Profile</h2>
        <form method="POST" action="">
            <div class="dropdown-container">
                <label for="updateOption" class="block mb-2">Select an option to update:</label>
                <select id="updateOption" name="updateOption" class="w-full px-4 py-2 border border-blue-600 rounded-lg bg-gray-800 text-white focus:outline-none focus:ring-2 focus:ring-blue-600" onchange="showInputField()">
                    <option value="" disabled selected>Select an option</option>
                    <option value="email">Email</option>
                    <option value="username">Username</option>
                    <option value="password">Password</option>
                </select>
            </div>
            <div id="emailField" class="floating-placeholder" style="display: none;">
                <input type="email" name="email" id="email" placeholder=" ">
                <label for="email">New Email</label>
            </div>
            <div id="usernameField" class="floating-placeholder" style="display: none;">
                <input type="text" name="username" id="username" placeholder=" ">
                <label for="username">New Username</label>
            </div>
            <div id="passwordField" class="floating-placeholder" style="display: none;">
                <input type="password" name="password" id="password" placeholder=" ">
                <label for="password">New Password</label>
            </div>
            <button type="submit">Update</button>
        </form>
        <?php
        if (!empty($success_message)) {
            echo '<p class="message success">' . $success_message . '</p>';
        }
        if (!empty($error_message)) {
            echo '<p class="message error">' . $error_message . '</p>';
        }
        ?>
    </div>
    <a class="back-to-home" href="home.php">Back to Home</a>
</body>
</html>
