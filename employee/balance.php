<?php
session_start();
if (!isset($_SESSION["employee_id"]) || $_SESSION["role"] !== "employee") {
    header("Location: ../login.php");
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

$genderResult = $conn->query("SELECT gender FROM employee WHERE employee_id = $employee_id");
$genderRow = $genderResult->fetch_assoc();
$gender = $genderRow['gender'];

$totalQuery = $conn->query("
    SELECT COALESCE(SUM(p.maxdays_peryear), 0) AS total_policy_days
    FROM leave_policy p
    WHERE p.gender_specific = 'all' OR p.gender_specific = '$gender'
");
$totalPolicyRow = $totalQuery->fetch_assoc();
$totalPolicyDays = $totalPolicyRow['total_policy_days'];

$takenQuery = $conn->query("
    SELECT COALESCE(SUM(DATEDIFF(lr.end_date, lr.start_date) + 1), 0) AS total_taken
    FROM leave_request lr
    WHERE lr.employee_id = $employee_id AND lr.status = 'approved'
");
$takenRow = $takenQuery->fetch_assoc();
$totalTaken = $takenRow['total_taken'];

$total_balance = $totalPolicyDays - $totalTaken;

$annualQuery = $conn->query("
    SELECT (p.maxdays_peryear - 
        COALESCE((
            SELECT SUM(DATEDIFF(lr.end_date, lr.start_date) + 1)
            FROM leave_request lr
            JOIN leave_type lt ON lr.type_id = lt.type_id
            WHERE lr.employee_id = $employee_id 
              AND lr.status = 'approved'
              AND lt.type_name = 'Annual Leave'
        ), 0)
    ) AS annual_left
    FROM leave_policy p
    JOIN leave_type lt ON p.type_id = lt.type_id
    WHERE lt.type_name = 'Annual Leave'
      AND (p.gender_specific = 'all' OR p.gender_specific = '$gender')
    LIMIT 1
");
$annualRow = $annualQuery->fetch_assoc();
$annual_leave = $annualRow['annual_left'] ?? 0;

$sickQuery = $conn->query("
    SELECT (p.maxdays_peryear - 
        COALESCE((
            SELECT SUM(DATEDIFF(lr.end_date, lr.start_date) + 1)
            FROM leave_request lr
            JOIN leave_type lt ON lr.type_id = lt.type_id
            WHERE lr.employee_id = $employee_id 
              AND lr.status = 'approved'
              AND lt.type_name = 'Sick Leave'
        ), 0)
    ) AS sick_left
    FROM leave_policy p
    JOIN leave_type lt ON p.type_id = lt.type_id
    WHERE lt.type_name = 'Sick Leave'
      AND (p.gender_specific = 'all' OR p.gender_specific = '$gender')
    LIMIT 1
");
$sickRow = $sickQuery->fetch_assoc();
$sick_leave = $sickRow['sick_left'] ?? 0;

$query = "
    SELECT lt.type_name,
           p.maxdays_peryear AS total_accrued_since_hire,
           COALESCE(SUM(CASE WHEN lr.status = 'approved' 
                             THEN DATEDIFF(lr.end_date, lr.start_date) + 1
                             ELSE 0 END), 0) AS total_taken_since_hire,
           (p.maxdays_peryear - 
            COALESCE(SUM(CASE WHEN lr.status = 'approved' 
                              THEN DATEDIFF(lr.end_date, lr.start_date) + 1
                              ELSE 0 END), 0)) AS current_balance
    FROM leave_policy p
    JOIN leave_type lt ON p.type_id = lt.type_id
    LEFT JOIN leave_request lr 
           ON lr.type_id = lt.type_id AND lr.employee_id = $employee_id
    WHERE p.gender_specific = 'all' OR p.gender_specific = '$gender'
    GROUP BY lt.type_name, p.maxdays_peryear
    ORDER BY lt.type_name
";
$balances = $conn->query($query);


$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Leave</title>
    <link href='https://cdn.boxicons.com/fonts/basic/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" 
    integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="../assets/favicon/favicon.ico">
</head>
<body>
    <div class="wrapper">
            <aside id="sidebar">
                <div class="d-flex justify-content-between p-4">
                    <div class="sidebar-logo">
                    <a href="../employee.php"><img src="../assets/img/logolight.png" style="width: 166px; height: 50.8px;" alt=" SeamLess Leave"></a>                    </div>
                    <button class="toggle-btn border-0" type="button">
                        <i id="icon" class="bx bxs-chevrons-right"></i>
                    </button>
                </div>
                <ul class="sidebar-nav">
                    <li class="sidebar-item">
                        <a href="../employee.php" class="sidebar-link">
                            <i class="bx bx-dashboard"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="myprofile.php" class="sidebar-link">
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
                                <a href="apply.php" class="sidebar-link">
                                    Apply For Leave
                                </a>
                            </li>
                            <li class="sidebar-item">
                                <a href="balance.php" class="sidebar-link">
                                    Leave Balance
                                </a>
                            </li>
                            <li class="sidebar-item">
                                <a href="status.php" class="sidebar-link">
                                    Leave Status
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="sidebar-item">
                        <a href="history.php" class="sidebar-link">
                            <i class="bx  bx-history"></i> 
                            <span>Leave History</span>
                         </a>
                    </li>
                    <!-- <li class="sidebar-item">
                        <a href="notification.php" class="sidebar-link">
                            <i class="bx bx-bell-ring"></i>
                            <span>Notification</span>
                        </a>
                    </li> -->
                    <li class="sidebar-item">
                        <a href="settings.php" class="sidebar-link">
                            <i class="bx bx-cog"></i>
                            <span>Settings</span>
                        </a>
                    </li>
                </ul>
                <div class="sidebar-footer">
                    <a href="../logout.php" class="sidebar-link">
                        <i class="bx bx-arrow-left-stroke-square"></i>
                        <span>Logout</span>
                    </a>
                </div>
            </aside>
            <div class="main">
                <nav class="navbar navbar-expand px-4 py-3">
                    <h6>Balances</h6>
                    <div class="navbar-collapse collapse">
                        <ul class="navbar-nav ms-auto">
                            <li class="nav-item dropdown">
                                <a href="#" data-bs-toggle="dropdown" class="nav-icon pe-md-0">
                                   <img src="../assets/img/avatar.jpeg" alt="" class="avatar img-fluid">
                                </a>
                                <div class="dropdown-menu dropdown-menu-end rounded-0 border-0 shadow mt-3">
                                    <!-- <a href="notification.php" class="dropdown-item">
                                        <i class="bx bx-bell-ring"></i>
                                        <span>Notifications</span>
                                    </a> -->
                                    <a href="settings.php" class="dropdown-item">
                                        <i class="bx bx-cog"></i>
                                        <span>Settings</span>
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a href="help.php" class="dropdown-item">
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
                                Leave Balance
                            </h2>
                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <div class="card shadow-sm text-center">
                                        <div class="card-body">
                                            <h6 class="fw-bold">Total Available Balance</h6>
                                            <h3 class="text-success">
                                                <?= number_format($total_balance, 0); ?> days
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                                 <div class="col-md-4">
                                    <div class="card shadow-sm text-center">
                                        <div class="card-body">
                                            <h6 class="fw-bold">Annual Leave</h6>
                                            <h3 class="text-primary">
                                                <?= number_format($annual_leave, 0); ?> days
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card shadow-sm text-center">
                                        <div class="card-body">
                                            <h6 class="fw-bold">Sick Leave</h6>
                                            <h3 class="text-danger">
                                                <?= number_format($sick_leave, 0); ?> days
                                            </h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="card shadow">
                                        <div class="card-body py-4">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr class="highlight">
                                                    <th scope="col">Leave Type</th>
                                                    <th scope="col">Current Balance</th>
                                                    <th scope="col">Total Balance</th>
                                                    <th scope="col">Total Taken</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                     <?php if ($balances->num_rows > 0): ?>
                                                        <?php while ($row = $balances->fetch_assoc()): ?>
                                                            <tr>
                                                                <td><?= $row['type_name'] ? htmlspecialchars($row['type_name']) : 'N/A'; ?></td>
                                                                <td><?= htmlspecialchars($row['current_balance']); ?></td>
                                                                <td><?= htmlspecialchars($row['total_accrued_since_hire']); ?></td>
                                                                <td><?= htmlspecialchars($row['total_taken_since_hire']); ?></td>
                                                            </tr>
                                                        <?php endwhile; ?>
                                                    <?php else: ?>
                                                        <tr><td colspan="8" class="text-center">No balance records found.</td></tr>
                                                    <?php endif; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
        <script src="../assets/js/dashboard.js"></script>
    </body>
</body>
</html>