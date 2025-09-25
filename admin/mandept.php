<?php
session_start();


if (!isset($_SESSION["employee_id"])) {
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

$success = "";
$error   = "";

if (isset($_GET["delete"])) {
    $delete_id = intval($_GET["delete"]);
    $stmt = $conn->prepare("DELETE FROM department WHERE department_id = ?");
    $stmt->bind_param("i", $delete_id);

    if ($stmt->execute()) {
        $success = "Department deleted successfully!";
    } else {
        $error = "Error deleting department: " . $stmt->error;
    }
    $stmt->close();
}

$query = "
    SELECT d.department_id, d.department_name, e.first_name, e.last_name 
    FROM department d
    LEFT JOIN employee e ON d.manager_id = e.employee_id
    ORDER BY d.department_name ASC
";
$departments = $conn->query($query);

$conn->close();
?>


<!DOCTYPE html>
<html>
    <head>
        <title>Departments</title>
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
                         <a href="../admin.php"><img src="../assets/img/logolight.png" style="width: 166px; height: 50.8px;" alt=" SeamLess Leave"></a> 
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
                        <a href="#" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse" 
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
                    <h6>Departments</h6>
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
                                Manage Departments
                            </h2>
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="card shadow">
                                        <div class="card-body py-4">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr class="highlight">
                                                    <th scope="col">ID</th>
                                                    <th scope="col">Department Name</th>
                                                    <th scope="col">Manager</th>
                                                    <th scope="col">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if ($departments->num_rows > 0): ?>
                                                        <?php while ($row = $departments->fetch_assoc()): ?>
                                                            <tr>
                                                                <td><?php echo $row['department_id']; ?></td>
                                                                <td><?php echo htmlspecialchars($row['department_name']); ?></td>
                                                                <td>
                                                                    <?php echo $row['first_name'] ? htmlspecialchars($row['first_name'] . " " . $row['last_name']) : "Not Assigned"; ?>
                                                                </td>
                                                                <td>
                                                                    <a href="editdept.php?id=<?php echo $row['department_id']; ?>" class="btn btn-lg"><i class='bx  bx-edit'  style="color: green;"></i> </a>
                                                                    <a href="mandept.php?delete=<?php echo $row['department_id']; ?>" class="btn btn-lg" onclick="return confirm('Are you sure you want to delete this department?');"><i class='bx  bx-trash-x' style="color: red;" ></i> </a>
                                                                </td>
                                                            </tr>
                                                        <?php endwhile; ?>
                                                    <?php else: ?>
                                                        <tr>
                                                            <td colspan="4" class="text-center">No departments found</td>
                                                        </tr>
                                                    <?php endif; ?>
                                                </tbody>
                                            </table>
                                            <a href="adddept.php" class="btn btn-dark">Add New Department</a>
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
