<?php
session_set_cookie_params(0);
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['username'])) {
    header("Location: AdminLogin.html");
    exit();
}

// Database connection
include '../user/config.php';

// Fetch last Citizen username correctly
$query = "SELECT username FROM usercredentials WHERE username LIKE 'Citizen%' ORDER BY CAST(SUBSTRING(username, 8, 5) AS UNSIGNED) DESC LIMIT 1";
$result = $conn->query($query);
$lastCitizenID = "Citizen00000"; // Default if no users exist

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $lastCitizenID = $row['username'];
}

// Extract the number and increment correctly
$lastNumber = intval(substr($lastCitizenID, 7)); // Extract number from "CitizenXXXXX"
$newCitizenID = "Citizen" . str_pad($lastNumber + 1, 5, "0", STR_PAD_LEFT); // Increment and format

$message = ""; // Initialize message variable

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = trim($_POST['firstname']);
    $middlename = trim($_POST['middlename']);
    $lastname = trim($_POST['lastname']);
    $email = trim($_POST['email']);
    $age = intval($_POST['age']);
    $dateofbirth = $_POST['dateofbirth'];
    $gender = $_POST['gender'];
    $educationlevel = $_POST['educationlevel'];
    $maritalstatus = $_POST['maritalstatus'];
    $income = trim($_POST['income']);
    $address = trim($_POST['address']);

    // Auto-generate password
    $password = "Citizenpassword" . substr($lastname, 0, 2);
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Check if email already exists
    $checkQuery = "SELECT * FROM usercredentials WHERE email = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['message'] = "Email already exists!";
    } else {
        // Insert new user with all fields
        $insertQuery = "INSERT INTO usercredentials 
                        (username, firstname, middlename, lastname, email, password, age, dateofbirth, gender, educationlevel, maritalstatus, income, address, reset_token, token_expiry) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NULL, NULL)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("ssssssissssss", 
            $newCitizenID, $firstname, $middlename, $lastname, $email, $hashedPassword,
            $age, $dateofbirth, $gender, $educationlevel, $maritalstatus, $income, $address
        );

        if ($stmt->execute()) {
            $_SESSION['message'] = "User added successfully! The default password is: $password";
            echo "<script>
                alert('User added successfully! The default password is: $password');
                window.location.href = 'AddUsers.php';
            </script>";
            exit();
        } else {
            $_SESSION['message'] = "Error adding user: " . $conn->error;
        }
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <link rel="icon" type="image/x-icon" href="../images/lguicon.png"/>
    <link rel="stylesheet" href="../style.css">
    <title>Admin - Add User</title>
    <style>
        .container {
            top: 100px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        
        .form-container {
            width: 100%;
            max-width: 800px;
            padding: 50px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .form-section {
            margin-bottom: 25px;
        }
        .form-section h2 {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #333;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        .form-row {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 15px;
        }
        .form-row label {
            flex: 1 1 100%;
            font-size: 14px;
            font-weight: bold;
            color: #555;
        }
        .form-row input, 
        .form-row select,
        .form-row textarea {
            flex: 1 1 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }
        .form-row .half {
            flex: 1 1 calc(50% - 8px);
        }
        .form-row .third {
            flex: 1 1 calc(33.33% - 10px);
        }
        .submit-button {
            text-align: center;
            margin-top: 25px;
        }
        .submit-button button {
            padding: 12px 25px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        .submit-button button:hover {
            background-color: #0056b3;
        }
        .required:after {
            content: " *";
            color: red;
        }
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
            <a href="Reviewsubmissions.php">
                <span class="material-symbols-outlined">rate_review</span>
                <h3>Review Request & Feedback</h3>
            </a>
            <a href="Certificates.php">
                <span class="material-symbols-outlined">rate_review</span>         
                <h3>Certificates</h3>
            </a>
            <a href="AddUsers.php" class="active">
                <span class="material-symbols-outlined">add_box</span>         
                <h3>Add Users</h3>
            </a>
        </div>
    </aside>
    <!-- Sidebar end -->
    
    <div class="form-container">
        <h1>Add New Citizen</h1>

        <?php if (isset($_SESSION['message'])): ?>
            <p style="color: <?php echo strpos($_SESSION['message'], 'successfully') !== false ? 'green' : 'red'; ?>;">
                <?php echo $_SESSION['message']; unset($_SESSION['message']); ?>
            </p>
        <?php endif; ?>

        <form action="AddUsers.php" method="POST">
            <div class="form-section">
                <h2>User Information</h2>
                <div class="form-row">
                    <label for="username" class="required">Username (Auto-generated):</label>
                    <input type="text" id="username" name="username" value="<?php echo $newCitizenID; ?>" readonly>
                </div>

                <div class="form-row">
                    <div class="half">
                        <label for="firstname" class="required">First Name:</label>
                        <input type="text" id="firstname" name="firstname" required>
                    </div>
                    <div class="half">
                        <label for="middlename">Middle Name:</label>
                        <input type="text" id="middlename" name="middlename">
                    </div>
                </div>

                <div class="form-row">
                    <label for="lastname" class="required">Last Name:</label>
                    <input type="text" id="lastname" name="lastname" required>
                </div>

                <div class="form-row">
                    <label for="email" class="required">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
            </div>

            <div class="form-section">
                <h2>Personal Details</h2>
                <div class="form-row">
                    <div class="third">
                        <label for="age" class="required">Age:</label>
                        <input type="number" id="age" name="age" min="1" max="120" required>
                    </div>
                    <div class="third">
                        <label for="dateofbirth" class="required">Date of Birth:</label>
                        <input type="date" id="dateofbirth" name="dateofbirth" required>
                    </div>
                    <div class="third">
                        <label for="gender" class="required">Gender:</label>
                        <select id="gender" name="gender" required>
                            <option value="">Select Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="half">
                        <label for="educationlevel" class="required">Education Level:</label>
                        <select id="educationlevel" name="educationlevel" required>
                            <option value="">Select Education Level</option>
                            <option value="Elementary">Elementary</option>
                            <option value="High School">High School</option>
                            <option value="Bachelor's">Bachelor's Degree</option>
                            <option value="Master's">Master's Degree</option>
                            <option value="PhD">PhD</option>
                            <option value="Vocational">Vocational</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="half">
                        <label for="maritalstatus" class="required">Marital Status:</label>
                        <select id="maritalstatus" name="maritalstatus" required>
                            <option value="">Select Marital Status</option>
                            <option value="Single">Single</option>
                            <option value="Married">Married</option>
                            <option value="Divorced">Divorced</option>
                            <option value="Widowed">Widowed</option>
                            <option value="Separated">Separated</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <label for="income" class="required">Yearly Income:</label>
                    <input type="text" id="income" name="income" placeholder="e.g. Php 60,000" required>
                </div>

                <div class="form-row">
                    <label for="address" class="required">Address:</label>
                    <textarea id="address" name="address" rows="3" required></textarea>
                </div>
            </div>

            <div class="submit-button">
                <button type="submit">Add Citizen</button>
            </div>
        </form>
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
<script src="../sidebar.js"></script>
<script>
    // Set max date for date of birth (18 years ago from today)
    document.addEventListener('DOMContentLoaded', function() {
        const today = new Date();
        const maxDate = new Date();
        maxDate.setFullYear(today.getFullYear() - 18);
        
        const dateInput = document.getElementById('dateofbirth');
        dateInput.max = maxDate.toISOString().split('T')[0];
        
        // Calculate age when date of birth changes
        dateInput.addEventListener('change', function() {
            const dob = new Date(this.value);
            const ageDiff = Date.now() - dob.getTime();
            const ageDate = new Date(ageDiff);
            const calculatedAge = Math.abs(ageDate.getUTCFullYear() - 1970);
            
            document.getElementById('age').value = calculatedAge;
        });
        
        // Validate age when manually entered
        document.getElementById('age').addEventListener('change', function() {
            if (this.value < 18) {
                alert('Citizens must be at least 18 years old');
                this.value = '';
            }
        });
    });
</script>
</body>
</html>