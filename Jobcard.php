<?php
session_start();
include 'connect.php'; // Change from db.php to connect.php

// Ensure user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Collect POST data
$customer_name = $_POST['customerName'] ?? '';
$contact_number = $_POST['contactNumber'] ?? '';
$vehicle_make = $_POST['vehicleMake'] ?? '';
$vehicle_model = $_POST['vehicleModel'] ?? '';
$vehicle_year = $_POST['vehicleYear'] ?? '';
$vehicle_number = $_POST['vehicleNumber'] ?? '';
$technician = $_POST['technician'] ?? '';
$services = $_POST['services'] ?? '';
$notes = $_POST['notes'] ?? '';
$date = $_POST['date'] ?? '';
$created_by = $_SESSION['username'];

// Insert into database
$stmt = $conn->prepare("INSERT INTO job_cards 
    (customer_name, contact_number, vehicle_make, vehicle_model, vehicle_year, vehicle_number, technician, services, notes, date, created_by)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssisssss", $customer_name, $contact_number, $vehicle_make, $vehicle_model, $vehicle_year, $vehicle_number, $technician, $services, $notes, $date, $created_by);
$stmt->execute();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Fix'n Drive - Job Card</title>
    <!-- Include your styles as before -->
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');

    body {
        margin: 0;
        padding: 0;
        font-family: 'Poppins', sans-serif;
        background: linear-gradient(135deg, #2c3e50, #34495e);
        color: #2c3e50;
    }

    .container {
        max-width: 600px;
        margin: 40px auto;
        background: #ffffff;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        overflow: hidden;
        padding-bottom: 20px;
    }

    .header {
        background: linear-gradient(to right, #e74c3c, #c0392b);
        color: #fff;
        padding: 20px;
        text-align: center;
    }

    .header h2 {
        margin: 0;
        font-weight: 600;
    }

    .section {
        padding: 25px;
        border-bottom: 1px solid #eee;
    }

    .section:last-child {
        border-bottom: none;
    }

    .label {
        font-weight: 600;
        color: #555;
        margin-bottom: 5px;
    }

    .value {
        font-size: 16px;
        color: #333;
        margin-bottom: 15px;
    }

    .button-wrapper {
        text-align: center;
        margin-top: 20px;
    }

    .btn {
        display: inline-block;
        margin: 10px;
        padding: 10px 25px;
        background: #e74c3c;
        color: white;
        border-radius: 30px;
        text-decoration: none;
        font-weight: bold;
        transition: 0.3s;
        border: none;
        cursor: pointer;
    }

    .btn:hover {
        background: #c0392b;
    }
    </style>
</head>

<body>
    <div class="page-wrapper">
    <div class="container" id="pdfContent">
        <div class="header">
            <h2>🛠️ Job Card</h2>
        </div>

        <div class="section">
            <div class="label">👤 Customer Name:</div>
            <div class="value"><?php echo htmlspecialchars($customer_name); ?></div>

            <div class="label">🚗 Vehicle Number:</div>
            <div class="value">
                <?php echo htmlspecialchars("$vehicle_make $vehicle_model ($vehicle_year) - $vehicle_number"); ?></div>

            <div class="label">🧰 Services Required:</div>
            <div class="value"><?php echo htmlspecialchars($services); ?></div>

            <div class="label">👨‍🔧 Assigned Technician:</div>
            <div class="value"><?php echo htmlspecialchars($technician); ?></div>

            <div class="label">📝 Additional Notes:</div>
            <div class="value"><?php echo htmlspecialchars($notes); ?></div>
        </div>
    </div>

    <div class="button-wrapper">
        <button id="downloadBtn" class="btn">⬇️ Download as PDF</button>
        <a class="btn" href="index.php">← Back to Form</a>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
    document.getElementById("downloadBtn").addEventListener("click", function() {
        html2pdf().set({
            margin: 0.5,
            filename: 'FixnDrive_JobCard.pdf',
            image: {
                type: 'jpeg',
                quality: 0.98
            },
            html2canvas: {
                scale: 2
            },
            jsPDF: {
                unit: 'in',
                format: 'letter',
                orientation: 'portrait'
            }
        }).from(document.getElementById("pdfContent")).save();
    });
    </script>
    </div>
</body>

</html>