<?php
session_start();
<<<<<<< HEAD
if (!isset($_SESSION['username'])) {
=======
if (!isset($_SESSION['email'])) {
>>>>>>> 9cdd39e2d17f7ba465f19bbdd19dba7ab44c0de5
    header("Location: ../index.html");
    exit();
}

// Include the database configuration file
require_once 'config.php';

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username = $_SESSION['username'];

// Get search terms if they exist
$id_search = isset($_GET['id_search']) ? $_GET['id_search'] : '';
$clearance_search = isset($_GET['clearance_search']) ? $_GET['clearance_search'] : '';

// Fetch data from barangay_id table with optional search
$sql_id = "SELECT request_number, dateofbirth, civilstatus, gender, location, validuntil, transaction_id, status FROM barangay_id WHERE username = ?";
$params_id = array($username);
$types_id = "s";

// Add search conditions if search term is provided
if (!empty($id_search)) {
    $sql_id .= " AND (request_number LIKE ? OR transaction_id LIKE ? OR status LIKE ?)";
    $search_term = "%" . $id_search . "%";
    $params_id = array_merge($params_id, array($search_term, $search_term, $search_term));
    $types_id .= "sss";
}

$stmt_id = $conn->prepare($sql_id);
if ($params_id) {
    $stmt_id->bind_param($types_id, ...$params_id);
}
$stmt_id->execute();
$result_id = $stmt_id->get_result();
$barangay_id_data = $result_id->fetch_all(MYSQLI_ASSOC);

// Fetch data from barangay_clearance table with optional search
$sql_clearance = "SELECT crequest_number, firstname, lastname, middleinitial, age, ordinalnumber, transaction_id, status FROM barangay_clearance WHERE username = ?";
$params_clearance = array($username);
$types_clearance = "s";

// Add search conditions if search term is provided
if (!empty($clearance_search)) {
    $sql_clearance .= " AND (crequest_number LIKE ? OR firstname LIKE ? OR lastname LIKE ? OR transaction_id LIKE ? OR status LIKE ?)";
    $search_term = "%" . $clearance_search . "%";
    $params_clearance = array_merge($params_clearance, array($search_term, $search_term, $search_term, $search_term, $search_term));
    $types_clearance .= "sssss";
}

$stmt_clearance = $conn->prepare($sql_clearance);
if ($params_clearance) {
    $stmt_clearance->bind_param($types_clearance, ...$params_clearance);
}
$stmt_clearance->execute();
$result_clearance = $stmt_clearance->get_result();
$barangay_clearance_data = $result_clearance->fetch_all(MYSQLI_ASSOC);
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
    <title>Track Requests</title>
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
        .ready-for-pickup { 
            background-image: linear-gradient(to right, blue, purple);
            color: white; 
            padding: 0.2rem; 
            border-radius: 4px; 
        }
        
        /* Scrollable table container */
        .table-container {
            width: 100%;
            overflow-x: auto;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        #tracking-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        #tracking-table th, #tracking-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            white-space: nowrap;
        }
        
        #tracking-table th {
            background-color: #f8f9fa;
            font-weight: 600;
            position: sticky;
            top: 0;
        }
        
        #tracking-table tr:hover {
            background-color: #f5f5f5;
        }
        
        /* Search form styles */
        .search-form {
            margin-bottom: 15px;
            display: flex;
            gap: 10px;
        }
        
        .search-form input[type="text"] {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            flex-grow: 1;
        }
        
        .search-form button {
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            background-color: #007bff;
            color: white;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .search-form button.resetbtn {
            background-color: #6c757d;
        }
        
        .search-form button:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <div class="container">
        <aside id="sidebar">
            <div class="sidebar">
<<<<<<< HEAD
                <a href="Home.php"><span class="material-icons-sharp">home</span><h3>Home</h3></a>
                <a href="User.php"><span class="material-icons-sharp">person_outline</span><h3>User</h3></a>
                <a href="Announcement.php"><span class="material-icons-sharp">campaign</span><h3>Announcement</h3></a>
                <a href="certificates.php"><span class="material-symbols-outlined">rate_review</span><h3>Certificates</h3></a>
                <a href="Request.php"><span class="material-symbols-outlined">rate_review</span><h3>Request</h3></a>
                <a href="track.php" class="active"><span class="material-symbols-outlined">query_stats</span><h3>Track</h3></a>
                <a href="Contact.php"><span class="material-symbols-outlined">call</span><h3>Contact Us</h3></a>
                <a href="About.php"><span class="material-symbols-outlined">info</span><h3>About Us</h3></a>
=======
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
                <a href="Submit.php">
                    <span class="material-symbols-outlined">rate_review</span>
                    <h3>Submit a Request or Feedback</h3>
                </a>
                <a href="track.php" class="active">
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
>>>>>>> 9cdd39e2d17f7ba465f19bbdd19dba7ab44c0de5
            </div>
        </aside>

        <div class="main--content">
            <h1>Track your Requests</h1>
            
<<<<<<< HEAD
            <h3>Barangay ID Requests</h3>
            <form method="GET" action="" class="search-form">
                <input type="text" name="id_search" placeholder="Search by Request Number, Transaction ID, or Status" value="<?php echo htmlspecialchars($id_search); ?>">
                <button class="searchbtn" type="submit">
                    <span class="material-symbols-outlined">search</span>
                </button>
                <button class="resetbtn" type="reset" onclick="window.location.href='?clearance_search=<?php echo urlencode($clearance_search); ?>';">
                    <span class="material-symbols-outlined">restart_alt</span>
                </button>
            </form>
            <div class="table-container" id="id">
                <table id="tracking-table">
                    <thead>
                        <tr>
                            <th>Request ID</th>
                            <th>Birthdate</th>
                            <th>Civil Status</th>
                            <th>Gender</th>
                            <th>Valid Until</th>
                            <th>Transaction ID</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($barangay_id_data) > 0): ?>
                            <?php foreach ($barangay_id_data as $row): ?>
                            <tr>
                                <td>
                                    <a href="php/view_id.php?request_number=<?php echo urlencode($row['request_number']); ?>">
                                        <?php echo htmlspecialchars($row['request_number']); ?>
                                    </a>
                                </td>
                                <td><?php echo date("F j, Y", strtotime($row['dateofbirth'])); ?></td>
                                <td><?php echo htmlspecialchars($row['civilstatus']); ?></td>
                                <td><?php echo htmlspecialchars($row['gender']); ?></td>
                                <td><?php echo date("F j, Y", strtotime($row['validuntil'])); ?></td>
                                <td><?php echo htmlspecialchars($row['transaction_id']); ?></td>
                                <td><span class="<?php echo strtolower(str_replace(' ', '-', $row['status'])); ?>"><?php echo htmlspecialchars($row['status']); ?></span></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8">No barangay ID requests found<?php echo !empty($id_search) ? ' matching your search' : ''; ?>.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <h3>Barangay Clearance Requests</h3>
            <form method="GET" action="" class="search-form">
                <input type="text" name="clearance_search" placeholder="Search by Request Number, Name, Transaction ID, or Status" value="<?php echo htmlspecialchars($clearance_search); ?>">
                <button class="searchbtn" type="submit">
                    <span class="material-symbols-outlined">search</span>
                </button>
                <button class="resetbtn" type="reset" onclick="window.location.href='?id_search=<?php echo urlencode($id_search); ?>';">
                    <span class="material-symbols-outlined">restart_alt</span>
                </button>
            </form>
            <div class="table-container" id="clearance">
                <table id="tracking-table">
                    <thead>
                        <tr>
                            <th>Request ID</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Middle Initial</th>
                            <th>Age</th>
                            <th>Ordinal Number</th>
                            <th>Transaction ID</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($barangay_clearance_data) > 0): ?>
                            <?php foreach ($barangay_clearance_data as $row): ?>
                            <tr>
                                <td>
                                    <a href="php/view_clearance.php?crequest_number=<?php echo urlencode($row['crequest_number']); ?>">
                                        <?php echo htmlspecialchars($row['crequest_number']); ?>
                                    </a>
                                </td>
                                <td><?php echo htmlspecialchars($row['firstname']); ?></td>
                                <td><?php echo htmlspecialchars($row['lastname']); ?></td>
                                <td><?php echo htmlspecialchars($row['middleinitial']); ?></td>
                                <td><?php echo htmlspecialchars($row['age']); ?></td>
                                <td><?php echo htmlspecialchars($row['ordinalnumber']); ?></td>
                                <td><?php echo htmlspecialchars($row['transaction_id']); ?></td>
                                <td><span class="<?php echo strtolower(str_replace(' ', '-', $row['status'])); ?>"><?php echo htmlspecialchars($row['status']); ?></span></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8">No barangay clearance requests found<?php echo !empty($clearance_search) ? ' matching your search' : ''; ?>.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <nav class="navigation">
            <div class="left-section">
                <div class="close" id="toggle-btn" tabindex="0" aria-label="Toggle menu">
                    <span class="material-icons-sharp">menu_open</span>
                </div>
                <div class="logo">
                    <img src="../images/crfms.png" alt="LGU Logo">
                </div>
            </div>
            <div class="right-section">
                <button id="theme-toggle" class="btn-theme-toggle" aria-label="Toggle theme">
                    <span class="material-symbols-outlined">light_mode</span>
                </button>
                <button class="btnLogin-popup"><a href="php/logout.php">Logout</a></button>
            </div>
        </nav>
    </div>
    <script src="../scriptv4.js"></script>
=======
            <table id="tracking-table">
                <thead>
                    <tr>
                        <th>Request ID</th>
                        <th>Status</th>
                        <th>Submitted Date</th>
                        <th>Last Updated</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($result as $row): ?>
                    <tr>
                        <td>
                            <!-- Link to user_view_request.php with reference_id as a query parameter -->
                            <a href="php/user_view_request.php?reference_id=<?php echo urlencode($row['reference_id']); ?>">
                                <?php echo htmlspecialchars($row['reference_id']); ?>
                            </a>
                        </td>
                        <td>
                            <div class="status-container">
                                <span class="status <?php echo isset($row['status_class']) ? $row['status_class'] : ''; ?>">
                                    <?php echo htmlspecialchars($row['status']); ?>
                                </span>
                            </div>
                        </td>
                            <td><?php echo date("F j, Y g:i A", strtotime($row['submitted_date'])); ?></td>
                            <td><?php echo date("F j, Y g:i A", strtotime($row['last_updated'])); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
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

    <script src="../script.js"></script>
>>>>>>> 9cdd39e2d17f7ba465f19bbdd19dba7ab44c0de5
    <script src="../sidebar.js"></script>
</body>
</html>

<?php
$stmt_id->close();
$stmt_clearance->close();
$conn->close();
?>