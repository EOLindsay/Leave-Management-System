<?php
session_start();

if (!isset($_SESSION["employee_id"])) {
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

$role = $_SESSION["role"];
$employee_id = $_SESSION["employee_id"];

$manager_department_id = null;
if ($role === "manager") {
    $deptQuery = $conn->prepare("SELECT department_id FROM employee WHERE employee_id=?");
    $deptQuery->bind_param("i", $employee_id);
    $deptQuery->execute();
    $deptQuery->bind_result($manager_department_id);
    $deptQuery->fetch();
    $deptQuery->close();
}


$query = "
    SELECT e.employee_id, e.first_name, e.last_name, e.username, e.role, e.email, e.mobile, d.department_name, e.department_id
    FROM employee e
    LEFT JOIN department d ON e.department_id = d.department_id
    WHERE 1=1
";

if ($role === "manager") {
    $query .= " AND e.department_id = $manager_department_id ";
}

# Apply filters
if (!empty($_GET['role'])) {
    $f_role = $conn->real_escape_string($_GET['role']);
    $query .= " AND e.role = '$f_role'";
}

if (!empty($_GET['department_id']) && $role === "administrator") {
    $department_id = (int) $_GET['department_id'];
    $query .= " AND e.department_id = $department_id";
}

if (!empty($_GET['search'])) {
    $search = $conn->real_escape_string($_GET['search']);
    $query .= " AND (e.first_name LIKE '%$search%' OR e.last_name LIKE '%$search%' OR e.username LIKE '%$search%')";
}

$query .= " ORDER BY e.first_name ASC";
$employees = $conn->query($query);

# Fetch departments (only for admins, managers are locked to theirs)
$departments = null;
if ($role === "administrator") {
    $departments = $conn->query("SELECT department_id, department_name FROM department ORDER BY department_name ASC");
}


if (isset($_GET['export'])) {
    $result = $conn->query($query);

    if ($_GET['export'] == 'csv') {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=employees.csv');
        $output = fopen('php://output', 'w');
        fputcsv($output, ['Employee ID', 'Name', 'Username', 'Role', 'Department', 'Email', 'Mobile']);

        while ($row = $result->fetch_assoc()) {
            fputcsv($output, [
                $row['employee_id'],
                $row['first_name']." ".$row['last_name'],
                $row['username'],
                ucfirst($row['role']),
                $row['department_name'] ?? 'N/A',
                $row['email'],
                $row['mobile']
            ]);
        }
        fclose($output);
        exit;
    }

    if ($_GET['export'] == 'excel') {
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=employees.xls");
        echo "Employee ID\tName\tUsername\tRole\tDepartment\tEmail\tMobile\n";

        while ($row = $result->fetch_assoc()) {
            echo $row['employee_id']."\t".
                 $row['first_name']." ".$row['last_name']."\t".
                 $row['username']."\t".
                 ucfirst($row['role'])."\t".
                 ($row['department_name'] ?? 'N/A')."\t".
                 $row['email']."\t".
                 $row['mobile']."\n";
        }
        exit;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Employees</title>
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
                         <a href="#"><img src="../assets/img/logolight.png" style="width: 166px; height: 50.8px;" alt=" SeamLess Leave"></a> 
                    </div>
                    <button class="toggle-btn border-0" type="button">
                        <i id="icon" class="bx bxs-chevrons-right"></i>
                    </button>
                </div>
                <ul class="sidebar-nav">
                    <li class="sidebar-item">
                        <a href="../admin.php" class="sidebar-link">
                            <i class="bx bx-dashboard"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link collapsed has-dropdown"data-bs-toggle="collapse" 
                        data-bs-target="#dept" aria-expanded="false" aria-controls="dept">
                            <i class="bx bx-building"></i>
                            <span>Departments</span>
                        </a>
                        <ul id="dept" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                            <li class="sidebar-item">
                                <a href="adddept.php" class="sidebar-link">
                                    Add Department
                                </a>
                            </li>
                            <li class="sidebar-item">
                                <a href="mandept.php" class="sidebar-link">
                                    Manage Departments
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link collapsed has-dropdown"data-bs-toggle="collapse" 
                        data-bs-target="#leaves" aria-expanded="false" aria-controls="leaves">
                            <i class="bx bx-pencil-square"></i>
                            <span>Leaves</span>
                        </a>
                        <ul id="leaves" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                            <li class="sidebar-item">
                                <a href="#" class="sidebar-link collapsed has-dropdown"data-bs-toggle="collapse" 
                                data-bs-target="#type" aria-expanded="false" aria-controls="type">
                                Leave Type
                                </a>
                                <ul id="type" class="sidebar-dropdown list-unstyled collapse">
                                    <li class="sidebar-item">
                                        <a href="addtype.php" class="sidebar-link">Add Leave Type</a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="mantype.php" class="sidebar-link">Manage Leave Types</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="sidebar-item">
                                <a href="#" class="sidebar-link collapsed has-dropdown"data-bs-toggle="collapse" 
                                data-bs-target="#policy" aria-expanded="false" aria-controls="policy">
                                Leave Policy
                                </a>
                                <ul id="policy" class="sidebar-dropdown list-unstyled collapse">
                                    <li class="sidebar-item">
                                        <a href="addpolicy.php" class="sidebar-link">Add Leave Policy</a>
                                    </li>
                                    <li class="sidebar-item">
                                        <a href="manpolicy.php" class="sidebar-link">Manage Leave Policies</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="sidebar-item">
                                <a href="#" class="sidebar-link collapsed has-dropdown"data-bs-toggle="collapse" 
                                data-bs-target="#balance" aria-expanded="false" aria-controls="balance">
                                Leave Balance
                                </a>
                                <ul id="balance" class="sidebar-dropdown list-unstyled collapse">
                                    <li class="sidebar-item">
                                        <a href="editbalances.php" class="sidebar-link">View Leave Balance</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link collapsed has-dropdown"data-bs-toggle="collapse" 
                        data-bs-target="#emp" aria-expanded="false" aria-controls="emp">
                            <i class="bx bx-people-diversity"></i>
                            <span>Employees</span>
                        </a>
                        <ul id="emp" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                            <li class="sidebar-item">
                                <a href="addemp.php" class="sidebar-link">
                                    Add Employee
                                </a>
                            </li>
                            <li class="sidebar-item">
                                <a href="grantp.php" class="sidebar-link">
                                    Employee Permissions
                                </a>
                            </li>
                            <li class="sidebar-item">
                                <a href="manemp.php" class="sidebar-link">
                                    Manage Employees
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link collapsed has-dropdown"data-bs-toggle="collapse" 
                        data-bs-target="#manage" aria-expanded="false" aria-controls="manage">
                            <i class="bx bx-server"></i>
                            <span>Leave Management</span>
                        </a>
                        <ul id="manage" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                            <li class="sidebar-item">
                                <a href="all.php" class="sidebar-link">
                                    All Leaves
                                </a>
                            </li>
                            <li class="sidebar-item">
                                <a href="pending.php" class="sidebar-link">
                                    Pending Leaves
                                </a>
                            </li>
                            <li class="sidebar-item">
                                <a href="approved.php" class="sidebar-link">
                                    Approved Leaves
                                </a>
                            </li>
                            <li class="sidebar-item">
                                <a href="rejected.php" class="sidebar-link">
                                    Rejected Leaves
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="sidebar-item">
                        <a href="report.php" class="sidebar-link">
                            <i class="bx bx-file-detail"></i>
                            <span>Leave Report</span>
                        </a>
                    </li>
                    <!-- <li class="sidebar-item">
                        <a href="notification.php" class="sidebar-link">
                            <i class="bx bx-bell-ring"></i>
                            <span>Notifications</span>
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
                    <h6>Permissions</h6>
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
                                Grant User Permissions
                            </h2>
                            <div class="row">
                                <div class="col-12">
                                    <div class="card shadow">
                                        <div class="card-body py-4">
                                            <form method="GET" class="row g-3 mb-4">
                                                <div class="col-md-3">
                                                    <label class="form-label">Role</label>
                                                    <select name="role" class="form-select">
                                                        <option value="">All</option>
                                                        <option value="employee" <?= (isset($_GET['role']) && $_GET['role']=='employee')?'selected':'' ?>>Employee</option>
                                                        <option value="manager" <?= (isset($_GET['role']) && $_GET['role']=='manager')?'selected':'' ?>>Manager</option>
                                                        <?php if ($role === "administrator"): ?>
                                                        <option value="administrator" <?= (isset($_GET['role']) && $_GET['role']=='administrator')?'selected':'' ?>>Administrator</option>
                                                        <?php endif; ?>
                                                    </select>
                                                </div>

                                                <?php if ($role === "administrator"): ?>
                                                <div class="col-md-3">
                                                    <label class="form-label">Department</label>
                                                    <select name="department_id" class="form-select">
                                                        <option value="">All</option>
                                                        <?php while ($dept = $departments->fetch_assoc()): ?>
                                                            <option value="<?= $dept['department_id']; ?>" <?= (isset($_GET['department_id']) && $_GET['department_id']==$dept['department_id'])?'selected':'' ?>>
                                                                <?= htmlspecialchars($dept['department_name']); ?>
                                                            </option>
                                                        <?php endwhile; ?>
                                                    </select>
                                                </div>
                                                <?php endif; ?>

                                                <div class="col-md-3">
                                                    <label class="form-label">Search</label>
                                                    <input type="text" name="search" class="form-control" value="<?= $_GET['search'] ?? '' ?>" placeholder="Name or Username">
                                                </div>

                                                <div class="col-md-2 d-flex align-items-end">
                                                    <button type="submit" class="btn btn-dark w-100">Filter</button>
                                                </div>
                                                <div class="col-md-2 d-flex align-items-end">
                                                    <button type="submit" name="export" value="csv" class="btn btn-success w-100">Export CSV</button>
                                                </div>
                                                <div class="col-md-2 d-flex align-items-end">
                                                    <button type="submit" name="export" value="excel" class="btn btn-primary w-100">Export Excel</button>
                                                </div>
                                                <div class="col-md-2 d-flex align-items-end">
                                                    <a href="manemp.php" class="btn btn-secondary w-100">Reset Filters</a>
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
                                                    <th scope="col">Employee</th>
                                                    <th scope="col">Department</th>
                                                    <th scope="col">Current Role</th>
                                                    <th scope="col">Change Role</th>
                                                    <th scope="col">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php while ($row = $employees->fetch_assoc()): ?>
                                                        <tr>
                                                            <form method="POST">
                                                                <input type="hidden" name="employee_id" value="<?php echo $row['employee_id']; ?>">
                                                                <td><?php echo htmlspecialchars($row['first_name'] . " " . $row['last_name']); ?></td>
                                                                <td><?php echo htmlspecialchars($row['department_name'] ?? 'Unassigned'); ?></td>
                                                                <td><?php echo htmlspecialchars($row['role']); ?></td>
                                                                <td>
                                                                    <select name="role" class="form-select" required>
                                                                        <option value="employee" <?php if ($row['role'] == 'employee') echo 'selected'; ?>>Employee</option>
                                                                        <option value="manager" <?php if ($row['role'] == 'manager') echo 'selected'; ?>>Manager</option>
                                                                        <?php if ($role === "administrator"): ?>
                                                                            <option value="administrator" <?php if ($row['role'] === 'administrator') echo 'selected'; ?>>Administrator</option>
                                                                        <?php endif; ?>
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <button type="submit" name="update_role" class="btn btn-dark btn-sm">Update</button>
                                                                </td>
                                                            </form>
                                                        </tr>
                                                    <?php endwhile; ?>
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
</html>
