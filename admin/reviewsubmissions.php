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

// Include the database connection details
include '../user/config.php';


// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all requests
$sql_requests = "SELECT reference_id, email, topic, status, submitted_date FROM Request";
$result_requests = $conn->query($sql_requests);

// Fetch all feedbacks, including FeedbackID
$sql_feedbacks = "SELECT FeedbackID, email, topic, submitted_date FROM Feedback";
$result_feedbacks = $conn->query($sql_feedbacks);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link rel="stylesheet" href="../style.css">
    <title>Admin Review Submissions</title>
    <style>
        .submitted { 
            background-color: blue; 
            color: white; 
            padding: 0.2rem; 
            border-radius: 4px; 
        }
        .reviewed { 
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
    </style>
</head>
<body>

<div class="container">
    <!-- Side bar -->
    <aside id="sidebar">
        <div class="toggle">
            <div class="logo">
                <img src="../images/crfms.png" alt="Logo">
            </div>
            <div class="close" id="toggle-btn">
                <span class="material-icons-sharp">menu_open</span>
            </div>
        </div>

        <div class="sidebar">
            <a href="AdminDashboard.php">
                <span class="material-symbols-outlined">dashboard</span>
                <h3>Dashboard</h3>
            </a>
            <a href="admin.php">
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
        </div>
    </aside>
    <!-- Sidebar end -->

    <!-- Main content per page -->
    <div class="main--content">
        <h2>Review Submissions from Users</h2>
        <div class="review--submission">
        <h3>Requests</h3>

            <form method="GET" action="">
                <input type="text" name="search" placeholder="Search by Reference ID, Email, Topic, or Status" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
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
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Get the search query from the form
                    $searchQuery = isset($_GET['search']) ? $_GET['search'] : '';

                    // Create a prepared statement for the search
                    $sql_requests = "SELECT reference_id, email, topic, status, submitted_date FROM Request WHERE 
                                    reference_id LIKE ? OR 
                                    email LIKE ? OR 
                                    topic LIKE ? OR 
                                    status LIKE ?";
                    $stmt = $conn->prepare($sql_requests);

                    // Prepare search parameters
                    $searchParam = "%" . $searchQuery . "%";
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
                            echo "<td>" . $submittedDate->format('F / d / Y | h:i A') . "</td>"; // Modified format
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

<div class="review--submission">
    <h3>Feedbacks</h3>
    <form method="GET" action="">
        <input type="text" name="search" placeholder="Search by Feedback ID, Email, or Topic" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
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
                <th>Feedback ID</th>
                <th>Email</th>
                <th>Topic</th>
                <th>Submitted Date</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Get the search query from the form
            $searchQuery = isset($_GET['search']) ? $_GET['search'] : '';

            // Create a prepared statement for the search
            $sql_feedbacks = "SELECT FeedbackID, email, topic, submitted_date FROM Feedback WHERE 
                              FeedbackID LIKE ? OR 
                              email LIKE ? OR 
                              topic LIKE ?";
            $stmt = $conn->prepare($sql_feedbacks);

            // Prepare search parameters
            $searchParam = "%" . $searchQuery . "%";
            $stmt->bind_param("sss", $searchParam, $searchParam, $searchParam);
            $stmt->execute();
            $result_feedbacks = $stmt->get_result();

            if ($result_feedbacks->num_rows > 0) {
                while ($row = $result_feedbacks->fetch_assoc()) {
                    // Format the submitted_date
                    $submittedDate = new DateTime($row['submitted_date']);
                    echo "<tr>";
                    echo "<td><a href='php/view_feedback.php?feedback_id=" . htmlspecialchars($row['FeedbackID']) . "'>" . htmlspecialchars($row['FeedbackID']) . "</a></td>"; // Make Feedback ID clickable
                    echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['topic']) . "</td>";
                    echo "<td>" . $submittedDate->format('F / d / Y | h:i A') . "</td>"; // Modified format
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No feedbacks found.</td></tr>";
            }

            // Close the statement
            $stmt->close();
            ?>
        </tbody>
    </table>
</div>

    <nav class="navigation">
        <button id="theme-toggle" class="btn-theme-toggle">
            <span class="material-symbols-outlined">light_mode</span>
        </button>
        <button class="btnLogin-popup"><a href="php/admin_logout.php">Logout</a></button>
    </nav>
</div>

<script src="../script.js"></script>

</body>
</html>

<?php
$conn->close();
?>
