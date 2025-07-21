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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION["username"]); ?>!</h2>
    <!-- <p>Role: <?php echo $_SESSION["role"]; ?></p> -->

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
