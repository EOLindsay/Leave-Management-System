<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

// Optional: Only allow managers
if ($_SESSION["role"] !== "manager") {
    echo "Access denied. You are not authorized to view this page.";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manager Dashboard - Leave Management System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 30px;
        }
        h2 {
            color: #007bff;
        }
        ul {
            list-style-type: none;
            padding-left: 0;
        }
        li {
            margin-bottom: 10px;
        }
        a {
            color: #333;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        .logout-btn {
            margin-top: 20px;
        }
    </style>
</head>
<body>

<h2>Welcome, <?php echo htmlspecialchars($_SESSION["username"]); ?>!</h2>
<p><strong>Role:</strong> <?php echo $_SESSION["role"]; ?></p>

<h3>Manager Actions</h3>
<ul>
    <li><a href="pending_requests.php">View & Approve/Reject Leave Requests</a></li>
    <li><a href="team_leave_calendar.php">View Team Leave Calendar</a></li>
    <li><a href="team_leave_history.php">View Team Leave History</a></li>
    <li><a href="leave_balances.php">Check Team Leave Balances</a></li>
</ul>

<!-- Logout Form -->
<form method="post" action="logout.php" class="logout-btn">
    <button type="submit">Logout</button>
</form>

</body>
</html>
