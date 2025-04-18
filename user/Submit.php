<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: ../index.html");
    exit();
}
$email = $_SESSION['email']; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
    <link rel="stylesheet" href="../style.css">
    <link rel="icon" type="image/x-icon" href="../images/lguicon.png"/>
    <title>Submit Requests or Feedbacks</title>
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
                </a>
                <a href="Announcement.php">
                    <span class="material-icons-sharp">campaign</span>
                    <h3>Announcement</h3>
                </a>
                <a href="certificates.php">
                    <span class="material-symbols-outlined">rate_review</span>
                    <h3>Certificates</h3>
                </a>
                <a href="Request.php">
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
        <!-- Sidebar end-->

        <!-- Main content per page-->
        <div class="main--content">
            <h2>Submit a Request or Feedback</h2>
    
            <form id="request-form" action="php/request_submission.php" method="POST" enctype="multipart/form-data">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required readonly>
                
                <label for="topic">Topic:</label>
                <select id="topic" name="topic" required>
                    <option value="" disabled selected>Select a topic</option>
                    <option value="General Inquiry">General Inquiry</option>
                    <option value="Infrastructure Issues">Infrastructure Issues</option>
                    <option value="Complaint">Complaint</option>
                    <option value="Report">Report</option>
                    <option value="Feedback">Feedback</option>
                </select>
                
                <label for="description">Description:</label>
                <textarea id="description" name="description" rows="4" required></textarea>
                <input type="file" name="images" id="images"> 
                <img id="image-preview" src="" alt="Image Preview" style="display:none;">
                <br>
                <br>
                <label for="location">Location:</label>
                <input type="text" id="location" name="location" required>
                
                <div id="map" style="height: 300px; width: 100%;"></div> 
                
                    <div class="submitbutton">
                        <button type="submit">Submit</button>
                    </div>
                <div id="success-message"></div> 
            </form>
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
    <script src="submit.js"></script> 
    <script src="maps.js"></script> 
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
    <script src="../sidebar.js"></script>
<<<<<<< HEAD
=======
    <script src="../sidebar.js"></script>
>>>>>>> 9cdd39e2d17f7ba465f19bbdd19dba7ab44c0de5
</body>
</html>
