<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Employee Dashboard</title>
</head>
<body>
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION["username"]); ?>!</h2>
    <p>Role: <?php echo $_SESSION["role"]; ?></p>

    <ul>
        <li><a href="apply_leave.php">Apply for Leave</a></li>
        <li><a href="view_leave_history.php">View Leave History</a></li>
        <li><a href="leave_balance.php">Check Leave Balance</a></li>
        <li><a href="team_calendar.php">Team Leave Calendar</a></li>
    </ul>

    <form method="post" action="logout.php">
        <button type="submit">Logout</button>
    </form>
</body>
</html>
