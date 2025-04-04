<?php
session_start();

// Ensure user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit();
}

// Include the database connection
include '../config.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the crequest_number from the URL
$crequest_number = $_GET['crequest_number'];
$username = $_SESSION['username'];

// Prepared statement to prevent SQL injection
$sql = "SELECT * FROM barangay_clearance WHERE crequest_number = ? AND username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $crequest_number, $username);
$stmt->execute();
$result = $stmt->get_result();
$request = $result->fetch_assoc();

if (!$request) {
    echo "Request not found or you don't have permission to view it!";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Barangay Clearance Request</title>
    <link rel="stylesheet" href="../../style.css">
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
        
        .action-buttons {
            margin-top: 20px;
            text-align: center;
        }
        
        .action-buttons a {
            display: inline-block;
            padding: 10px 20px;
            margin: 10px;
            text-decoration: none;
            color: white;
            background-color: #007bff;
            border-radius: 5px;
        }
        
        .action-buttons a:hover {
            background-color: #0056b3;
        }
        
        .action-buttons a.feedback-btn {
            background-color: #28a745;
        }
        
        .action-buttons a.feedback-btn:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
<div class="view-request-container">
    <div class="view-request-header">
        <h2>Barangay Clearance Request Details</h2>
        
        <!-- Table for Request Information -->
        <table>
            <tr>
                <th>Field</th>
                <th>Value</th>
            </tr>
            <tr>
                <td><strong>Request Number:</strong></td>
                <td><?php echo htmlspecialchars($request['crequest_number']); ?></td>
            </tr>
            <tr>
                <td><strong>First Name:</strong></td>
                <td><?php echo htmlspecialchars($request['firstname']); ?></td>
            </tr>
            <tr>
                <td><strong>Last Name:</strong></td>
                <td><?php echo htmlspecialchars($request['lastname']); ?></td>
            </tr>
            <tr>
                <td><strong>Middle Initial:</strong></td>
                <td><?php echo htmlspecialchars($request['middleinitial']); ?></td>
            </tr>
            <tr>
                <td><strong>Age:</strong></td>
                <td><?php echo htmlspecialchars($request['age']); ?></td>
            </tr>
            <tr>
                <td><strong>Ordinal Number:</strong></td>
                <td><?php echo htmlspecialchars($request['ordinalnumber']); ?></td>
            </tr>
            <tr>
                <td><strong>Transaction ID:</strong></td>
                <td><?php echo htmlspecialchars($request['transaction_id']); ?></td>
            </tr>
            <tr>
                <td><strong>Status:</strong></td>
                <td><?php echo htmlspecialchars($request['status']); ?></td>
            </tr>
        </table>
    </div>
    <div class="download-buttons">
        <button onclick="downloadCSV()">Download as CSV</button>
        <button onclick="generatePDF()">Download as PDF</button>
    </div>
</div>
<div class="action-buttons">
    <a href="../track.php">Back to Track Requests</a>
    <?php if ($request['status'] === 'Completed'): ?>
        <a href="feedback_clearance.php?crequest_number=<?php echo htmlspecialchars($request['crequest_number']); ?>" class="feedback-btn">Provide Feedback for this Request</a>
    <?php endif; ?>
</div>
<script>
    // Function to download data as CSV
    function downloadCSV() {
        const crequestNumber = "<?php echo htmlspecialchars($request['crequest_number']); ?>";
        const csvContent = [
            ["Field", "Value"],
            ["Request Number", crequestNumber],
            ["First Name", "<?php echo htmlspecialchars($request['firstname']); ?>"],
            ["Last Name", "<?php echo htmlspecialchars($request['lastname']); ?>"],
            ["Middle Initial", "<?php echo htmlspecialchars($request['middleinitial']); ?>"],
            ["Age", "<?php echo htmlspecialchars($request['age']); ?>"],
            ["Ordinal Number", "<?php echo htmlspecialchars($request['ordinalnumber']); ?>"],
            ["Transaction ID", "<?php echo htmlspecialchars($request['transaction_id']); ?>"],
            ["Status", "<?php echo htmlspecialchars($request['status']); ?>"]
        ];

        let csvFile = "";
        csvContent.forEach(function(rowArray) {
            let row = rowArray.join(",");
            csvFile += row + "\r\n";
        });

        let hiddenElement = document.createElement('a');
        hiddenElement.href = 'data:text/csv;charset=utf-8,' + encodeURI(csvFile);
        hiddenElement.target = '_blank';
        hiddenElement.download = `${requestNumber}-Barangay-Clearance-Request.csv`;
        hiddenElement.click();
    }

    // Function to generate PDF
    function generatePDF() {
        const crequestNumber = "<?php echo htmlspecialchars($request['crequest_number']); ?>";
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();
        doc.setFont("helvetica");

        // Set blue header at the top of the table
        doc.setFillColor(0, 0, 255); // Blue color
        doc.rect(20, 10, 160, 10, 'F'); // Rectangle header above the table
        doc.setTextColor(255, 255, 255); // White text color
        doc.text("Barangay Clearance Request Details", 100, 17, null, null, "center"); // Centered header text

        // Fields for the request details
        const fields = [
            { label: "Request Number", value: "<?php echo htmlspecialchars($request['crequest_number']); ?>" },
            { label: "First Name", value: "<?php echo htmlspecialchars($request['firstname']); ?>" },
            { label: "Last Name", value: "<?php echo htmlspecialchars($request['lastname']); ?>" },
            { label: "Middle Initial", value: "<?php echo htmlspecialchars($request['middleinitial']); ?>" },
            { label: "Age", value: "<?php echo htmlspecialchars($request['age']); ?>" },
            { label: "Ordinal Number", value: "<?php echo htmlspecialchars($request['ordinalnumber']); ?>" },
            { label: "Transaction ID", value: "<?php echo htmlspecialchars($request['transaction_id']); ?>" },
            { label: "Status", value: "<?php echo htmlspecialchars($request['status']); ?>" }
        ];

        // Table settings
        const margin = 20; // Table margin
        const x = 20; // Left padding
        let y = 25; // Starting position for table content below header
        const labelCellWidth = 60; // Width of label cell
        const valueCellWidth = 100; // Width of value cell
        const lineHeight = 8; // Height of each text line

        // Loop through fields to create rows in the table with precise spacing for text
        fields.forEach((field) => {
            const value = encodeURIComponent(field.value); // Escape special characters
            const splitText = doc.splitTextToSize(decodeURIComponent(value), valueCellWidth - 4);
            const rowHeight = lineHeight * splitText.length;

            // Draw cell for label
            doc.setTextColor(0, 0, 0); // Black text color
            doc.rect(x, y, labelCellWidth, rowHeight, 'S'); // Label cell border
            doc.text(field.label + ":", x + 2, y + lineHeight); // Label text

            // Draw cell for value with adjusted height and wrapped text
            doc.rect(x + labelCellWidth, y, valueCellWidth, rowHeight, 'S'); // Value cell border
            doc.text(splitText, x + labelCellWidth + 2, y + lineHeight); // Display wrapped text

            // Move to the next row position
            y += rowHeight;
        });

        // Save the PDF with the request number as the filename
        doc.save(`${requestNumber}-Barangay-Clearance-Request.pdf`);
    }
</script>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>