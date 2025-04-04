<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_set_cookie_params(0);
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../index.html");
    exit();
}

// Include the database configuration file
require_once 'config.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$currentUsername = $_SESSION['username'];
$stmt = $conn->prepare("SELECT id, api_id, username, firstname, middlename, lastname, email, areaofcencusstreet, password_hash FROM census_credentials WHERE username = ?");
$stmt->bind_param("s", $currentUsername);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $id = htmlspecialchars($row['id']);
    $api_id = htmlspecialchars($row['api_id']);
    $username = htmlspecialchars($row['username']);
    $firstname = htmlspecialchars($row['firstname']);
    $middlename = htmlspecialchars($row['middlename']);
    $lastname = htmlspecialchars($row['lastname']);
    $email = htmlspecialchars($row['email']);
    $areaofcencusstreet = htmlspecialchars($row['areaofcencusstreet']);
    $password_hash = $row['password_hash']; 
} else {
    header("Location: ../index.html");
    exit();
}

$stmt->close();

$errorMessage = ""; 
$successMessage = ""; 

if (isset($_GET['error'])) {
    $errorMessage = htmlspecialchars($_GET['error']);
} elseif (isset($_GET['success'])) {
    $successMessage = "Profile updated successfully!";
}

$conn->close();
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
    <title>User Profile Edit</title>
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
            <a href="User.php" class="active">
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
    <!--Sidebar end-->

    <!--Main content per page-->
    <div class="main--content">
        <h2>Edit Profile</h2>

        <!-- Display error message if there is one -->
        <?php if (!empty($errorMessage)): ?>
            <div class="error-message"><?php echo $errorMessage; ?></div>
        <?php endif; ?>

        <!-- Display success message if profile was updated -->
        <?php if (!empty($successMessage)): ?>
            <div class="success-message"><?php echo $successMessage; ?></div>
        <?php endif; ?>

        <!-- User Profile Form -->
        <form action="php/user_update_profile.php" method="POST">
            <div class="profile-section">
                <label for="id">ID:</label>
                <input type="text" id="id" name="id" value="<?php echo $id; ?>" readonly required>
            </div>

            <div class="profile-section">
                <label for="api_id">API ID:</label>
                <input type="text" id="api_id" name="api_id" value="<?php echo $api_id; ?>" readonly required>
            </div>

            <div class="profile-section">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo $username; ?>" readonly required>
            </div>

            <div class="profile-section">
                <label for="firstname">First Name:</label>
                <input type="text" id="firstname" name="firstname" value="<?php echo $firstname; ?>" required>
            </div>

            <div class="profile-section">
                <label for="middlename">Middle Name:</label>
                <input type="text" id="middlename" name="middlename" value="<?php echo $middlename; ?>" required>
            </div>

            <div class="profile-section">
                <label for="lastname">Last Name:</label>
                <input type="text" id="lastname" name="lastname" value="<?php echo $lastname; ?>" required>
            </div>

            <div class="profile-section">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo $email; ?>" required>
            </div>

            <div class="profile-section">
                <label for="areaofcencusstreet">Area of Census Street:</label>
                <input type="text" id="areaofcencusstreet" name="areaofcencusstreet" value="<?php echo $areaofcencusstreet; ?>" required>
            </div>

            <div class="profile-section password-container">
                <label for="current_password">Current Password:</label>
                <input type="password" id="current_password" name="current_password" required>
                <button type="button" class="btn-show" onclick="togglePasswordVisibility('current_password')">Show</button>
            </div>

            <div class="profile-section password-container">
                <label for="new_password">New Password:</label>
                <input type="password" id="new_password" name="new_password" 
                    pattern="(?=.*\d).{8,}" 
                    title="Password must be at least 8 characters long and include at least 1 number">
<<<<<<< HEAD
=======
                <input type="password" id="new_password" name="new_password" 
                    pattern="(?=.*\d).{8,}" 
                    title="Password must be at least 8 characters long and include at least 1 number">
>>>>>>> 9cdd39e2d17f7ba465f19bbdd19dba7ab44c0de5
                <button type="button" class="btn-show" onclick="togglePasswordVisibility('new_password')">Show</button>
            </div>

            <div class="formbuttons">
                <div class="updatebutton">
                    <button type="submit">Update Profile</button>
                </div>
            </div>
        </form>
<<<<<<< HEAD
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
=======
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
>>>>>>> 9cdd39e2d17f7ba465f19bbdd19dba7ab44c0de5

    <!-- Incorrect Password Popup -->
    <div class="passwordpopup" id="passwordpopup" style="display: <?php echo !empty($errorMessage) ? 'block' : 'none'; ?>;">
        <div class="popup-content">
            <span class="popup-close" onclick="this.parentElement.parentElement.style.display='none'">&times;</span>
            <img src="../images/error.png" alt="Error Icon" class="popup-icon">
            <p><?php echo $errorMessage; ?></p>
        </div>
    </div>

    <!-- Profile Updated Popup -->
    <div class="successpopup" id="successpopup" style="display: <?php echo !empty($successMessage) ? 'block' : 'none'; ?>;">
        <div class="popup-content">
            <span class="popup-close" onclick="this.parentElement.parentElement.style.display='none'">&times;</span>
            <img src="../images/success.png" alt="Success Icon" class="popup-icon">
            <p><?php echo $successMessage; ?></p>
        </div>
    </div>
</div>

<script>
function togglePasswordVisibility(fieldId, btn) {
    const passwordField = document.getElementById(fieldId);
    if (passwordField.type === "password") {
        passwordField.type = "text";
        btn.textContent = "Hide";
    } else {
        passwordField.type = "password";
        btn.textContent = "Show";
    }
}
</script>
<<<<<<< HEAD
<script src="../scriptv4.js"></script>
=======
<script src="../script.js"></script>
>>>>>>> 9cdd39e2d17f7ba465f19bbdd19dba7ab44c0de5
<script src="../sidebar.js"></script>
<script sr="../password.js"></script>
</body>
</html>
