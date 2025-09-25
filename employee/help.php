<?php
session_start();

if (!isset($_SESSION["employee_id"])) {
    header("Location: login.php");
    exit;
}

$role = $_SESSION["role"];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Help Center</title>
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
                    <h6>System Guide</h6>
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
                                Help Center
                            </h2>
                            <div class="row">
                                <div class="col-12">
                                    <div class="card shadow">
                                        <div class="card-body py-4">
                                            <?php if ($role === "employee"): ?>
                                                <div class="accordion" id="helpAccordion">
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="headingDash">
                                                            <button class="accordion-button"type="button" data-bs-toggle="collapse" data-bs-target="#collapseDash" aria-expanded="true" aria-controls="collapseDash">
                                                                Dashboard
                                                            </button>
                                                        </h2>
                                                    </div>
                                                    <div id="collapseDash" class="accordion-collapse collapse show" aria-labelledby="headingDash" data-bs-parent="#helpAccordion">
                                                        <div class="accordion-body">
                                                            <ul>
                                                                <li>Click on Dashboard in the sidebar</li>
                                                                <li>First card is the number of leave requests that you've sent </li>
                                                                <li>Second card is the number of approved leaves </li>
                                                                <li>Third card is the number of new or pending leave requests you've sent  out</li>
                                                                <li>The table is a list of the five most recent leave applications </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="headingProf">
                                                            <button class="accordion-button"type="button" data-bs-toggle="collapse" data-bs-target="#collapseProf" aria-expanded="true" aria-controls="collapseProf">
                                                                Profile
                                                            </button>
                                                        </h2>
                                                    </div>
                                                    <div id="collapseProf" class="accordion-collapse collapse " aria-labelledby="headingProf" data-bs-parent="#helpAccordion">
                                                        <div class="accordion-body">
                                                            <ul>
                                                                <li>Click on My Profle in the sidebar</li>
                                                                <li>You can view your details here</li>
                                                        </div>
                                                    </div>
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="headingleaves">
                                                            <button class="accordion-button"type="button" data-bs-toggle="collapse" data-bs-target="#collapseleaves" aria-expanded="true" aria-controls="collapseleaves">
                                                                My Leave > Apply for Leave/ leave Balance/ leave status
                                                            </button>
                                                        </h2>
                                                    </div>
                                                    <div id="collapseleaves" class="accordion-collapse collapse " aria-labelledby="headingleaves" data-bs-parent="#helpAccordion">
                                                        <div class="accordion-body">
                                                            <ul>
                                                                <li>Click on Leaves in the sidebar</li>
                                                                <li>You can click on Apply for Leave, leave Balance or leave status</li>
                                                                <li>At Apply for leave you can send a leave request</li>
                                                                <li>At leave Balance you can view you leave balance</li>
                                                                <li>At leave status you can track your leave request and view its status </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="headingRep">
                                                            <button class="accordion-button"type="button" data-bs-toggle="collapse" data-bs-target="#collapseRep" aria-expanded="true" aria-controls="collapseRep">
                                                                Leave History
                                                            </button>
                                                        </h2>
                                                    </div>
                                                    <div id="collapseRep" class="accordion-collapse collapse " aria-labelledby="headingRep" data-bs-parent="#helpAccordion">
                                                        <div class="accordion-body">
                                                            <ul>
                                                                <li>Click on Leave History in the sidebar</li>
                                                                <li>Here you have a table of all your leave requests</li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="headingSet">
                                                            <button class="accordion-button"type="button" data-bs-toggle="collapse" data-bs-target="#collapseSet" aria-expanded="true" aria-controls="collapseSet">
                                                                Settings
                                                            </button>
                                                        </h2>
                                                    </div>
                                                    <div id="collapseSet" class="accordion-collapse collapse " aria-labelledby="headingSet" data-bs-parent="#helpAccordion">
                                                        <div class="accordion-body">
                                                            <ul>
                                                                <li>Click on Settings in the sidebar</li>
                                                                <li>First card Has your role</li>
                                                                <li>Second card has your name contact information. You can update your contact information that is your email and mobile number </li>
                                                                <li>Third card allows you to change your password </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
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