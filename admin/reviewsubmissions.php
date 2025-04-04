<?php
session_set_cookie_params(0); 
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: AdminLogin.html");
    exit();
}

// Initialize error and success messages
$errorMessage = "";
$successMessage = "";

// Connection
<<<<<<< HEAD
$conn = new mysqli("localhost", "citi_lgutestdb1", "GGpfr89ly9h6qJF7", "citi_lgutestdb");
=======
$conn = new mysqli("localhost", "root", "", "lgutestdb");

>>>>>>> 9cdd39e2d17f7ba465f19bbdd19dba7ab44c0de5

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all requests
$sql_requests = "SELECT reference_id, email, topic, status, submitted_date FROM request";
$result_requests = $conn->query($sql_requests);

// Fetch all feedbacks, including FeedbackID
$sql_feedbacks = "SELECT feedbackid, email, topic, submitted_date FROM feedback";
$result_feedbacks = $conn->query($sql_feedbacks);

// Fetch all certificates
$sql_certificates = "SELECT id, child_first_name, child_middle_name, child_last_name, child_sex, child_date_of_birth, father_first_name, father_last_name, mother_first_name, mother_last_name, status, created_at FROM birth_records";
$result_certificates = $conn->query($sql_certificates);

// Fetch all barangay IDs
$sql_barangay_ids = "SELECT request_number, username, firstname, lastname, status, datesubmitted FROM barangay_id";
$result_barangay_ids = $conn->query($sql_barangay_ids);

// Fetch all barangay clearances
$sql_barangay_clearances = "SELECT crequest_number, username, firstname, lastname, middleinitial, age, ordinalnumber, status, datesubmitted FROM barangay_clearance";
$result_barangay_clearances = $conn->query($sql_barangay_clearances);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link rel="stylesheet" href="../style.css">
<<<<<<< HEAD
    <link rel="stylesheet" href="styles/pending-requests-css.css">
=======
>>>>>>> 9cdd39e2d17f7ba465f19bbdd19dba7ab44c0de5
    <link rel="icon" type="image/x-icon" href="../images/lguicon.png"/>
    <title>Admin Review Submissions</title>
    <style>
        .submitted { 
            background-color: blue; 
            color: white; 
            padding: 0.2rem; 
            border-radius: 4px; 
        }
        .pending { 
            background-color: blue; 
            color: white; 
            padding: 0.2rem; 
            border-radius: 4px; 
        }
        .reviewed  { 
            background-color: orange; 
            color: white; 
            padding: 0.2rem; 
            border-radius: 4px; 
        }
        .in-progress { 
            background-color: yellow; 
            color: black; 
            padding: 0.2rem; 
            border-radius: 4px; 
        }
        .processing { 
            background-color: yellow; 
            color: black; 
            padding: 0.2rem; 
            border-radius: 4px; 
        }
        .cancelled { 
            background-color: red; 
            color: white; 
            padding: 0.2rem; 
            border-radius: 4px; 
        }
        .completed { 
            background-color: green; 
            color: white; 
            padding: 0.2rem; 
            border-radius: 4px; 
        }
        .positive {
            background-color: blue;
            color: white;
            padding: 0.2rem;
            border-radius: 4px;
        }
        .negative{
            background-color: red; 
            color: white; 
            padding: 0.2rem; 
            border-radius: 4px; 
        }
<<<<<<< HEAD
        .ready-for-pickup { 
            background-image: linear-gradient(to right, blue, purple);
            color: white; 
            padding: 0.2rem; 
            border-radius: 4px; 
        }
        
=======
>>>>>>> 9cdd39e2d17f7ba465f19bbdd19dba7ab44c0de5
    </style>
</head>
<body>

<div class="container">
    <!-- Side bar -->
    <aside id="sidebar">
        <div class="sidebar">
            <a href="AdminDashboard.php">
                <span class="material-symbols-outlined">dashboard</span>
                <h3>Dashboard</h3>
            </a>
            <a href="Admin.php">
                <span class="material-symbols-outlined">shield_person</span>
                <h3>Admin</h3>
            </a>
            <a href="AdminAnnouncement.php">
                <span class="material-symbols-outlined">add_box</span>
                <h3>Announcements</h3>
            </a>
            <a href="Reviewsubmissions.php" class="active">
                <span class="material-symbols-outlined">rate_review</span>
                <h3>Review Request & Feedback</h3>
            </a>
            <a href="Certificates.php">
                <span class="material-symbols-outlined">rate_review</span>         
                <h3>Certificates</h3>
            </a>
            <a href="AddUsers.php">
                <span class="material-symbols-outlined">add_box</span>         
                <h3>Add Users</h3>
            </a>
        </div>
    </aside>
    <!-- Sidebar end -->

    <!-- Main content per page -->
    <div class="main--content">
    <h2>Review Submissions from Users</h2>
<<<<<<< HEAD
    
    <!-- Certificates Table (existing) -->
    <div class="review--submission" id="certificate">
        <h3>Certificates</h3>
        <form method="GET" action="">
            <input type="text" name="certificate_search" placeholder="Search by Certificate ID, Child's Name, or Status" value="<?php echo isset($_GET['certificate_search']) ? htmlspecialchars($_GET['certificate_search']) : ''; ?>">
=======
    <div class="review--submission">
        <h3>Requests</h3>

        <form method="GET" action="">
            <input type="text" name="request_search" placeholder="Search by Reference ID, Email, Topic, or Status" value="<?php echo isset($_GET['request_search']) ? htmlspecialchars($_GET['request_search']) : ''; ?>">
>>>>>>> 9cdd39e2d17f7ba465f19bbdd19dba7ab44c0de5
            <button class="searchbtn" type="submit">
                <span class="material-symbols-outlined">search</span>
            </button>
            <button class="resetbtn" type="reset" onclick="window.location.href='?';">
                <span class="material-symbols-outlined">restart_alt</span>
            </button>
        </form>
<<<<<<< HEAD
    
        <table>
            <thead>
                <tr>
                    <th>Certificate ID</th>
                    <th>Child's First Name</th>
                    <th>Child's Middle Name</th>
                    <th>Child's Last Name</th>
                    <th>Sex</th>
                    <th>Date of Birth</th>
                    <th>Father's First Name</th>
                    <th>Father's Last Name</th>
                    <th>Mother's First Name</th>
                    <th>Mother's Last Name</th>
                    <th>Status</th>
                    <th>Created At</th>
=======
        <form method="GET" action="">
            <input type="text" name="request_search" placeholder="Search by Reference ID, Email, Topic, or Status" value="<?php echo isset($_GET['request_search']) ? htmlspecialchars($_GET['request_search']) : ''; ?>">
            <button class="searchbtn" type="submit">
                <span class="material-symbols-outlined">search</span>
            </button>
            <button class="resetbtn" type="reset" onclick="window.location.href='?';">
                <span class="material-symbols-outlined">restart_alt</span>
            </button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>Reference ID</th>
                    <th>Email</th>
                    <th>Topic</th>
                    <th>Status</th>
                    <th>Submitted Date</th>
>>>>>>> 9cdd39e2d17f7ba465f19bbdd19dba7ab44c0de5
                </tr>
            </thead>
            <tbody>
                <?php
<<<<<<< HEAD
                $certificateSearchQuery = isset($_GET['certificate_search']) ? $_GET['certificate_search'] : '';
    
                $sql_certificates = "SELECT id, child_first_name, child_middle_name, child_last_name, child_sex, child_date_of_birth, father_first_name, father_last_name, mother_first_name, mother_last_name, status, created_at FROM birth_records WHERE 
                                      id LIKE ? OR 
                                      child_first_name LIKE ? OR 
                                      child_middle_name LIKE ? OR 
                                      child_last_name LIKE ? OR 
                                      status LIKE ?";
    
                $stmt = $conn->prepare($sql_certificates);
    
                $searchParam = "%" . $certificateSearchQuery . "%";
                $stmt->bind_param("sssss", $searchParam, $searchParam, $searchParam, $searchParam, $searchParam);
                $stmt->execute();
                $result_certificates = $stmt->get_result();
    
                if ($result_certificates->num_rows > 0) {
                    while ($row = $result_certificates->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td><a href='https://citizenrequest.lgu2.com/admin/php/view_birth.php?birth_id=" . urlencode($row['id']) . "'>" . htmlspecialchars($row['id']) . "</a></td>";
                        echo "<td>" . htmlspecialchars($row['child_first_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['child_middle_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['child_last_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['child_sex']) . "</td>";
                        echo "<td>" . date('F d, Y', strtotime($row['child_date_of_birth'])) . "</td>";
                        echo "<td>" . htmlspecialchars($row['father_first_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['father_last_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['mother_first_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['mother_last_name']) . "</td>";
                        echo "<td><span class='" . strtolower($row['status']) . "'>" . htmlspecialchars($row['status']) . "</span></td>";
                        echo "<td>" . date('F d, Y | h:i A', strtotime($row['created_at'])) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='12'>No certificates found.</td></tr>";
                }
    
                $stmt->close();
                ?>
            </tbody>
        </table>
    </div>

    <!-- Barangay ID Table (existing) -->
    <div class="review--submission" id="barangay-id">
        <h3>Barangay ID Applications</h3>
        
        <form method="GET" action="">
            <input type="text" name="barangay_search" placeholder="Search by Request Number, Username, or Name" value="<?php echo isset($_GET['barangay_search']) ? htmlspecialchars($_GET['barangay_search']) : ''; ?>">
=======
                // Get the search query from the request form
                $requestSearchQuery = isset($_GET['request_search']) ? $_GET['request_search'] : '';
        <table>
            <thead>
                <tr>
                    <th>Reference ID</th>
                    <th>Email</th>
                    <th>Topic</th>
                    <th>Status</th>
                    <th>Submitted Date</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Get the search query from the request form
                $requestSearchQuery = isset($_GET['request_search']) ? $_GET['request_search'] : '';

                // Create a prepared statement for the search
                $sql_requests = "SELECT reference_id, email, topic, status, submitted_date FROM request WHERE 
                                reference_id LIKE ? OR 
                                email LIKE ? OR 
                                topic LIKE ? OR 
                                status LIKE ?";
                $stmt = $conn->prepare($sql_requests);
                // Create a prepared statement for the search
                $sql_requests = "SELECT reference_id, email, topic, status, submitted_date FROM request WHERE 
                                reference_id LIKE ? OR 
                                email LIKE ? OR 
                                topic LIKE ? OR 
                                status LIKE ?";
                $stmt = $conn->prepare($sql_requests);

                // Prepare search parameters
                $searchParam = "%" . $requestSearchQuery . "%";
                $stmt->bind_param("ssss", $searchParam, $searchParam, $searchParam, $searchParam);
                $stmt->execute();
                $result_requests = $stmt->get_result();
                // Prepare search parameters
                $searchParam = "%" . $requestSearchQuery . "%";
                $stmt->bind_param("ssss", $searchParam, $searchParam, $searchParam, $searchParam);
                $stmt->execute();
                $result_requests = $stmt->get_result();

                if ($result_requests->num_rows > 0) {
                    while ($row = $result_requests->fetch_assoc()) {
                        // Format the submitted_date
                        $submittedDate = new DateTime($row['submitted_date']);
                        echo "<tr>";
                        echo "<td><a href='php/view_request.php?reference_id=" . $row['reference_id'] . "'>" . $row['reference_id'] . "</a></td>";
                        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['topic']) . "</td>";
                        echo "<td><span class='" . strtolower($row['status']) . "'>" . htmlspecialchars($row['status']) . "</span></td>";
                        echo "<td>" . $submittedDate->format('F d, Y | h:i A') . "</td>"; // Modified format
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No requests found.</td></tr>";
                }

                // Close the statement
                $stmt->close();
                ?>
            </tbody>
        </table>
    </div>
                // Close the statement
                $stmt->close();
                ?>
            </tbody>
        </table>
    </div>

    <div class="review--submission">
        <h3>Feedbacks</h3>
        <form method="GET" action="">
            <input type="text" name="feedback_search" placeholder="Search by Feedback ID, Email, or Topic" value="<?php echo isset($_GET['feedback_search']) ? htmlspecialchars($_GET['feedback_search']) : ''; ?>">
>>>>>>> 9cdd39e2d17f7ba465f19bbdd19dba7ab44c0de5
            <button class="searchbtn" type="submit">
                <span class="material-symbols-outlined">search</span>
            </button>
            <button class="resetbtn" type="reset" onclick="window.location.href='?';">
                <span class="material-symbols-outlined">restart_alt</span>
            </button>
        </form>
<<<<<<< HEAD
        
        <table>
            <thead>
                <tr>
                    <th>Request Number</th>
                    <th>Username</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Status</th>
                    <th>Date Submitted</th>
=======
        <table>
            <thead>
                <tr>
                    <th>Feedback ID</th>
                    <th>Email</th>
                    <th>Topic</th>
                    <th>Submitted Date</th>
                    <th>Analysis</th>

>>>>>>> 9cdd39e2d17f7ba465f19bbdd19dba7ab44c0de5
                </tr>
            </thead>
            <tbody>
                <?php
<<<<<<< HEAD
                $barangaySearchQuery = isset($_GET['barangay_search']) ? $_GET['barangay_search'] : '';
                
                $sql_barangay_ids = "SELECT request_number, username, firstname, lastname, status, datesubmitted FROM barangay_id WHERE 
                                    request_number LIKE ? OR 
                                    username LIKE ? OR 
                                    firstname LIKE ? OR 
                                    lastname LIKE ? OR 
                                    status LIKE ?";
                $stmt = $conn->prepare($sql_barangay_ids);
                
                $searchParam = "%" . $barangaySearchQuery . "%";
                $stmt->bind_param("sssss", $searchParam, $searchParam, $searchParam, $searchParam, $searchParam);
                $stmt->execute();
                $result_barangay_ids = $stmt->get_result();
                
                if ($result_barangay_ids->num_rows > 0) {
                    while ($row = $result_barangay_ids->fetch_assoc()) {
                        $dateSubmitted = new DateTime($row['datesubmitted']);
                        echo "<tr>";
                        echo "<td><a href='php/view_id.php?request_number=" . htmlspecialchars($row['request_number']) . "'>" . htmlspecialchars($row['request_number']) . "</a></td>";
                        echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['firstname']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['lastname']) . "</td>";
                        echo "<td><span class='" . strtolower($row['status']) . "'>" . htmlspecialchars($row['status']) . "</span></td>";
                        echo "<td>" . $dateSubmitted->format('F d, Y') . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No barangay ID applications found.</td></tr>";
                }
                
=======
                $feedbackSearchQuery = isset($_GET['feedback_search']) ? $_GET['feedback_search'] : '';

                $sql_feedbacks = "SELECT feedbackid, email, topic, submitted_date FROM feedback WHERE 
                                  feedbackid LIKE ? OR 
                                  email LIKE ? OR 
                                  topic LIKE ?";
                $stmt = $conn->prepare($sql_feedbacks);

                $searchParam = "%" . $feedbackSearchQuery . "%";
                $stmt->bind_param("sss", $searchParam, $searchParam, $searchParam);
                $stmt->execute();
                $result_feedbacks = $stmt->get_result();

                if ($result_feedbacks->num_rows > 0) {
                    while ($row = $result_feedbacks->fetch_assoc()) {
                        // Format the submitted_date
                        $submittedDate = new DateTime($row['submitted_date']);
                        echo "<tr>";
                        echo "<td><a href='php/view_feedback.php?feedback_id=" . htmlspecialchars($row['feedbackid']) . "'>" . htmlspecialchars($row['feedbackid']) . "</a></td>"; 
                        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['topic']) . "</td>";
                        echo "<td>" . $submittedDate->format('F  d, Y | h:i A') . "</td>"; 
                        echo "<td><span class='positive'> Positive</span>||<span class='negative'>Negative</span> </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No feedbacks found.</td></tr>";
                }

                // Close the statement
>>>>>>> 9cdd39e2d17f7ba465f19bbdd19dba7ab44c0de5
                $stmt->close();
                ?>
            </tbody>
        </table>
    </div>
<<<<<<< HEAD
    
    <!-- New Barangay Clearance Table -->
    <div class="review--submission" id="barangay-clearance">
        <h3>Barangay Clearance Applications</h3>
        
        <form method="GET" action="">
            <input type="text" name="clearance_search" placeholder="Search by Request Number, Username, or Name" value="<?php echo isset($_GET['clearance_search']) ? htmlspecialchars($_GET['clearance_search']) : ''; ?>">
            <button class="searchbtn" type="submit">
                <span class="material-symbols-outlined">search</span>
            </button>
            <button class="resetbtn" type="reset" onclick="window.location.href='?';">
                <span class="material-symbols-outlined">restart_alt</span>
            </button>
        </form>
        
        <table>
            <thead>
                <tr>
                    <th>Request Number</th>
                    <th>Username</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Middle Initial</th>
                    <th>Age</th>
                    <th>Ordinal Number</th>
                    <th>Status</th>
                    <th>Date Submitted</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $clearanceSearchQuery = isset($_GET['clearance_search']) ? $_GET['clearance_search'] : '';
                
                $sql_barangay_clearances = "SELECT crequest_number, username, firstname, lastname, middleinitial, age, ordinalnumber, status, datesubmitted FROM barangay_clearance WHERE 
                                          crequest_number LIKE ? OR 
                                          username LIKE ? OR 
                                          firstname LIKE ? OR 
                                          lastname LIKE ? OR 
                                          middleinitial LIKE ? OR 
                                          age LIKE ? OR 
                                          ordinalnumber LIKE ? OR 
                                          status LIKE ?";
                $stmt = $conn->prepare($sql_barangay_clearances);
                
                $searchParam = "%" . $clearanceSearchQuery . "%";
                $stmt->bind_param("ssssssss", $searchParam, $searchParam, $searchParam, $searchParam, $searchParam, $searchParam, $searchParam, $searchParam);
                $stmt->execute();
                $result_barangay_clearances = $stmt->get_result();
                
                if ($result_barangay_clearances->num_rows > 0) {
                    while ($row = $result_barangay_clearances->fetch_assoc()) {
                        $dateSubmitted = new DateTime($row['datesubmitted']);
                        echo "<tr>";
                        echo "<td><a href='php/view_clearance.php?crequest_number=" . htmlspecialchars($row['crequest_number']) . "'>" . htmlspecialchars($row['crequest_number']) . "</a></td>";
                        echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['firstname']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['lastname']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['middleinitial']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['age']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['ordinalnumber']) . "</td>";
                        echo "<td><span class='" . strtolower($row['status']) . "'>" . htmlspecialchars($row['status']) . "</span></td>";
                        echo "<td>" . $dateSubmitted->format('F d, Y') . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='9'>No barangay clearance applications found.</td></tr>";
                }
                
                $stmt->close();
                ?>
            </tbody>
        </table>
    </div>
</div>
</div>

<nav class="navigation">
    <div class="left-section">
        <div class="close" id="toggle-btn" tabindex="0" aria-label="Toggle menu">
            <span class="material-icons-sharp">menu_open</span>
        </div>
        <div class="logo">
            <a href="AdminDashboard.php">
                <img src="../images/crfms.png" alt="LGU Logo">
            </a>
        </div>
    </div>
    <div class="right-section">
        <button id="theme-toggle" class="btn-theme-toggle" aria-label="Toggle theme">
            <span class="material-symbols-outlined">light_mode</span>
        </button>
        <button class="btnLogin-popup"><a href="php/admin_logout.php">Logout</a></button>
    </div>
</nav>

<script src="../scriptv4.js"></script>
=======
</div>
<nav class="navigation">
        <!-- Left section: Close button and Logo -->
        <div class="left-section">
            <div class="close" id="toggle-btn" tabindex="0" aria-label="Toggle menu">
                <span class="material-icons-sharp">menu_open</span>
            </div>
            <div class="logo">
                <a href="AdminDashboard.php">
                    <img src="../images/crfms.png" alt="LGU Logo">
                </a>
            </div>
        </div>
        <!-- Right section: Theme toggle and Sign up button -->
        <div class="right-section">
            <button id="theme-toggle" class="btn-theme-toggle" aria-label="Toggle theme">
                <span class="material-symbols-outlined">light_mode</span>
            </button>
            <button class="btnLogin-popup"><a href="php/admin_logout.php">Logout</a></button>
        </div>
    </nav>
<script src="../script.js"></script>
<script src="../sidebar.js"></script>
>>>>>>> 9cdd39e2d17f7ba465f19bbdd19dba7ab44c0de5
<script src="../sidebar.js"></script>
</body>
</html>

<?php
$conn->close();
?>