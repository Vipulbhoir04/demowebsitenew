<?php
// Include connection file
include 'connection.php';

// Start session
session_start();

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $usrname = $_POST['usrname'];
  $passwordd = $_POST['passwordd'];

  // Query the database to check credentials
  $sql = "SELECT * FROM credentials WHERE username='$usrname' AND passwordd='$passwordd'";
  $result = $conn->query($sql);

  if (!$result) { 
    die("Error executing query: " . $conn->error);
  }

  if ($result->num_rows > 0) {
    // Login successful 
    $_SESSION['usrname'] = $usrname;
    $success_message = "Login successful! Redirecting...";
    echo '<script type="text/javascript"> 
      setTimeout(function() { 
        window.location.href = "home.php"; 
      }, 2000); 
    </script>';
  } else {
    // Login failed
    $error_message = "Invalid username or password.";
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #1a1a1a, #333333, #4d4d4d);
      position: relative;
      overflow: hidden;
    }

    .floating-placeholder {
      position: relative;
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

    .floating-placeholder input:focus+label,
    .floating-placeholder input:not(:placeholder-shown)+label {
      top: -0.75rem;
      left: 0.5rem;
      font-size: 1rem;
      color: #1e90ff;
      /* Blue color */
      background: #1a1a1a;
      /* Background set back to normal */
    }

    .password-container {
      position: relative;
    }

    .password-container .toggle-password {
      position: absolute;
      top: 50%;
      right: 0.5rem;
      transform: translateY(-50%);
      cursor: pointer;
      color: #b3b3b3;
    }
  </style>
</head>

<body class="flex justify-center items-center min-h-screen">
  <div class="w-full max-w-md bg-gray-900 p-8 rounded-lg shadow-lg">
    <h1 class="text-3xl font-bold mb-6 text-white text-center">Sign In</h1>
    <form method="POST" action="">
      <!--username block-->
      <div class="mb-4 floating-placeholder">
        <input type="text" name="usrname" id="usrname" placeholder=" "
          class="w-full px-4 py-2 border border-blue-600 rounded-lg bg-gray-800 text-white focus:outline-none focus:ring-2 focus:ring-blue-600">
        <label for="usrname">Username</label>
      </div>
      <!--password block-->
      <div class="mb-6 floating-placeholder password-container">
        <input type="password" name="passwordd" id="passwordd" placeholder=" "
          class="w-full px-4 py-2 border border-blue-600 rounded-lg bg-gray-800 text-white focus:outline-none focus:ring-2 focus:ring-blue-600">
        <label for="passwordd">Password</label>
        <span class="toggle-password" onclick="togglePassword()">👁️</span>
      </div>
      <button type="submit"
        class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition duration-300">Login</button>
      <div class="flex justify-between items-center mt-4 text-gray-400">
        <a href="#" class="hover:underline">Forgot Password?</a>
        <a href="register.php" class="text-white hover:underline">Sign Up</a>
      </div>
    </form>
    <?php
    if (isset($success_message)) {
      echo '<p class="text-green-500 text-center mt-4">' . $success_message . '</p>';
    }
    if (isset($error_message)) {
      echo '<p class="text-red-500 text-center mt-4">' . $error_message . '</p>';
    }
    ?>
  </div>
  <script>
    function togglePassword() {
      const passwordField = document.getElementById('passwordd');
      const passwordToggle = document.querySelector('.toggle-password');
      if (passwordField.type === 'password') {
        passwordField.type = 'text';
        passwordToggle.textContent = '🙈';
      } else {
        passwordField.type = 'password';
        passwordToggle.textContent = '👁️';
      }
    }
  </script>
</body>

</html>
