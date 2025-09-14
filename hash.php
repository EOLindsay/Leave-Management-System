<?php
$host = "localhost";
$db = "leave_management";
$user = "root";
$pass = "";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all employees
$result = $conn->query("SELECT employee_id, password FROM employee");

while ($row = $result->fetch_assoc()) {
    $employee_id = $row['employee_id'];
    $plain_password = $row['password'];

    // Hash the plain password
    $hashed_password = password_hash($plain_password, PASSWORD_DEFAULT);

    // Update the database with the hashed password
    $update = $conn->prepare("UPDATE employee SET password = ? WHERE employee_id = ?");
    $update->bind_param("si", $hashed_password, $employee_id);
    $update->execute();
    $update->close();

    echo "Employee ID $employee_id password hashed.<br>";
}

$conn->close();

echo "✅ All passwords have been hashed successfully!";
?>
