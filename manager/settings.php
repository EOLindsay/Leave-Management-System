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

$employee_id = $_SESSION["employee_id"];
$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["update_contact"])) {
    $email = trim($_POST["email"]);
    $phone = trim($_POST["mobile"]);

    if (!empty($email)) {
        $stmt = $conn->prepare("UPDATE employee SET email=?, mobile=? WHERE employee_id=?");
        $stmt->bind_param("ssi", $email, $phone, $employee_id);
        if ($stmt->execute()) {
            $success = "Contact details updated successfully.";
        } else {
            $error = "Error updating contact details: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $error = "Email cannot be empty.";
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["update_password"])) {
    $current_password = $_POST["current_password"];
    $new_password     = $_POST["new_password"];
    $confirm_password = $_POST["confirm_password"];

    if (!empty($current_password) && !empty($new_password) && !empty($confirm_password)) {
        $stmt = $conn->prepare("SELECT password FROM employee WHERE employee_id=?");
        $stmt->bind_param("i", $employee_id);
        $stmt->execute();
        $stmt->bind_result($hashed_password);
        $stmt->fetch();
        $stmt->close();

        if (password_verify($current_password, $hashed_password)) {
            if ($new_password === $confirm_password) {
                $new_hashed = password_hash($new_password, PASSWORD_DEFAULT);
                $update = $conn->prepare("UPDATE employee SET password=? WHERE employee_id=?");
                $update->bind_param("si", $new_hashed, $employee_id);
                if ($update->execute()) {
                    $success = "Password updated successfully.";
                } else {
                    $error = "Error updating password: " . $update->error;
                }
                $update->close();
            } else {
                $error = "New passwords do not match.";
            }
        } else {
            $error = "Current password is incorrect.";
        }
    } else {
        $error = "Please fill in all password fields.";
    }
}

$stmt = $conn->prepare("SELECT first_name, last_name, email, mobile, role FROM employee WHERE employee_id=?");
$stmt->bind_param("i", $employee_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

$conn->close();
?>


<!DOCTYPE html>
<html>
<head>
    <title>Settings</title>
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
                    <h6>Update Contact Details | Change Password</h6>
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
                                Settings
                            </h2>
                            <div class="row">
                                <div class="col-12 col-md-4">
                                    <div class="card shadow">
                                        <div class="card-body py-4">
                                            <strong>Role:</strong> <?= ucfirst($user['role']); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php if ($success): ?>
                                <div class="alert alert-success"><?= $success; ?></div>
                            <?php endif; ?>
                            <?php if ($error): ?>
                                <div class="alert alert-danger"><?= $error; ?></div>
                            <?php endif; ?>
                            <div class="row">
                                <div class="col-12 col-md-8">
                                    <div class="card shadow">
                                        <div class="card-body py-4">
                                            <form method="POST">
                                                <div class="mb-3">
                                                    <label class="form-label">First Name</label>
                                                    <input type="text" class="form-control" value="<?= htmlspecialchars($user['first_name']); ?>" disabled>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Last Name</label>
                                                    <input type="text" class="form-control" value="<?= htmlspecialchars($user['last_name']); ?>" disabled>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Email</label>
                                                    <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($user['email']); ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Mobile</label>
                                                    <input type="text" class="form-control" name="mobile" value="<?= htmlspecialchars($user['mobile']); ?>">
                                                </div>
                                                <button type="submit" name="update_contact" class="btn btn-dark">Update Contact</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-8">
                                    <div class="card shadow">
                                        <div class="card-body py-4">
                                            <form method="POST">
                                                <div class="mb-3">
                                                    <label class="form-label">Current Password</label>
                                                    <input type="password" class="form-control" name="current_password" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">New Password</label>
                                                    <input type="password" class="form-control" name="new_password" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Confirm New Password</label>
                                                    <input type="password" class="form-control" name="confirm_password" required>
                                                </div>
                                                <button type="submit" name="update_password" class="btn btn-dark">Update Password</button>
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