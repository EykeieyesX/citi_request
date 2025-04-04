<?php
// Enable full error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../AdminLogin.html");
    exit();
}

// Set the default time zone to Asia/Manila
date_default_timezone_set('Asia/Manila');

// Database connection
include '../../user/config.php';
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set timezone for updates
$conn->query("SET time_zone = '+08:00'");

// Validate request number
if (!isset($_GET['crequest_number']) || empty($_GET['crequest_number'])) {
    die("Invalid request number");
}
$crequest_number = $_GET['crequest_number'];

// List of all valid statuses
$all_statuses = ['Submitted', 'Processing', 'Ready-for-Pickup', 'Completed', 'Cancelled'];

// Predefined admin messages
$admin_messages = [
    'Submitted' => 'This request is marked as submitted.',
    'Processing' => 'This request is currently being processed.',
    'Ready-for-Pickup' => 'Your barangay clearance is Ready for Pick-up.',
    'Cancelled' => 'Your request is cancelled, due to improper or lack of information.',
    'Completed' => 'Your request has been completed, we await your feedback.'
];

// Handle status update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate inputs
    $new_status = $_POST['status'] ?? '';
    
    if (!in_array($new_status, $all_statuses)) {
        die("Invalid status selected");
    }
    
    // Get corresponding admin message for the selected status
    $admin_message = $admin_messages[$new_status] ?? '';

    try {
        // Prepare update statement
        $sql_update = "UPDATE barangay_clearance SET 
                      status = ?, 
                      adminmessage = ?, 
                      last_updated = NOW() 
                      WHERE crequest_number = ?";
        
        $stmt_update = $conn->prepare($sql_update);
        if (!$stmt_update) {
            throw new Exception("Prepare failed: " . $conn->error);
        }
        
        $bind_result = $stmt_update->bind_param("sss", $new_status, $admin_message, $crequest_number);
        if (!$bind_result) {
            throw new Exception("Bind failed: " . $stmt_update->error);
        }
        
        if (!$stmt_update->execute()) {
            throw new Exception("Execute failed: " . $stmt_update->error);
        }
        
        $stmt_update->close();
        
        // Redirect to prevent form resubmission
        header("Location: view_clearance.php?crequest_number=" . urlencode($crequest_number));
        exit();
        
    } catch (Exception $e) {
        die("Error updating record: " . $e->getMessage());
    }
}

// Fetch the request details
$sql = "SELECT * FROM barangay_clearance WHERE crequest_number = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$bind_result = $stmt->bind_param("s", $crequest_number);
if (!$bind_result) {
    die("Bind failed: " . $stmt->error);
}

if (!$stmt->execute()) {
    die("Execute failed: " . $stmt->error);
}

$result = $stmt->get_result();
$clearance_request = $result->fetch_assoc();

if (!$clearance_request) {
    die("Barangay Clearance request not found!");
}

$stmt->close();

// Format dates for display with proper timezone handling
try {
    $date_issued = new DateTime($clearance_request['dateissued'], new DateTimeZone('Asia/Manila'));
    $date_submitted = DateTime::createFromFormat('Y-m-d H:i:s', $clearance_request['datesubmitted'], new DateTimeZone('Asia/Manila'));
    if (!$date_submitted) {
        $date_submitted = new DateTime($clearance_request['datesubmitted'], new DateTimeZone('Asia/Manila'));
    }
    
    $last_updated = DateTime::createFromFormat('Y-m-d H:i:s', $clearance_request['last_updated'], new DateTimeZone('Asia/Manila'));
    if (!$last_updated) {
        $last_updated = new DateTime($clearance_request['last_updated'], new DateTimeZone('Asia/Manila'));
    }
} catch (Exception $e) {
    die("Error processing dates: " . $e->getMessage());
}

$current_status = $clearance_request['status'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../../style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Barangay Clearance Request</title>
    <link rel="icon" type="image/x-icon" href="../../images/lguicon.png" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <style>
        .download-buttons {
            margin-top: 20px;
            text-align: center;
        }
        .download-buttons button {
            padding: 10px 20px;
            font-size: 16px;
            margin: 10px;
            cursor: pointer;
            border: none;
            background-color: #4CAF50;
            color: white;
            border-radius: 5px;
        }
        .download-buttons button:hover {
            background-color: #45a049;
        }
        .status-update-form {
            margin: 20px 0;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        .status-update-form label {
            display: block;
            margin: 10px 0 5px;
        }
        .status-update-form select {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .status-update-form .admin-message-display {
            margin: 10px 0;
            padding: 10px;
            background-color: #f0f0f0;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-style: italic;
        }
        .status-update-form button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
            display: block;
            width: 100%;
        }
        .status-update-form button:hover {
            background-color: #45a049;
        }
        .request-details table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .request-details th, 
        .request-details td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .request-details th {
            background-color: #f2f2f2;
        }
        .status-message {
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
        }
        .error-message {
            background-color: #ffdddd;
            color: #d8000c;
        }
        .success-message {
            background-color: #ddffdd;
            color: #4F8A10;
        }
    </style>
</head>
<body>

<div class="view-request-container">
    <div class="view-request-header">
        <h2>Barangay Clearance Request Details</h2>
        
        <?php if (isset($_GET['error'])): ?>
            <div class="status-message error-message">
                <?php echo htmlspecialchars($_GET['error']); ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_GET['success'])): ?>
            <div class="status-message success-message">
                Status updated successfully!
            </div>
        <?php endif; ?>
        
        <div class="request-details">
            <table>
                <tr>
                    <th>Field</th>
                    <th>Value</th>
                </tr>
                <tr>
                    <td>Request Number:</td>
                    <td><?php echo htmlspecialchars($clearance_request['crequest_number']); ?></td>
                </tr>
                <tr>
                    <td>Username:</td>
                    <td><?php echo htmlspecialchars($clearance_request['username']); ?></td>
                </tr>
                <tr>
                    <td>Last Name:</td>
                    <td><?php echo htmlspecialchars($clearance_request['lastname']); ?></td>
                </tr>
                <tr>
                    <td>First Name:</td>
                    <td><?php echo htmlspecialchars($clearance_request['firstname']); ?></td>
                </tr>
                <tr>
                    <td>Middle Initial:</td>
                    <td><?php echo htmlspecialchars($clearance_request['middleinitial']); ?></td>
                </tr>
                <tr>
                    <td>Age:</td>
                    <td><?php echo htmlspecialchars($clearance_request['age']); ?></td>
                </tr>
                <tr>
                    <td>Ordinal Number:</td>
                    <td><?php echo htmlspecialchars($clearance_request['ordinalnumber']); ?></td>
                </tr>
                <tr>
                    <td>Date Issued:</td>
                    <td><?php echo $date_issued->format('F j, Y'); ?></td>
                </tr>
                <tr>
                    <td>Status:</td>
                    <td><?php echo htmlspecialchars($clearance_request['status']); ?></td>
                </tr>
                <tr>
                    <td>Payment Status:</td>
                    <td><?php echo $clearance_request['paid'] ? 'Paid' : 'Unpaid'; ?></td>
                </tr>
                <tr>
                    <td>Transaction ID:</td>
                    <td><?php echo htmlspecialchars($clearance_request['transaction_id']); ?></td>
                </tr>
                <tr>
                    <td>Date Submitted:</td>
                    <td><?php echo $date_submitted->format('F j, Y'); ?></td>
                </tr>
                <tr>
                    <td>Last Updated:</td>
                    <td><?php echo $last_updated->format('F j, Y'); ?></td>
                </tr>
                <?php if (!empty($clearance_request['adminmessage'])): ?>
                <tr>
                    <td>Admin Message:</td>
                    <td><?php echo nl2br(htmlspecialchars($clearance_request['adminmessage'])); ?></td>
                </tr>
                <?php endif; ?>
            </table>
        </div>

        <div class="status-update-form">
            <h3>Update Status</h3>
            <form method="POST" action="">
                <label for="status">New Status:</label>
                <select name="status" id="status" required <?php echo ($current_status === 'Completed' || $current_status === 'Cancelled') ? 'disabled' : ''; ?> onchange="updateAdminMessageDisplay(this.value)">
                    <option value="">Select Status</option>
                    <?php foreach ($all_statuses as $status): ?>
                        <option value="<?php echo $status; ?>" <?php echo ($status === $current_status) ? 'selected' : ''; ?>>
                            <?php echo $status; ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label for="admin_message_display">Admin Message:</label>
                <div id="admin_message_display" class="admin-message-display">
                    <?php echo htmlspecialchars($admin_messages[$current_status] ?? 'Select a status to see the message'); ?>
                </div>

                <!-- Hidden input to store the admin message -->
                <input type="hidden" id="admin_message_hidden" name="admin_message" value="<?php echo htmlspecialchars($admin_messages[$current_status] ?? ''); ?>">

                <?php if ($current_status !== 'Completed' && $current_status !== 'Cancelled'): ?>
                    <button type="submit">Update Status</button>
                <?php else: ?>
                    <p style="color:red;">Status cannot be changed from <?php echo $current_status; ?>.</p>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <div class="download-buttons">
        <button onclick="downloadCSV()">Download as CSV</button>
        <button onclick="generatePDF()">Download as PDF</button>
    </div>
</div>

<script>
    // Function to update admin message display based on selected status
    function updateAdminMessageDisplay(status) {
        // Get predefined admin messages from PHP
        const adminMessages = {
            'Submitted': '<?php echo addslashes($admin_messages['Submitted']); ?>',
            'Processing': '<?php echo addslashes($admin_messages['Processing']); ?>',
            'Ready-for-Pickup': '<?php echo addslashes($admin_messages['Ready-for-Pickup']); ?>',
            'Completed': '<?php echo addslashes($admin_messages['Completed']); ?>',
            'Cancelled': '<?php echo addslashes($admin_messages['Cancelled']); ?>'
        };
        
        // Update the display div
        const messageDisplay = document.getElementById('admin_message_display');
        messageDisplay.textContent = adminMessages[status] || 'Select a status to see the message';
        
        // Update the hidden input value
        const hiddenInput = document.getElementById('admin_message_hidden');
        hiddenInput.value = adminMessages[status] || '';
    }

    // Initialize admin message display based on current status when page loads
    document.addEventListener('DOMContentLoaded', function() {
        const statusSelect = document.getElementById('status');
        if (statusSelect.value) {
            updateAdminMessageDisplay(statusSelect.value);
        }
    });

    function downloadCSV() {
        const crequestNumber = "<?php echo htmlspecialchars($clearance_request['crequest_number']); ?>";
        const csvContent = [
            ["Field", "Value"],
            ["Request Number", crequestNumber],
            ["Username", "<?php echo htmlspecialchars($clearance_request['username']); ?>"],
            ["Last Name", "<?php echo htmlspecialchars($clearance_request['lastname']); ?>"],
            ["First Name", "<?php echo htmlspecialchars($clearance_request['firstname']); ?>"],
            ["Middle Initial", "<?php echo htmlspecialchars($clearance_request['middleinitial']); ?>"],
            ["Age", "<?php echo htmlspecialchars($clearance_request['age']); ?>"],
            ["Ordinal Number", "<?php echo htmlspecialchars($clearance_request['ordinalnumber']); ?>"],
            ["Date Issued", "<?php echo $date_issued->format('F/j/Y'); ?>"],
            ["Status", "<?php echo htmlspecialchars($clearance_request['status']); ?>"],
            ["Payment Status", "<?php echo $clearance_request['paid'] ? 'Paid' : 'Unpaid'; ?>"],
            ["Transaction ID", "<?php echo htmlspecialchars($clearance_request['transaction_id']); ?>"],
            ["Date Submitted", "<?php echo $date_submitted->format('F/j/Y'); ?>"],
            ["Last Updated", "<?php echo $last_updated->format('F/j/Y'); ?>"],
            ["Admin Message", "<?php echo str_replace('"', '""', $clearance_request['adminmessage'] ?? ''); ?>"]
        ];

        let csv = "";
        csvContent.forEach(row => {
            csv += row.map(field => `"${field}"`).join(",") + "\r\n";
        });

        const blob = new Blob([csv], { type: "text/csv;charset=utf-8;" });
        const url = URL.createObjectURL(blob);
        const link = document.createElement("a");
        link.href = url;
        link.download = `${crequestNumber}-barangay-clearance-request.csv`;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    function generatePDF() {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();
        
        // Set document properties
        doc.setProperties({
            title: `Barangay Clearance Request - <?php echo $clearance_request['crequest_number']; ?>`,
            subject: 'Barangay Clearance Application',
            author: 'LGU System',
            keywords: 'barangay, clearance, request',
            creator: 'LGU System'
        });

        // Add header
        doc.setFontSize(18);
        doc.setTextColor(0, 0, 255);
        doc.text("Barangay Clearance Request Details", 105, 20, { align: "center" });
        
        doc.setFontSize(12);
        doc.setTextColor(0, 0, 0);
        doc.text(`Request Number: <?php echo $clearance_request['crequest_number']; ?>`, 14, 30);

        // Add request details
        let y = 40;
        const addField = (label, value) => {
            doc.setFont("helvetica", "bold");
            doc.text(`${label}:`, 20, y);
            doc.setFont("helvetica", "normal");
            doc.text(value, 70, y);
            y += 7;
        };

        addField("Username", "<?php echo $clearance_request['username']; ?>");
        addField("Name", "<?php echo $clearance_request['firstname'] . ' ' . $clearance_request['lastname']; ?>");
        addField("Middle Initial", "<?php echo $clearance_request['middleinitial']; ?>");
        addField("Age", "<?php echo $clearance_request['age']; ?>");
        addField("Ordinal Number", "<?php echo $clearance_request['ordinalnumber']; ?>");
        addField("Date Issued", "<?php echo $date_issued->format('F j, Y'); ?>");
        addField("Status", "<?php echo $clearance_request['status']; ?>");
        addField("Payment Status", "<?php echo $clearance_request['paid'] ? 'Paid' : 'Unpaid'; ?>");
        addField("Date Submitted", "<?php echo $date_submitted->format('F j, Y'); ?>");
        addField("Last Updated", "<?php echo $last_updated->format('F j, Y'); ?>");

        // Add admin message if exists
        <?php if (!empty($clearance_request['adminmessage'])): ?>
            y += 7;
            doc.setFont("helvetica", "bold");
            doc.text("Admin Message:", 20, y);
            doc.setFont("helvetica", "normal");
            const splitText = doc.splitTextToSize("<?php echo addslashes($clearance_request['adminmessage']); ?>", 180);
            doc.text(splitText, 20, y + 7);
        <?php endif; ?>

        // Save the PDF
        doc.save(`<?php echo $clearance_request['crequest_number']; ?>-barangay-clearance-request.pdf`);
    }
</script>

<div class="backtoreview">
    <a href="../Reviewsubmissions.php">Back to Review Submissions</a>
</div>

</body>
</html>

<?php
$conn->close();
?>