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
$conn = new mysqli("localhost", "citi_lgutestdb1", "GGpfr89ly9h6qJF7", "citi_lgutestdb");

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

// Determine which content to show
$view = isset($_GET['view']) ? $_GET['view'] : 'default';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link rel="stylesheet" href="../style.css">
    <link rel="icon" type="image/x-icon" href="../images/lguicon.png" />
    <title>Certificates</title>
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
            <a href="Reviewsubmissions.php">
                <span class="material-symbols-outlined">rate_review</span>
                <h3>Review Request & Feedback</h3>
            </a>
            <a href="Certificates.php" class="active">
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
        <h2>Certificates</h2>

     <?php
        if ($view === 'birth') {
            echo '<div class="certificate-container">';
            echo '<button type="button" class="close-view-btn" onclick="location.href=\'Certificates.php\';">✖</button>';
            include('php/Certificates/birth.php');
            echo '</div>';
        } elseif ($view === 'marriage') {
            echo '<div class="certificate-container">';
            echo '<button type="button" class="close-view-btn" onclick="location.href=\'Certificates.php\';">✖</button>';
            include('php/Certificates/marriage.php');
            echo '</div>';
        } elseif ($view === 'death') {
            echo '<div class="certificate-container">';
            echo '<button type="button" class="close-view-btn" onclick="location.href=\'Certificates.php\';">✖</button>';
            include('php/Certificates/death.php');
            echo '</div>';
        } else {
        ?>

            <div class="card-container">
                <!-- Birth Certificates Card -->
                <div class="card" onclick="location.href='Certificates.php?view=birth';">
                    <h3><span class="material-symbols-outlined">description</span> Birth Certificates</h3>
                    <p>Click to view Birth Certificates</p>
                </div>

                <!-- Marriage Certificates Card -->
                <div class="card" onclick="location.href='Certificates.php?view=marriage';">
                    <h3><span class="material-symbols-outlined">description</span> Marriage Certificates</h3>
                    <p>Click to view Marriage Certificates</p>
                </div>

                <!-- Death Certificates Card -->
                <div class="card" onclick="location.href='Certificates.php?view=death';">
                    <h3><span class="material-symbols-outlined">description</span> Death Certificates</h3>
                    <p>Click to view Death Certificates</p>
                </div>
            </div>

        <?php
        }
        ?>
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
        <!-- Right section: Theme toggle and Logout button -->
        <div class="right-section">
            <button id="theme-toggle" class="btn-theme-toggle" aria-label="Toggle theme">
                <span class="material-symbols-outlined">light_mode</span>
            </button>
            <button class="btnLogin-popup"><a href="php/admin_logout.php">Logout</a></button>
        </div>
    </nav>

    <script src="../scriptv4.js"></script>
    <script src="../sidebar.js"></script>

</body>

</html>

<?php
$conn->close();
?>
