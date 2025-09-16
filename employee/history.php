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
    SELECT l.request_id, lt.type_name, l.start_date, l.end_date, 
           l.reason, l.status, l.request_date, l.approved_on
    FROM leave_request l
    JOIN leave_type lt ON l.type_id = lt.type_id
    WHERE l.employee_id = ?
";

$filters = [];
$params = [$employee_id];
$types = "i";

if (!empty($_GET['status'])) {
    $query .= " AND l.status = ?";
    $filters[] = $_GET['status'];
    $types .= "s";
}

if (!empty($_GET['type_id'])) {
    $query .= " AND l.type_id = ?";
    $filters[] = $_GET['type_id'];
    $types .= "i";
}

if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {
    $query .= " AND l.start_date >= ? AND l.end_date <= ?";
    $filters[] = $_GET['start_date'];
    $filters[] = $_GET['end_date'];
    $types .= "ss";
}

$query .= " ORDER BY l.request_id DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param($types, ...$params, ...$filters);
$stmt->execute();
$result = $stmt->get_result();

$leave_types = $conn->query("SELECT type_id, type_name FROM leave_type ORDER BY type_name ASC");

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
                    <h6>History</h6>
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
                                Leave History
                            </h2>
                            <div class="row">
                                <div class="col-12">
                                    <div class="card shadow">
                                        <div class="card-body py-4">
                                            <form method="GET" class="row g-3 mb-4">
                                                <div class="col-md-2">
                                                    <label class="form-label">Status</label>
                                                    <select name="status" class="form-select">
                                                        <option value="">All</option>
                                                        <option value="pending"  <?= (isset($_GET['status']) && $_GET['status']=='pending')?'selected':'' ?>>Pending</option>
                                                        <option value="approved" <?= (isset($_GET['status']) && $_GET['status']=='approved')?'selected':'' ?>>Approved</option>
                                                        <option value="rejected" <?= (isset($_GET['status']) && $_GET['status']=='rejected')?'selected':'' ?>>Rejected</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label">Leave Type</label>
                                                    <select name="type_id" class="form-select">
                                                        <option value="">All</option>
                                                        <?php while ($lt = $leave_types->fetch_assoc()): ?>
                                                            <option value="<?= $lt['type_id']; ?>" <?= (isset($_GET['type_id']) && $_GET['type_id']==$lt['type_id'])?'selected':'' ?>>
                                                                <?= htmlspecialchars($lt['type_name']); ?>
                                                            </option>
                                                        <?php endwhile; ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label">Start Date</label>
                                                    <input type="date" name="start_date" class="form-control" value="<?= $_GET['start_date'] ?? '' ?>">
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label">End Date</label>
                                                    <input type="date" name="end_date" class="form-control" value="<?= $_GET['end_date'] ?? '' ?>">
                                                </div>
                                                <div class="col-md-2 d-flex align-items-end">
                                                    <button type="submit" class="btn btn-dark w-100">Filter</button>
                                                </div>
                                                <div class="col-md-2 d-flex align-items-end">
                                                    <a href="history.php" class="btn btn-secondary w-100">Reset</a>
                                                </div>
                                            </form>
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
                                                    <th scope="col">Start Date</th>
                                                    <th scope="col">End Date</th>
                                                    <th scope="col">Reason</th>
                                                    <th scope="col">Status</th>
                                                    <th scope="col">Applied On</th>
                                                    <th scope="col">Reviewed On</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if ($result->num_rows > 0): ?>
                                                        <?php while ($row = $result->fetch_assoc()): ?>
                                                            <tr>
                                                                <td><?= htmlspecialchars($row['type_name']); ?></td>
                                                                <td><?= htmlspecialchars($row['start_date']); ?></td>
                                                                <td><?= htmlspecialchars($row['end_date']); ?></td>
                                                                <td><?= htmlspecialchars($row['reason']); ?></td>
                                                                <td>
                                                                    <?php if ($row['status'] == 'approved'): ?>
                                                                        <span class="badge bg-success">Approved</span>
                                                                    <?php elseif ($row['status'] == 'pending'): ?>
                                                                        <span class="badge bg-warning text-dark">Pending</span>
                                                                    <?php else: ?>
                                                                        <span class="badge bg-danger">Rejected</span>
                                                                    <?php endif; ?>
                                                                </td>
                                                                <td><?= htmlspecialchars($row['request_date']); ?></td>
                                                                <td><?= $row['reviewed_on'] ? htmlspecialchars($row['approved_on']) : '-' ?></td>
                                                            </tr>
                                                        <?php endwhile; ?>
                                                    <?php else: ?>
                                                        <tr><td colspan="7" class="text-center">No leave history found.</td></tr>
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