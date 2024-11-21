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

// Handle delete action
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_sql = "DELETE FROM credentials WHERE u_id='$delete_id'";
    if ($conn->query($delete_sql) === TRUE) {
        $delete_message = "User deleted successfully.";
    } else {
        $delete_message = "Error deleting user: " . $conn->error;
    }
}

// Handle delete all action
if (isset($_GET['delete_all'])) {
    $delete_all_sql = "DELETE FROM credentials";
    if ($conn->query($delete_all_sql) === TRUE) {
        $delete_message = "All users deleted successfully.";
    } else {
        $delete_message = "Error deleting all users: " . $conn->error;
    }
}

// Fetch all user data
$sql = "SELECT u_id, username, email FROM credentials";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View User List</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #1a1a1a, #333333, #4d4d4d);
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        table, th, td {
            border: 1px solid #555;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #444;
        }

        tr:nth-child(even) {
            background-color: #333;
        }

        tr:hover {
            background-color: #555;
        }

        .container {
            width: 100%;
            max-width: 800px;
            background: #2d2d2d;
            padding: 20px;
            border-radius: 0.25rem;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }

        h2 {
            margin-bottom: 20px;
            text-align: center;
            font-size: 2rem;
        }

        a {
            color: #1e90ff;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
        }

        a:hover {
            text-decoration: underline;
        }

        .delete-btn {
            color: white;
            background-color: #e53e3e;
            padding: 5px 10px;
            border-radius: 5px;
            text-decoration: none;
            transition: background 0.3s;
            display: inline-block;
            text-align: center;
            width: 100%;
        }

        .delete-btn:hover {
            background-color: #c53030;
        }

        td.action-cell {
            text-align: center;
        }

        .delete-all-btn {
            background-color: #e53e3e;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            text-decoration: none;
            transition: background 0.3s;
            display: inline-block;
        }

        .delete-all-btn:hover {
            background-color: #c53030;
        }

        .actions {
            display: flex;
            justify-content: flex-end;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>User List</h2>
        <?php
        if (isset($delete_message)) {
            echo '<p class="message success">' . $delete_message . '</p>';
        }
        ?>
        <table>
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['u_id'] . "</td>";
                        echo "<td>" . $row['username'] . "</td>";
                        echo "<td>" . $row['email'] . "</td>";
                        echo "<td class='action-cell'><a class='delete-btn' href='view_list.php?delete_id=" . $row['u_id'] . "' onclick='return confirm(\"Are you sure you want to delete this user?\")'>Delete</a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No users found</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <div class="actions">
            <a class="delete-all-btn" href="view_list.php?delete_all=1" onclick="return confirm('Are you sure you want to delete all users?')">Delete All</a>
        </div>
        <a href="home.php">Back to Home</a>
    </div>
</body>

</html>
