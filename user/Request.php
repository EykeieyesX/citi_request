<?php
session_set_cookie_params(0); 
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: ../index.html');
    exit();
}

$username   = $_SESSION['username'];

$conn = new mysqli("localhost", "root", "", "lgutestdb");

// Check connection

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link rel="stylesheet" href="../style.css">
    <link rel="icon" type="image/x-icon" href="../images/lguicon.png"/>
    <title>Choose a Request</title>
</head>
<body>

    <div class="container">
        <!-- Side bar-->
        <aside id="sidebar">
           <div class="sidebar">
                <a href="Home.php">
                    <span class="material-icons-sharp">home</span>
                    <h3>Home</h3>
                </a>
                <a href="User.php">
                    <span class="material-icons-sharp">person_outline</span>
                    <h3>User</h3>
                </a> <!-- Fixed closing tag -->
                <a href="Announcement.php">
                    <span class="material-icons-sharp">campaign</span>
                    <h3>Announcement</h3>
                </a>
                <a href="certificates.php">
                    <span class="material-symbols-outlined">rate_review</span>
                    <h3>Certificates</h3>
                </a>
                <a href="Request.php" class="active">
                    <span class="material-symbols-outlined">rate_review</span>         
                    <h3>Request</h3>
                </a>
                <a href="track.php">
                    <span class="material-symbols-outlined">query_stats</span>
                    <h3>Track</h3>
                </a>
                <a href="Contact.php">
                    <span class="material-symbols-outlined">call</span>
                    <h3>Contact Us</h3>
                </a>
                <a href="About.php">
                    <span class="material-symbols-outlined">info</span>
                    <h3>About Us</h3>
                </a>
            </div>
        </aside>
        <!--Sidebar end-->

        <!--Main content per page-->
        <div class="main--content">
             <h2>Choose a Request</h2>

     <?php
        // Get the 'view' parameter from the URL
        $view = isset($_GET['view']) ? $_GET['view'] : '';
        
       if ($view === 'barangayID') {
            echo '<div style="position: fixed; top: 80px; right: 20px; z-index: 1000;">';
            echo '<button type="button" class="close-view-btn" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #333;" onclick="location.href=\'Request.php\';">✖</button>';
            echo '</div>';
            echo '<div class="certificate-container" style="max-width: 100%; margin: 80px 20px 20px; padding: 20px; background: white; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">';
            include('barangay_id.php');
            echo '</div>';
        } elseif ($view === 'barangayclearance') {
           echo '<div style="position: fixed; top: 80px; right: 20px; z-index: 1000;">';
            echo '<button type="button" class="close-view-btn" style="background: none; border: none; font-size: 24px; cursor: pointer; color: #333;" onclick="location.href=\'Request.php\';">✖</button>';
            echo '</div>';
            echo '<div class="certificate-container" style="max-width: 100%; margin: 80px 20px 20px; padding: 20px; background: white; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">';
            include('barangay_clearance.php');
            echo '</div>';
        } else {
        ?>
            <div class="card-container">
                <!-- Barangay ID -->
                <div class="card" onclick="location.href='Request.php?view=barangayID';">
                    <h3><span class="material-symbols-outlined">badge</span>Barangay ID</h3>
                    <p>Click to request a Barangay ID</p>
                </div>
        
                <!-- Barangay Clearance -->
                <div class="card" onclick="location.href='Request.php?view=barangayclearance';">
                    <h3><span class="material-symbols-outlined">description</span>Barangay Clearance</h3>
                    <p>Click to request a Barangay Clearance</p>
                </div>
            </div>
        <?php
        }
        ?>
    </div>
        </div>
        <nav class="navigation">
            <!-- Left section: Close button and Logo -->
            <div class="left-section">
                <div class="close" id="toggle-btn" tabindex="0" aria-label="Toggle menu">
                    <span class="material-icons-sharp">menu_open</span>
                </div>
                <div class="logo">
                        <img src="../images/crfms.png" alt="LGU Logo">
                </div>
            </div>
            <!-- Right section: Theme toggle and Sign up button -->
            <div class="right-section">
                <button id="theme-toggle" class="btn-theme-toggle" aria-label="Toggle theme">
                    <span class="material-symbols-outlined">light_mode</span>
                </button>
                <button class="btnLogin-popup"><a href="php/logout.php">Logout</a></button>
            </div>
        </nav>
    </div>

    <script src="../scriptv4.js"></script>
    <script src="../sidebar.js"></script>
</body>
</html>
