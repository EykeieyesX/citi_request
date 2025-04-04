<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: AdminLogin.html");
    exit();
}

// Set the default time zone to Asia/Manila
date_default_timezone_set('Asia/Manila');

// Include the database connection details
include '../../user/config.php';
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set timezone for database updates
$conn->query("SET time_zone = '+08:00'");

$birth_id = $_GET['birth_id'];

// Prepared statement to prevent SQL injection
$sql = "SELECT child_first_name, child_middle_name, child_last_name, child_sex, child_date_of_birth, father_first_name, father_last_name, mother_first_name, mother_last_name, status, created_at FROM birth_records WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $birth_id);
$stmt->execute();
$result = $stmt->get_result();
$birth = $result->fetch_assoc();

if (!$birth) {
    echo "Birth Certificate not found!";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../../style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Birth Certificate</title>
    <link rel="icon" type="image/x-icon" href="../../images/lguicon.png" />
</head>
<body>

<div class="view-request-container">
    <div class="view-request-header">
        <h2>Birth Certificate Details</h2>

        <!-- Table for Birth Certificate Information -->
        <table>
            <tr>
                <th>Field</th>
                <th>Value</th>
            </tr>
            <tr>
                <td>Child's First Name:</td>
                <td><?php echo htmlspecialchars($birth['child_first_name']); ?></td>
            </tr>
            <tr>
                <td>Child's Middle Name:</td>
                <td><?php echo htmlspecialchars($birth['child_middle_name']); ?></td>
            </tr>
            <tr>
                <td>Child's Last Name:</td>
                <td><?php echo htmlspecialchars($birth['child_last_name']); ?></td>
            </tr>
            <tr>
                <td>Sex:</td>
                <td><?php echo htmlspecialchars($birth['child_sex']); ?></td>
            </tr>
            <tr>
                <td>Date of Birth:</td>
                <td><?php echo htmlspecialchars($birth['child_date_of_birth']); ?></td>
            </tr>
            <tr>
                <td>Father's First Name:</td>
                <td><?php echo htmlspecialchars($birth['father_first_name']); ?></td>
            </tr>
            <tr>
                <td>Father's Last Name:</td>
                <td><?php echo htmlspecialchars($birth['father_last_name']); ?></td>
            </tr>
            <tr>
                <td>Mother's First Name:</td>
                <td><?php echo htmlspecialchars($birth['mother_first_name']); ?></td>
            </tr>
            <tr>
                <td>Mother's Last Name:</td>
                <td><?php echo htmlspecialchars($birth['mother_last_name']); ?></td>
            </tr>
            <tr>
                <td>Status:</td>
                <td><?php echo htmlspecialchars($birth['status']); ?></td>
            </tr>
            <tr>
                <td>Created At:</td>
                <td><?php
                    $createdAt = new DateTime($birth['created_at']);
                    echo $createdAt->format('F j, Y g:i a');
                ?></td>
            </tr>
        </table>
    </div>
</div>
    <div class="backtoreview">
             <a href="../Reviewsubmissions.php">Back to Review Submissions</a>
    </div>

</body>
</html>
