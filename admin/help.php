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
                                            <?php if ($role === "administrator"): ?>
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
                                                                <li>First card is the number of registered employees in the organization</li>
                                                                <li>Second card is the number of registered departments in the organization </li>
                                                                <li>Third card is the number of Leave types </li>
                                                                <li>Fourth card is the number of leave requests that have been sent </li>
                                                                <li>Fifth card is the number of approved leaves </li>
                                                                <li>Sixth card is the number of new or pending leave requests</li>
                                                                <li>The table is a list of the five most recent leave applications </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="headingProf">
                                                            <button class="accordion-button"type="button" data-bs-toggle="collapse" data-bs-target="#collapseProf" aria-expanded="true" aria-controls="collapseProf">
                                                                Department > Add department/ Manage Departments
                                                            </button>
                                                        </h2>
                                                    </div>
                                                    <div id="collapseProf" class="accordion-collapse collapse " aria-labelledby="headingProf" data-bs-parent="#helpAccordion">
                                                        <div class="accordion-body">
                                                            <ul>
                                                                <li>Click on Departments in the sidebar</li>                                                                
                                                                <li>You can click on Add Department or Manage Departments</li>
                                                                <li>At Add Department you can add a new department and assign a manager then view departments which would take you to manage departments</li>
                                                                <li>At Manage Department you have a list of all departments and their managers in the organization and you can either edit a department's detail or delete a department or you can add a new department here</li>
                                                        </div>
                                                    </div>
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="headingtype">
                                                            <button class="accordion-button"type="button" data-bs-toggle="collapse" data-bs-target="#collapsetype" aria-expanded="true" aria-controls="collapsetype">
                                                                Leaves > Leave Types > Add Leave Types/ Manage Leave Types
                                                            </button>
                                                        </h2>
                                                    </div>
                                                    <div id="collapsetype" class="accordion-collapse collapse " aria-labelledby="headingtype" data-bs-parent="#helpAccordion">
                                                        <div class="accordion-body">
                                                            <ul>
                                                                <li>Click on Leaves then Leave Types in the sidebar</li>                                                                
                                                                <li>You can click on Add Leave Type or Manage Leave Types</li>
                                                                <li>At Add Leave Type you can add a new Leave Type then view Leave Types which would take you to manage Leave Types</li>
                                                                <li>At Manage Leave Types you have a list of all Leave Types in the organization and you can either edit a Leave Type or delete a Leave Type or you can add a new Leave Type here</li>
                                                        </div>
                                                    </div>
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="headingpol">
                                                            <button class="accordion-button"type="button" data-bs-toggle="collapse" data-bs-target="#collapsepol" aria-expanded="true" aria-controls="collapsepol">
                                                                Leaves > Leave Policy > Add Leave  Policy/ Manage Leave Policies
                                                            </button>
                                                        </h2>
                                                    </div>
                                                    <div id="collapsetype" class="accordion-collapse collapse " aria-labelledby="headingpol" data-bs-parent="#helpAccordion">
                                                        <div class="accordion-body">
                                                            <ul>
                                                                <li>Click on Leaves then Leave Policies in the sidebar</li>                                                                
                                                                <li>You can click on Add Leave Policy or Manage Leave Policies</li>
                                                                <li>At Add Leave Policy you can add a new Leave Policy then view Leave Policies which would take you to manage Leave Policies</li>
                                                                <li>At Manage Leave Policies you have a list of all Leave Policies in the organization and you can either edit a Leave Policy or delete a Leave Policy or you can add a new Leave Policy here</li>
                                                        </div>
                                                    </div>
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="headingbal">
                                                            <button class="accordion-button"type="button" data-bs-toggle="collapse" data-bs-target="#collapsebal" aria-expanded="true" aria-controls="collapsebal">
                                                                Leaves > Leave Balances > View Leave Balances
                                                            </button>
                                                        </h2>
                                                    </div>
                                                    <div id="collapsebal" class="accordion-collapse collapse " aria-labelledby="headingbal" data-bs-parent="#helpAccordion">
                                                        <div class="accordion-body">
                                                            <ul>
                                                                <li>Click on Leaves then view Leave Balances in the sidebar</li>                                                                
                                                                <li>You can click on view Leave Balances</li>
                                                                <li>At view Leave Balances you can view a Leave Balance </li>
                                                        </div>
                                                    </div>
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="headingPerm">
                                                            <button class="accordion-button"type="button" data-bs-toggle="collapse" data-bs-target="#collapsePerm" aria-expanded="true" aria-controls="collapsePerm">
                                                                Employees > Add Employee/ Employee Permissions/ Manage Employees
                                                            </button>
                                                        </h2>
                                                    </div>
                                                    <div id="collapsePerm" class="accordion-collapse collapse " aria-labelledby="headingPerm" data-bs-parent="#helpAccordion">
                                                        <div class="accordion-body">
                                                            <ul>
                                                                <li>Click on Employees in the sidebar</li>
                                                                <li>You can click on Add employees, employee Permissions or manage employees in the sidebar</li>
                                                                <li>At Add employee you can add a new Leave employee then view all employees which would take you to manage employees</li>
                                                                <li>At Manage employees you have a list of all employees in the organization and you can either edit employee details or delete an employee record or you can add a new employee here</li>
                                                                <li>At employee permissions, the table contains a list of employees in your department and you can give an employee your manager access especially when you are going on your own  leave</li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="headingleaves">
                                                            <button class="accordion-button"type="button" data-bs-toggle="collapse" data-bs-target="#collapseleaves" aria-expanded="true" aria-controls="collapseleaves">
                                                                Leave Management > All Leaves/ Pending Leaves/ Approved Leaves/ Rejected Leaves
                                                            </button>
                                                        </h2>
                                                    </div>
                                                    <div id="collapseleaves" class="accordion-collapse collapse " aria-labelledby="headingleaves" data-bs-parent="#helpAccordion">
                                                        <div class="accordion-body">
                                                            <ul>
                                                                <li>Click on Leave Management in the sidebar</li>
                                                                <li>You can click on All Leaves, Pending Leaves, Approved Leaves or Rejected Leaves</li>
                                                                <li>At All Leaves you have a list of all leave requests from employees in your department and you can filter based on the leave type, status, employee name or leave date. You can also generate a report in comma separated values or in excel </li>
                                                                <li>At Pending Leaves you have a list of pending leave requests and you can approve or reject said leaves </li>
                                                                <li>At Approved Leaves you have a list of approved leave requests </li>
                                                                <li>At Rejected Leaves you have a list of rejected leave requests </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="headingRep">
                                                            <button class="accordion-button"type="button" data-bs-toggle="collapse" data-bs-target="#collapseRep" aria-expanded="true" aria-controls="collapseRep">
                                                                Leave Report
                                                            </button>
                                                        </h2>
                                                    </div>
                                                    <div id="collapseRep" class="accordion-collapse collapse " aria-labelledby="headingRep" data-bs-parent="#helpAccordion">
                                                        <div class="accordion-body">
                                                            <ul>
                                                                <li>Click on Leave Report in the sidebar</li>
                                                                <li>Here you have a list of all leave requests from employees in your department and you can filter based on status, employee name or leave date. You can also generate a report in comma separated values or in excel </li>
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
</html>
