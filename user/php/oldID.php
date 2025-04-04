<!--<!DOCTYPE html>-->
<!--<html lang="en">-->
<!--<head>-->
<!--    <meta charset="UTF-8">-->
<!--    <meta name="viewport" content="width=device-width, initial-scale=1.0">-->
<!--    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />-->
<!--    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">-->
<!--    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />-->
<!--    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />-->
<!--    <link rel="stylesheet" href="../../style.css">-->
<!--    <link rel="icon" type="image/x-icon" href="../../images/lguicon.png"/>-->
<!--    <title>Barangay Resident Registration</title>-->
<!--    <style>-->
<!--        body {-->
<!--            font-family: Arial, sans-serif;-->
<!--            background-color: #eaeaea;-->
<!--            display: flex;-->
<!--            justify-content: center;-->
<!--            align-items: center;-->
<!--            height: 100vh;-->
<!--        }-->
<!--        .registration-wrapper {-->
<!--            background-color: white;-->
<!--            padding: 25px;-->
<!--            border-radius: 8px;-->
<!--            box-shadow: 0px 0px 8px rgba(0, 0, 0, 0.1);-->
<!--            width: 65%;-->
<!--        }-->
<!--        .registration-title {-->
<!--            text-align: center;-->
<!--            font-weight: bold;-->
<!--            text-transform: uppercase;-->
<!--            font-size: 20px;-->
<!--        }-->
<!--        .form-label {-->
<!--            font-weight: bold;-->
<!--            display: block;-->
<!--            margin-top: 8px;-->
<!--            font-size: 14px;-->
<!--        }-->
<!--        .form-input, .form-select {-->
<!--            width: 100%;-->
<!--            padding: 8px;-->
<!--            margin-top: 4px;-->
<!--            border: 1px solid #ccc;-->
<!--            border-radius: 4px;-->
<!--            font-size: 14px;-->
<!--        }-->
<!--        .submit-btn {-->
<!--            width: 100%;-->
<!--            padding: 10px;-->
<!--            background-color: #5a55ff;-->
<!--            color: white;-->
<!--            border: none;-->
<!--            border-radius: 5px;-->
<!--            font-size: 16px;-->
<!--            margin-top: 15px;-->
<!--            cursor: pointer;-->
<!--        }-->
<!--        .submit-btn:hover {-->
<!--            background-color: #4740e0;-->
<!--        }-->
<!--    </style>-->
<!--</head>-->
<!--<body>-->
<!--    <div class="registration-wrapper">-->
<!--        <h1 class="registration-title">Barangay Resident Registration</h1>-->
<!--        <form id="residentRegistrationForm">-->
<!--            <label class="form-label" for="lastName">Last Name</label>-->
<!--            <input class="form-input" type="text" id="lastName" required>-->

<!--            <label class="form-label" for="firstName">First Name</label>-->
<!--            <input class="form-input" type="text" id="firstName" required>-->

<!--            <label class="form-label" for="middleName">Middle Name</label>-->
<!--            <input class="form-input" type="text" id="middleName">-->

<!--            <label class="form-label" for="middleInitial">Middle Initial</label>-->
<!--            <input class="form-input" type="text" id="middleInitial" maxlength="1">-->

<!--            <label class="form-label" for="dob">Date of Birth</label>-->
<!--            <input class="form-input" type="date" id="dob" required>-->

<!--            <label class="form-label" for="maritalStatus">Civil Status</label>-->
<!--            <select class="form-select" id="maritalStatus" required>-->
<!--                <option value="">Select</option>-->
<!--                <option value="Single">Single</option>-->
<!--                <option value="Married">Married</option>-->
<!--                <option value="Widowed">Widowed</option>-->
<!--                <option value="Divorced">Divorced</option>-->
<!--            </select>-->

<!--            <label class="form-label" for="genderType">Gender</label>-->
<!--            <select class="form-select" id="genderType" required>-->
<!--                <option value="">Select</option>-->
<!--                <option value="Male">Male</option>-->
<!--                <option value="Female">Female</option>-->
<!--            </select>-->

<!--            <label class="form-label" for="residenceAddress">Address</label>-->
<!--            <input class="form-input" type="text" id="location" required>-->
<!--            <div id="map" style="height: 300px; width: 100%;"></div> -->

<!--            <button class="submit-btn" type="submit">Register</button>-->
<!--        </form>-->
<!--    </div>-->

<!--    <script>-->
<!--        document.getElementById('residentRegistrationForm').addEventListener('submit', function(event) {-->
<!--            event.preventDefault();-->
            
<!--            const todayDate = new Date();-->
<!--            const expirationDate = new Date();-->
<!--            expirationDate.setFullYear(todayDate.getFullYear() + 5);-->
<!--            const validUntilDate = expirationDate.toISOString().split('T')[0];-->
            
            // Generate unique Resident ID
<!--            let lastResidentID = localStorage.getItem('lastResidentID') || 0;-->
<!--            lastResidentID++;-->
<!--            localStorage.setItem('lastResidentID', lastResidentID);-->
<!--            const residentID = `RES-${String(lastResidentID).padStart(5, '0')}`;-->
            
            // Generate random Precinct Number
<!--            const precinctID = `4-${Math.floor(1000 + Math.random() * 9000)}`;-->
            
<!--            console.log('Resident ID:', residentID);-->
<!--            console.log('Precinct Number:', precinctID);-->
<!--            console.log('Valid Until:', validUntilDate);-->
<!--            alert('Registration Successful!');-->
<!--        });-->
<!--    </script>-->
<!--    <script src="../maps.js"></script> -->
<!--    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>-->
<!--</body>-->
<!--</html>-->
