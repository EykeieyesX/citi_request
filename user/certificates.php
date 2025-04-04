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
    <title>Birth Certificate</title>
    <style>
        .form-container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .form-section {
            margin-bottom: 20px;
        }
        .form-section h2 {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .form-row {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 10px;
        }
        .form-row label {
            flex: 1 1 100%;
            font-size: 14px;
            font-weight: bold;
        }
        .form-row input, .form-row select {
            flex: 1 1 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .form-row .half {
            flex: 1 1 calc(50% - 5px);
        }
        .form-row .third {
            flex: 1 1 calc(33.33% - 7px);
        }
        .submit-button {
            text-align: center;
            margin-top: 20px;
        }
        .submit-button button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .submit-button button:hover {
            background-color: #0056b3;
        }
    </style>
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
                <a href="certificates.php" class="active">
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
                <a href="Contact.php" >
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
            <div class="form-container">
                <h1>BIRTH CERTIFICATE REGISTRATION FORM</h1>
                <form id="birthCertificateForm">
                    <div class="form-section">
                        <h2>Child’s Information</h2>
                        <div class="form-row">
                            <label>Firstname</label>
                            <input type="text" name="child_first_name" required>
                        </div>
                        <div class="form-row">
                            <label>Lastname</label>
                            <input type="text" name="child_last_name" required>
                        </div>
                        <div class="form-row">
                            <label>Middlename</label>
                            <input type="text" name="child_middle_name">
                        </div>
                        <div class="form-row">
                            <label>Sex</label>
                            <select name="child_sex" required>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                        </div>
                        <div class="form-row">
                            <label>Date of Birth</label>
                            <input type="date" name="child_date_of_birth" required>
                        </div>
                        <div class="form-row">
                            <label>Time of Birth</label>
                            <input type="time" name="child_time_of_birth">
                        </div>
                        <div class="form-row">
                            <label>Place of Birth</label>
                            <input type="text" name="child_place_of_birth" required>
                        </div>
                        <div class="form-row">
                            <label>Birth Type</label>
                            <select name="child_birth_type" required>
                                <option value="single">Single</option>
                                <option value="twin">Twin</option>
                                <option value="triplet">Triplet</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="form-row">
                            <label>Birth Order</label>
                            <input type="number" name="child_birth_order" min="1" required>
                        </div>
                    </div>

                    <div class="form-section">
                        <h2>Father’s Information</h2>
                        <div class="form-row">
                            <label>Firstname</label>
                            <input type="text" name="father_first_name" required>
                        </div>
                        <div class="form-row">
                            <label>Lastname</label>
                            <input type="text" name="father_last_name" required>
                        </div>
                        <div class="form-row">
                            <label>Middlename</label>
                            <input type="text" name="father_middle_name">
                        </div>
                        <div class="form-row">
                            <label>Suffix</label>
                            <input type="text" name="father_suffix">
                        </div>
                        <div class="form-row">
                            <label>Nationality</label>
                            <input type="text" name="father_nationality" required>
                        </div>
                        <div class="form-row">
                            <label>Date of Birth</label>
                            <input type="date" name="father_date_of_birth" required>
                        </div>
                        <div class="form-row">
                            <label>Place of Birth</label>
                            <input type="text" name="father_place_of_birth" required>
                        </div>
                    </div>

                    <div class="form-section">
                        <h2>Mother’s Information</h2>
                        <div class="form-row">
                            <label>Firstname</label>
                            <input type="text" name="mother_first_name" required>
                        </div>
                        <div class="form-row">
                            <label>Lastname</label>
                            <input type="text" name="mother_last_name" required>
                        </div>
                        <div class="form-row">
                            <label>Middlename</label>
                            <input type="text" name="mother_middle_name">
                        </div>
                        <div class="form-row">
                            <label>Maiden Name</label>
                            <input type="text" name="mother_maiden_name">
                        </div>
                        <div class="form-row">
                            <label>Nationality</label>
                            <input type="text" name="mother_nationality" required>
                        </div>
                        <div class="form-row">
                            <label>Date of Birth</label>
                            <input type="date" name="mother_date_of_birth" required>
                        </div>
                        <div class="form-row">
                            <label>Place of Birth</label>
                            <input type="text" name="mother_place_of_birth" required>
                        </div>
                    </div>

                    <div class="form-section">
                        <h2>Employee Information</h2>
                        <div class="form-row">
                            <label>Select Employee</label>
                            <select name="employee_id" required>
                                <option value="">Select an Employee</option>
                                <!-- Dynamic employee options will be inserted here -->
                            </select>
                        </div>
                    </div>

                    <div class="submit-button">
                        <button type="submit">Submit Registration</button>
                    </div>
                </form>
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
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script>
        function showToast(message, type) {
            Toastify({
                text: message,
                style: {
                    background: type === 'success' ? 'linear-gradient(to right, #00b09b, #96c93d)' : 'linear-gradient(to right, #ff5f6d, #ffc371)'
                },
                duration: 3000
            }).showToast();
        }

        // Handle form submission
        document.getElementById('birthCertificateForm').addEventListener('submit', async function(event) {
            event.preventDefault(); // Prevent default form submission

            // Collect form data
            const formData = new FormData(this);
            const data = Object.fromEntries(formData.entries());

            // Add user ID from session or local storage if needed
            const userId = localStorage.getItem('userId'); // Example: Retrieve user ID
            if (userId) {
                data.user_id = userId; // Add user ID to the data
            }

            // Disable the submit button to prevent multiple submissions
            const submitButton = this.querySelector('button[type="submit"]');
            submitButton.disabled = true;

            try {
                // Send data to the API
                const response = await fetch('https://civilregistrar.lgu2.com/api/birth.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(data),
                });

                const result = await response.json();

                if (response.ok) {
                    showToast('Form submitted successfully!', 'success');
                    this.reset(); // Clear the form
                } else {
                    showToast(result.error || 'An error occurred while submitting the form.', 'error');
                }
            } catch (error) {
                showToast('An error occurred: ' + error.message, 'error');
            } finally {
                submitButton.disabled = false; // Re-enable the submit button
            }
        });
//fetch employee
        fetch("https://civilregistrar.lgu2.com/api/employees.php")
    .then(response => {
        console.log("Response status:", response.status); // Log the status code
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        return response.text(); // Get the response as text
    })
    .then(text => {
        console.log("Raw response:", text); // Log the raw response
        
        // Ensure the JSON is properly formatted
        let fixedJson = text.trim();
        
        // If the response is not already a valid JSON array, attempt to fix it
        if (!fixedJson.startsWith("[")) {
            fixedJson = "[" + fixedJson + "]";
        }
        
        // Replace any misplaced brackets or formatting issues
        fixedJson = fixedJson
            .replace(/\}\{/g, "},{")  // Ensure objects are separated properly
            .replace(/}{/g, "},{");  // Fix edge case for missing commas
        
        console.log("Fixed JSON:", fixedJson);

        // Parse the fixed JSON
        const employees = JSON.parse(fixedJson);
        console.log("Employees:", employees);

        // Populate the dropdown
        const employeeSelect = document.querySelector('select[name="employee_id"]');
        employeeSelect.innerHTML = ""; // Clear existing options

        employees.forEach(employee => {
            const option = document.createElement("option");
            option.value = employee.id;
            option.textContent = employee.name;
            employeeSelect.appendChild(option);
        });
    })
    .catch(error => {
        console.error("Error fetching or parsing employees:", error);
        showToast("Failed to load employee list. Please try again later.", "error");
    });
    </script>
</body>
</html>