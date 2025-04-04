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
$conn = new mysqli("localhost", "root", "", "lgutestdb");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// API URL
$api_url = "https://backend-api-5m5k.onrender.com/api/cencus";

// Fetch data from API
$response = file_get_contents($api_url);

// Check if the response is empty
if (!$response) {
    die("<p>Error: Unable to fetch data from API.</p>");
}

// Decode JSON response
$data = json_decode($response, true);

// Check if decoding was successful
if ($data === null || !isset($data['data'])) {
    die("<p>Error: Invalid JSON response.</p><pre>" . htmlspecialchars($response) . "</pre>");
}

// Extract the actual census data
$censusData = $data['data'];

// Function to normalize area names
function normalizeAreaName($area) {
    $area = strtolower(trim($area));
    // Combine variations of old capitol
    if (strpos($area, 'old capitol') !== false || strpos($area, 'barangay old capitol') !== false) {
        return 'Barangay Old Capitol'; // Standardize on this version
    }
    return ucwords($area); // Return original with proper capitalization
}

// Get unique areas for filtering (with normalization)
$areas = array();
$areaMap = array();

foreach ($censusData as &$row) {
    $normalizedArea = normalizeAreaName($row['areaofcencusstreet']);
    if (!isset($areaMap[$normalizedArea])) {
        $areaMap[$normalizedArea] = $normalizedArea;
        $areas[] = $normalizedArea;
    }
    // Update the area in the data to the normalized version
    $row['areaofcencusstreet'] = $normalizedArea;
}

// Sort areas alphabetically
sort($areas);
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
    <style>
        .button-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 15px;
        }
        .area-button {
            background: #eef2ff;
            border: none;
            padding: 15px 20px;
            border-radius: 12px;
            font-size: 16px;
            cursor: pointer;
            box-shadow: 2px 4px 10px rgba(0, 0, 0, 0.1);
            transition: 0.3s;
        }
        .area-button:hover {
            background: #d1d5ff;
        }
        .back-button {
            display: none;
            margin-top: 20px;
            padding: 10px 20px;
            background: #ffcccc;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }
        
        /* Table container with scrolling */
        .table-container {
            width: 100%;
            overflow-x: auto;
            margin-top: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            display: none;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: auto;
        }
        
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            min-width: 100px;
        }
        
        th {
            background-color: #f4f4f4;
            position: sticky;
            top: 0;
        }
        
        .table-container::-webkit-scrollbar {
            width: 12px;
            height: 12px;
        }
        
        .table-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 6px;
        }
        
        .table-container::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 6px;
        }
        
        .table-container::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
        
        .table-container {
            scrollbar-width: thin;
            scrollbar-color: #888 #f1f1f1;
        }
        
        tbody tr:nth-child(even) {
            background-color: #f8f9fc;
        }
        
        tbody tr:hover {
            background-color: #eaecf4;
        }
    </style>
    <script>
        function filterData(area) {
            document.getElementById("areaButtons").style.display = "none";
            document.getElementById("backButton").style.display = "block";
            document.querySelectorAll(".census-row").forEach(row => {
                const rowArea = row.getAttribute("data-area").toLowerCase();
                row.style.display = rowArea === area.toLowerCase() ? "table-row" : "none";
            });
            document.querySelector(".table-container").style.display = "block";
        }
        function showButtons() {
            document.getElementById("areaButtons").style.display = "flex";
            document.getElementById("backButton").style.display = "none";
            document.querySelector(".table-container").style.display = "none";
        }
    </script>
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
            <a href="AddUsers.php">
                <span class="material-symbols-outlined">add_box</span>         
                <h3>Add Users</h3>
            </a>
            <a href="censusdata.php" class="active">
                <span class="material-symbols-outlined">shield_person</span>         
                <h3>Census</h3>
            </a>            
        </div>
    </aside>
    <!-- Sidebar end -->

    <!-- Main content per page -->
    <div class="main--content">
        <h3>Select Area of Census</h3>
        <!-- Census data/function-->
        <div id="areaButtons" class="button-container">
            <?php foreach ($areas as $area): ?>
                <button class="area-button" onclick="filterData('<?php echo htmlspecialchars($area); ?>')">
                    <?php echo htmlspecialchars($area); ?>
                </button>
            <?php endforeach; ?>
        </div>
        <button id="backButton" class="back-button" onclick="showButtons()">Back to Areas</button>
        
        <?php if (!empty($censusData) && is_array($censusData)): ?>
            <div class="table-container">
                <table id="censusTable">
                    <thead>
                        <tr>
                            <?php 
                            if (!empty($censusData)) {
                                foreach (array_keys($censusData[0]) as $key) {
                                    echo "<th>" . htmlspecialchars($key) . "</th>";
                                }
                            }
                            ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($censusData as $row): ?>
                            <tr class="census-row" data-area="<?php echo htmlspecialchars($row['areaofcencusstreet']); ?>">
                                <?php foreach ($row as $key => $value): ?>
                                    <td>
                                        <?php 
                                        if (is_array($value)) {
                                            if ($key === 'householdMembers' && !empty($value)) {
                                                echo '<ul>';
                                                foreach ($value as $member) {
                                                    echo '<li>' . htmlspecialchars($member['firstname'] . ' ' . $member['lastname'] . ' (' . $member['relationship'] . ')') . '</li>';
                                                }
                                                echo '</ul>';
                                            } else {
                                                echo htmlspecialchars(json_encode($value, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
                                            }
                                        } else {
                                            echo htmlspecialchars($value);
                                        }
                                        ?>
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p>No data available or failed to fetch data.</p>
        <?php endif; ?>
    </div>
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