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

$success = "";
$error   = "";

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

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["update_role"])) {
    $target_employee_id = intval($_POST["employee_id"]);
    $new_role = $_POST["role"];

    if ($role === "administrator") {
        $stmt = $conn->prepare("UPDATE employee SET role=? WHERE employee_id=?");
        $stmt->bind_param("si", $new_role, $target_employee_id);
        $stmt->execute();
        $stmt->close();
        $_SESSION["success"] = "Role updated successfully!";
    } elseif ($role === "manager") {

        if ($new_role === "administrator") {
            $_SESSION["error"] = "Managers cannot assign Administrator role.";
        } else {

            $check = $conn->prepare("SELECT department_id FROM employee WHERE employee_id=?");
            $check->bind_param("i", $target_employee_id);
            $check->execute();
            $check->bind_result($emp_dept);
            $check->fetch();
            $check->close();

            if ($emp_dept == $manager_department_id) {
                $stmt = $conn->prepare("UPDATE employee SET role=? WHERE employee_id=?");
                $stmt->bind_param("si", $new_role, $target_employee_id);
                $stmt->execute();
                $stmt->close();
                $_SESSION["success"] = "Role updated successfully!";
            } else {
                $_SESSION["error"] = "You cannot change roles of employees outside your department.";
            }
        }
    } else {
        $_SESSION["error"] = "You do not have permission to update roles.";
    }

    header("Location: grantp.php");
    exit;
}

if ($role === "administrator") {
    $employees = $conn->query("SELECT employee_id, first_name, last_name, email, role, department_id FROM employee ORDER BY first_name ASC");
} elseif ($role === "manager") {
    $stmt = $conn->prepare("SELECT employee_id, first_name, last_name, email, role, department_id FROM employee WHERE department_id=? ORDER BY first_name ASC");
    $stmt->bind_param("i", $manager_department_id);
    $stmt->execute();
    $employees = $stmt->get_result();
} else {
    die("Access denied.");
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Leaves</title>
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
                    <a href="../manager.php"><img src="../assets/img/logolight.png" style="width: 166px; height: 50.8px;" alt=" SeamLess Leave"></a>                    </div>
                    <button class="toggle-btn border-0" type="button">
                        <i id="icon" class="bx bxs-chevrons-right"></i>
                    </button>
                </div>
                <ul class="sidebar-nav">
                    <li class="sidebar-item">
                        <a href="../manager.php" class="sidebar-link">
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
                        data-bs-target="#emp" aria-expanded="false" aria-controls="emp">
                            <i class="bx bx-people-diversity"></i>
                            <span>Employees</span>
                        </a>
                        <ul id="emp" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                            <li class="sidebar-item">
                                <a href="grantp.php" class="sidebar-link">
                                    Employee Permissions
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
                    <li class="sidebar-item">
                        <a href="#" class="sidebar-link">
                            <i class="bx bx-bell-ring"></i>
                            <span>Notifications</span>
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
                    <h6>Permissions</h6>
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
                                    <a href="#" class="dropdown-item">
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
                                Grant User Permissions
                            </h2>
                            <div class="row">
                                <div class="col-12">
                                    <div class="card shadow">
                                        <div class="card-body py-4">
                                            <?php if ($success): ?>
                                                <div class="alert alert-success"><?php echo $success; ?></div>
                                            <?php endif; ?>
                                            <?php if ($error): ?>
                                                <div class="alert alert-danger"><?php echo $error; ?></div>
                                            <?php endif; ?>
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr class="highlight">
                                                    <th scope="col">Employee</th>
                                                    <th scope="col">Email</th>
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
                                                                <td><?php echo htmlspecialchars($row['email']); ?></td>
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