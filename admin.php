<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

// Ensure only admins can access this page
if ($_SESSION["role"] !== "admin") {
    echo "Access denied. You are not authorized to view this page.";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - Leave Management System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="assets/favicon/favicon.ico"/>
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
            text-decoration: none;
            color: #333;
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
    <header>
        <nav>
            <ul>
                <a href=""></a>
                <a href=""></a>
                <a href=""></a>
                <a href=""></a>
            </ul>
        </nav>
    </header>

    <h2>Welcome, <?php echo htmlspecialchars($_SESSION["username"]); ?>!</h2>
    <p><strong>Role:</strong> <?php echo $_SESSION["role"]; ?></p>
z\\\\
    <h3>Admin Controls</h3>
    <ul>
        <li><a href="manage_users.php">Manage Users</a></li>
        <li><a href="manage_employees.php">Manage Employees</a></li>
        <li><a href="manage_departments.php">Manage Departments</a></li>
        <li><a href="manage_leave_types.php">Manage Leave Types</a></li>
        <li><a href="manage_leave_policies.php">Manage Leave Policies</a></li>
        <li><a href="view_all_requests.php">View All Leave Requests</a></li>
        <li><a href="system_reports.php">View System Reports</a></li>
    </ul>

    <!-- Logout -->
    <form method="post" action="logout.php" class="logout-btn">
        <button type="submit">Logout</button>
    </form>

</body>
</html>
