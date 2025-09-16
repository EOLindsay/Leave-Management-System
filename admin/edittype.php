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

if (!isset($_GET["id"])) {
    die("Leave Type ID missing!");
}
$type_id = intval($_GET["id"]);

$stmt = $conn->prepare("SELECT type_id, type_name, description, requires_approval, default_accrualrate_perperiod, max_carryover_days FROM leave_type WHERE type_id = ?");
$stmt->bind_param("i", $type_id);
$stmt->execute();
$result = $stmt->get_result();
$type = $result->fetch_assoc();
$stmt->close();

if (!$type) {
    die("Leave type not found!");
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["update_type"])) {
    $type_name   = trim($_POST["type_name"]);
    $description = trim($_POST["description"]);
    $requires_approval = isset($_POST["requires_approval"]) ? 1 : 0;
    $accrual_rate = intval($_POST["default_accrualrate_perperiod"]);
    $carryover    = intval($_POST["max_carryover_days"]);

    $stmt = $conn->prepare("UPDATE leave_type SET type_name=?, description=?, requires_approval=?, default_accrualrate_perperiod=?, max_carryover_days=? WHERE type_id=?");
    $stmt->bind_param("ssiiii", $type_name, $description, $requires_approval, $accrual_rate, $carryover, $type_id);

    if ($stmt->execute()) {
        $_SESSION["success"] = "Leave type updated successfully!";
        header("Location: mantype.php");
        exit;
    } else {
        $error = "Error updating leave type: " . $stmt->error;
    }
    $stmt->close();
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
                                        <a href="editbalances.php" class="sidebar-link">Edit Leave Balance</a>
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
                    <li class="sidebar-item">
                        <a href="notification.php" class="sidebar-link">
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
                    <h6>Types</h6>
                    <div class="navbar-collapse collapse">
                        <ul class="navbar-nav ms-auto">
                            <li class="nav-item dropdown">
                                <a href="#" data-bs-toggle="dropdown" class="nav-icon pe-md-0">
                                   <img src="../assets/img/avatar.jpeg" alt="" class="avatar img-fluid">
                                </a>
                                <div class="dropdown-menu dropdown-menu-end rounded-0 border-0 shadow mt-3">
                                    <a href="notification.php" class="dropdown-item">
                                        <i class="bx bx-bell-ring"></i>
                                        <span>Notifications</span>
                                    </a>
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
                                Edit Leave Types
                            </h2>
                            <div class="row">
                                <div class="col-12 col-md-8">
                                    <div class="card shadow">
                                        <div class="card-body py-4">
                                            <?php if (!empty($success)): ?>
                                                <div class="alert alert-success"><?php echo $success; ?></div>
                                            <?php endif; ?>
                                            <?php if (!empty($error)): ?>
                                                <div class="alert alert-danger"><?php echo $error; ?></div>
                                            <?php endif; ?>
                                            <form method="POST" class="row g-3">
                                                 <div class="col-md-6">
                                                    <label for="type_name" class="form-label">Type Name</label>
                                                    <input type="text" class="form-control" id="type_name" name="type_name" 
                                                        value="<?php echo htmlspecialchars($type['type_name']); ?>" required>
                                                </div>
                                                <div class="col-12">
                                                    <label for="description" class="form-label">Description</label>
                                                    <textarea class="form-control" id="description" name="description" rows="3"><?php echo htmlspecialchars($type['description']); ?></textarea>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="requires_approval" class="form-label">Requires Approval</label><br>
                                                    <input type="checkbox" id="requires_approval" name="requires_approval" value="1"
                                                        <?php if ($type['requires_approval']) echo "checked"; ?>>
                                                    <label for="requires_approval">Yes</label>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="default_accrualrate_perperiod" class="form-label">Default Accrual Rate (per period)</label>
                                                    <input type="number" class="form-control" id="default_accrualrate_perperiod" name="default_accrualrate_perperiod" 
                                                        value="<?php echo htmlspecialchars($type['default_accrualrate_perperiod']); ?>" required>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="max_carryover_days" class="form-label">Max Carryover Days</label>
                                                    <input type="number" class="form-control" id="max_carryover_days" name="max_carryover_days" 
                                                        value="<?php echo htmlspecialchars($type['max_carryover_days']); ?>" required>
                                                </div>
                                                <div class="col-12">
                                                    <button type="submit" name="update_type" class="btn btn-dark">Update Type</button>
                                                    <a href="mantype.php" class="btn btn-secondary">Cancel</a>
                                                </div>
                                            </form>
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