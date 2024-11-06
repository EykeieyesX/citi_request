<?php
session_start();

// Ensure user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Include the database connection
include '../config.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the reference_id from the URL
$reference_id = $_GET['reference_id'];

// Prepared statement to prevent SQL injection
$sql = "SELECT * FROM request WHERE reference_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $reference_id);
$stmt->execute();
$result = $stmt->get_result();
$request = $result->fetch_assoc();

if (!$request) {
    echo "Request not found!";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Request - User</title>
    <link rel="stylesheet" href="../../style.css">
</head>
<body>

<div class="view-request-container">
    <div class="view-request-header">
        <h2>Request Details</h2>
        
        <!-- Table for Request Information -->
        <table>
            <tr>
                <th>Field</th>
                <th>Value</th>
            </tr>
            <tr>
                <td><strong>Reference ID:</strong></td>
                <td><?php echo htmlspecialchars($request['reference_id']); ?></td>
            </tr>
            <tr>
                <td><strong>Email:</strong></td>
                <td><?php echo htmlspecialchars($request['email']); ?></td>
            </tr>
            <tr>
                <td><strong>Topic:</strong></td>
                <td><?php echo htmlspecialchars($request['topic']); ?></td>
            </tr>
            <tr>
                <td><strong>Description:</strong></td>
                <td><?php echo nl2br(htmlspecialchars($request['description'])); ?></td>
            </tr>
            <tr>
                <td><strong>Location:</strong></td>
                <td><?php echo htmlspecialchars($request['location']); ?></td>
            </tr>
            <tr>
                <td><strong>Status:</strong></td>
                <td><?php echo htmlspecialchars($request['status']); ?></td>
            </tr>
            <tr>
                <td><strong>Submitted Date:</strong></td>
                <td><?php echo htmlspecialchars($request['submitted_date']); ?></td>
            </tr>
            <tr>
                <td><strong>Last Updated:</strong></td>
                <td><?php echo htmlspecialchars($request['last_updated']); ?></td>
            </tr>
            <!-- Optionally, show admin message if it exists -->
            <?php if (!empty($request['adminmessage'])): ?>
            <tr>
                <td><strong>Admin Message:</strong></td>
                <td><?php echo nl2br(htmlspecialchars($request['adminmessage'])); ?></td>
            </tr>
            <?php endif; ?>
        </table>
    </div>
</div>
<div class="backtotrack">
            <a href="../track.php">Back to Track Requests</a>
</div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>