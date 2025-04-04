<?php
session_start();

// Store form data in SESSION so we can retrieve it after payment
$_SESSION['registration_data'] = $_POST;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Page</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
            margin: 0;
        }
        .container {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
            max-width: 400px;
            width: 100%;
        }
        h2 {
            margin-bottom: 10px;
            color: #333;
        }
        img {
            width: 250px;
            border-radius: 5px;
            margin: 10px 0;
        }
        label {
            font-weight: bold;
            display: block;
            margin: 10px 0 5px;
        }
        input[type="text"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }
        input[type="submit"] {
            background: #007bff;
            color: #fff;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            border-radius: 5px;
            font-size: 16px;
            width: 100%;
        }
        input[type="submit"]:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Complete Your Payment</h2>
        <p>Amount: <strong>₱20</strong></p>

        <!-- GCash Payment (QR Code) -->
        <h3>Pay with GCash</h3>
        <img src="../images/qr.jpg" alt="GCash QR Code">

        <p>After payment, enter your transaction number and click "I Have Paid."</p>

        <form action="process_payment.php" method="POST" onsubmit="return validateTransactionId()">
            <label for="transaction_id">Transaction Number:</label>
            <input 
                type="text" 
                id="transaction_id" 
                name="transaction_id" 
                required
                oninput="this.value = this.value.replace(/[^a-zA-Z0-9]/g, '')"
                placeholder="Transaction ID: i.e. ASD123123"
                pattern="[a-zA-Z0-9]+"
                title="Only letters and numbers are allowed"
            >
            <input type="submit" name="confirmPayment" value="I Have Paid">
        </form>
    </div>

    <script>
        // Client-side validation before form submission
        function validateTransactionId() {
            const transactionId = document.getElementById('transaction_id').value;
            if (!/^[a-zA-Z0-9]+$/.test(transactionId)) {
                alert("Transaction number can only contain letters and numbers.");
                return false;
            }
            return true;
        }
    </script>
</body>
</html>
