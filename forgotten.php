<?php
session_start();

$host = "localhost";
$db   = "leave_management";
$user = "root";
$pass = "";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"]);
    $new_password = $_POST["new_password"];
    $confirm_password = $_POST["confirm_password"];

    if (empty($email) || empty($new_password) || empty($confirm_password)) {
        $error = "All fields are required.";
    } elseif ($new_password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        // Check if email exists
        $stmt = $conn->prepare("SELECT employee_id FROM employee WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->bind_result($employee_id);
            $stmt->fetch();
            $stmt->close();

            // Update password
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $update = $conn->prepare("UPDATE employee SET password = ? WHERE employee_id = ?");
            $update->bind_param("si", $hashed_password, $employee_id);

            if ($update->execute()) {
                $success = "Password updated successfully. <a href='login.php'>Login here</a>";
            } else {
                $error = "Error updating password: " . $update->error;
            }
            $update->close();
        } else {
            $error = "Email not found in records.";
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <link rel="icon" type="image/x-icon" href="assets/favicon/favicon.ico"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: #add4adff;
        }
        .container { width: 400px; }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="card bg-light shadow-lg rounded-4 border-0 mb-5">
            <div class="card-header text-center">
                <h2>Forgot Password</h2>
            </div>
            <?php if ($error): ?>
                <div class="alert alert-danger"><?= $error; ?></div>
            <?php endif; ?>
            <?php if ($success): ?>
                <div class="alert alert-success"><?= $success; ?></div>
            <?php else: ?>
                <div class="card-body">
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label class="form-label">Registered Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">New Password</label>
                            <input type="password" name="new_password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" name="confirm_password" class="form-control" required>
                        </div>
                        <div class="d-grid">
                            <button class="btn btn-dark rounded-4 m-4" type="submit">
                                Login
                            </button>
                        </div>
                    </form>
                    <div class="text-center">
                        <a href="login.php" class="text-decoration-none">Remember? Back to Login</a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>