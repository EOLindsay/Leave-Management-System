<?php
session_start();
if (!isset($_SESSION["employee_id"]) || $_SESSION["role"] !== "employee") {
    header("Location: login.php");
    exit;
}

$host = "localhost";
$db   = "leave_management";
$user = "root";
$pass = "";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$employee_id = $_SESSION["employee_id"];

$total_leaves = $conn->query("SELECT COUNT(*) AS total FROM leave_request WHERE employee_id=$employee_id")->fetch_assoc()['total'];

$total_approved = $conn->query("SELECT COUNT(*) AS total FROM leave_request WHERE employee_id=$employee_id AND status='approved'")->fetch_assoc()['total'];

$total_new = $conn->query("SELECT COUNT(*) AS total FROM leave_request WHERE employee_id=$employee_id AND status='pending'")->fetch_assoc()['total'];

$recent_leaves = $conn->query("
    SELECT l.request_id, lt.type_name, l.start_date, l.end_date, l.status
    FROM leave_request l
    JOIN leave_type lt ON l.type_id = lt.type_id
    WHERE l.employee_id=$employee_id
    ORDER BY l.request_id DESC
    LIMIT 5
");

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Employee Dashboard</title>
    <link href='https://cdn.boxicons.com/fonts/basic/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" 
    integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/dashboard.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="assets/favicon/favicon.ico">
</head>
<body>
    <div class="wrapper">
            <aside id="sidebar">
                <div class="d-flex justify-content-between p-4">
                    <div class="sidebar-logo">
                    <a href="employee.php"><img src="assets/img/logolight.png" style="width: 166px; height: 50.8px;" alt=" SeamLess Leave"></a>                    </div>
                    <button class="toggle-btn border-0" type="button">
                        <i id="icon" class="bx bxs-chevrons-right"></i>
                    </button>
                </div>
                <ul class="sidebar-nav">
                    <li class="sidebar-item">
                        <a href="employee.php" class="sidebar-link">
                            <i class="bx bx-dashboard"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="employee/myprofile.php" class="sidebar-link">
                            <i class="bx bx-user"></i>
                            <span>My Profile</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link collapsed has-dropdown"data-bs-toggle="collapse" 
                        data-bs-target="#leave" aria-expanded="false" aria-controls="leave">
                            <i class="bx bx-pencil-square"></i>
                            <span>My Leave</span>
                        </a>
                        <ul id="leave" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                            <li class="sidebar-item">
                                <a href="employee/apply.php" class="sidebar-link">
                                    Apply For Leave
                                </a>
                            </li>
                            <li class="sidebar-item">
                                <a href="employee/balance.php" class="sidebar-link">
                                    Leave Balance
                                </a>
                            </li>
                            <li class="sidebar-item">
                                <a href="employee/status.php" class="sidebar-link">
                                    Leave Status
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="sidebar-item">
                        <a href="employee/history.php" class="sidebar-link">
                            <i class="bx  bx-history"></i> 
                            <span>Leave History</span>
                         </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="employee/notification.php" class="sidebar-link">
                            <i class="bx bx-bell-ring"></i>
                            <span>Notification</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="employee/settings.php" class="sidebar-link">
                            <i class="bx bx-cog"></i>
                            <span>Settings</span>
                        </a>
                    </li>
                </ul>
                <div class="sidebar-footer">
                    <a href="logout.php" class="sidebar-link">
                        <i class="bx bx-arrow-left-stroke-square"></i>
                        <span>Logout</span>
                    </a>
                </div>
            </aside>
            <div class="main">
                <nav class="navbar navbar-expand px-4 py-3">
                    <h6>Employee Dashboard</h6>
                    <div class="navbar-collapse collapse">
                        <ul class="navbar-nav ms-auto">
                            <li class="nav-item dropdown">
                                <a href="#" data-bs-toggle="dropdown" class="nav-icon pe-md-0">
                                   <img src="assets/img/avatar.jpeg" alt="" class="avatar img-fluid">
                                </a>
                                <div class="dropdown-menu dropdown-menu-end rounded-0 border-0 shadow mt-3">
                                    <a href="employee/notification.php" class="dropdown-item">
                                        <i class="bx bx-bell-ring"></i>
                                        <span>Notifications</span>
                                    </a>
                                    <a href="employee/settings.php" class="dropdown-item">
                                        <i class="bx bx-cog"></i>
                                        <span>Settings</span>
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a href="employee/help.php" class="dropdown-item">
                                        <i class="bx bx-help-circle"></i>
                                        <span>Help center</span>
                                    </a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>
                <main class="content px-3 py-4">
                    <div class="container-fluid">
                        <div class="mb-3">
                            <h2 class="fw-bold fs-4 mb-3">
                                Welcome, <?php echo htmlspecialchars($_SESSION["first_name"]);?>!
                            </h2>
                            <div class="row">
                                <div class="col-12 col-md-4">
                                    <div class="card effect shadow">
                                        <div class="card-body py-4">
                                            <h5 class="mb-2 card-title fw-bold">
                                                All Leaves
                                            </h5>
                                            <h3 class="card_text mb-2">
                                                <?php echo $total_leaves; ?>
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="card effect shadow">
                                        <div class="card-body py-4">
                                            <h5 class="mb-2 card-title fw-bold">
                                                Number of Approved Leaves
                                            </h5>
                                            <h3 class="card_text mb-2">
                                                <?php echo $total_approved; ?>
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4">
                                    <div class="card effect shadow">
                                        <div class="card-body py-4">
                                            <h5 class="mb-2 card-title fw-bold">
                                                Number of New Applications
                                            </h5>
                                            <h3 class="card_text mb-2">
                                                <?php echo $total_new; ?>
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <h3 class="fw-bold fs-4 my-3">Recent Leave Applications</h3>
                                    <table class="table table-hover">
                                        <thead>
                                            <tr class="highlight">
                                            <th scope="col">Leave Type</th>
                                            <th scope="col">Start Date</th>
                                            <th scope="col">End Date</th>
                                            <th scope="col">Status </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while ($row = $recent_leaves->fetch_assoc()): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($row['type_name']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['start_date']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['end_date']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['status']); ?></td>
                                                </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
        <script src="assets/js/dashboard.js"></script>
    </body>
</body>
</html>
