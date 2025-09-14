<?php
session_start();

// Database credentials
$host = "localhost";
$db = "leave_management";
$user = "root";
$pass = "";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT employee_id, first_name, password, role FROM employee WHERE username = ?");
    $stmt->bind_param("s", $username);
    
    if ($stmt->execute()) {
        $stmt->store_result();
        
        if ($stmt->num_rows === 1) {
            $stmt->bind_result($employee_id, $first_name, $db_password, $role);
            $stmt->fetch();

            if (password_verify($password, $db_password)) {
                $_SESSION["employee_id"] = $employee_id;
                $_SESSION["username"] = $username;
                $_SESSION["first_name"] = $first_name;
                $_SESSION["role"] = $role;

                switch ($role) {
                    case 'administrator':
                        header("Location: admin.php");
                        break;
                    case 'manager':
                        header("Location: manager.php");
                        break;
                    case 'employee':
                        header("Location: employee.php");
                        break;
                    default:
                        $error = "Invalid user role.";
                }
                exit;
            } else {
                $error = "Incorrect password.";
            }
        } else {
            $error = "Username not found.";
        }
    } else {
        $error = "Login failed. Please try again.";
    }

    $stmt->close();
}
$conn->close();
?>


<!-- HTML FORM -->
<!DOCTYPE html>
<html>
<head>
    <title>Leave Management System | Login </title>
    <link rel="icon" type="image/x-icon" href="assets/favicon/favicon.ico"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            margin:0;
            padding: 0px;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            backdrop-filter: blur(5px);
            overflow: hidden;
        }
        .bg{
            animation:slide 6s ease-in-out infinite alternate;
            background-image: linear-gradient(-60deg, #043004ff 50%, rgba(255, 255, 255, 1) 50%);
            bottom:0;
            left:-50%;
            opacity:.5;
            position:fixed;
            right:-50%;
            top:0;
            z-index:-1;
        }
        .bg2{
            animation-direction:alternate-reverse;
            animation-duration:7s;
        }
        .bg3{
            animation-duration:8s;
        }
        @keyframes slide {
            0% {
                transform:translateX(-25%);
            }
            100% {
                transform:translateX(25%);
            }
        }
        .container{
            width: 400px;
        }
    </style>
</head>
<body>
    <div class="bg"></div>
    <div class="bg bg2"></div>
    <div class="bg bg3"></div>
    <div>
        <img src="assets/img/logo.png" alt="">
    </div>
    <div class="container mt-5">
        <!-- login card -->
        <div class="card bg-light rounded-4 shadow-lg border-0 mb-5">
            <div class="card-header text-center">
                <h2>LOGIN</h2>
            </div>
            <?php if ($error) echo "<p style='color:red;'>$error</p>"; ?>
            <div class="card-body">
                <form method="post" action="">
                    <div class="mb-3">
                        <label class="form-label">Username:</label>
                        <input type="text" class="form-control" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password:</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>
                    <div class="d-grid">
                        <button class="btn btn-dark rounded-4 m-4" type="submit">
                            Login
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
</body>
</html>
