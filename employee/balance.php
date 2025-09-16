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

$query = "
    SELECT lt.type_name, lb.year, lb.current_balance, 
           lb.total_accrued_since_hire, lb.total_taken_since_hire, 
           lb.last_accrual_date, lb.next_accrual_date, lb.balance_asof_date
    FROM leave_balance lb
    JOIN leave_type lt ON lb.type_id = lt.type_id
    WHERE lb.employee_id = $employee_id
    ORDER BY lb.year DESC, lt.type_name
";
$balances = $conn->query($query);

$total_balance = 0;
$all_balances  = [];
if ($balances && $balances->num_rows > 0) {
    while ($row = $balances->fetch_assoc()) {
        $total_balance += $row['current_balance'];
        $all_balances[] = $row; // keep rows for later display
    }
}

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
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link">
                            <i class="bx bx-bell-ring"></i>
                            <span>Notification</span>
                        </a>
                    </li>
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
                                    <a href="#" class="dropdown-item">
                                        <i class="bx bx-bell-ring"></i>
                                        <span>Notifications</span>
                                    </a>
                                    <a href="settings.php" class="dropdown-item">
                                        <i class="bx bx-cog"></i>
                                        <span>Settings</span>
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a href="#" class="dropdown-item">
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
                                                <?= number_format($total_balance, 2); ?> days
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
                                                    <th scope="col">Year</th>
                                                    <th scope="col">Current Balance</th>
                                                    <th scope="col">Total Accrued</th>
                                                    <th scope="col">Total Taken</th>
                                                    <th scope="col">Last Accrual</th>
                                                    <th scope="col">Next Accrual</th>
                                                    <th scope="col">As of Date</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                     <?php if ($balances->num_rows > 0): ?>
                                                        <?php while ($row = $balances->fetch_assoc()): ?>
                                                            <tr>
                                                                <td><?= htmlspecialchars($row['type_name']); ?></td>
                                                                <td><?= htmlspecialchars($row['year']); ?></td>
                                                                <td><?= htmlspecialchars($row['current_balance']); ?></td>
                                                                <td><?= htmlspecialchars($row['total_accrued_since_hire']); ?></td>
                                                                <td><?= htmlspecialchars($row['total_taken_since_hire']); ?></td>
                                                                <td><?= htmlspecialchars($row['last_accrual_date']); ?></td>
                                                                <td><?= htmlspecialchars($row['next_accrual_date']); ?></td>
                                                                <td><?= htmlspecialchars($row['balance_asof_date']); ?></td>
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