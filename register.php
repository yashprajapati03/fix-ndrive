<?php
require_once "connect.php"; // database connection
$error = "";

// Disable MySQL fatal errors (IMPORTANT)
mysqli_report(MYSQLI_REPORT_OFF);

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // ✅ Basic validation
    if ($username === '' || $email === '' || $password === '') {
        echo "<script>alert('All fields are required'); window.history.back();</script>";
        exit;
    }

    // ✅ Password strength (optional but good)
    if (strlen($password) < 6) {
        echo "<script>alert('Password must be at least 6 characters'); window.history.back();</script>";
        exit;
    }

    // ✅ Check if username or email already exists
    $checkSql = "SELECT id FROM users WHERE username = ? OR email = ? LIMIT 1";
    $checkStmt = $conn->prepare($checkSql);

    if (!$checkStmt) {
        echo "<script>alert('Server error. Try again later');</script>";
        exit;
    }

    $checkStmt->bind_param("ss", $username, $email);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        echo "<script>
                alert('Username or Email already exists!');
                window.history.back();
              </script>";
        $checkStmt->close();
        exit;
    }

    $checkStmt->close();

    // ✅ Hash password securely
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // ✅ Insert user (safe)
    $insertSql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $insertStmt = $conn->prepare($insertSql);

    if (!$insertStmt) {
        echo "<script>alert('Server error. Try again later');</script>";
        exit;
    }

    $insertStmt->bind_param("sss", $username, $email, $hashedPassword);

    if ($insertStmt->execute()) {
        echo "<script>
                alert('Registration successful! Please login.');
                window.location.href = 'login.php';
              </script>";
        exit;
    } else {
        echo "<script>alert('Registration failed. Please try again');</script>";
    }

    $insertStmt->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FixNDrive | Create Account</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
    :root {
        --primary: #e74c3c;
        --dark: #2c3e50;
    }

    body {
        font-family: 'Segoe UI', sans-serif;
        background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('Logo.png');
        background-size: cover;
        background-position: center;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        margin: 0;
        padding: 20px 0;
    }

    .register-card {
        background: rgba(255, 255, 255, 0.95);
        padding: 40px;
        border-radius: 15px;
        width: 100%;
        max-width: 450px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        text-align: center;
    }

    .register-card img {
        width: 70px;
        margin-bottom: 10px;
    }

    .register-card h2 {
        color: var(--dark);
        margin-bottom: 10px;
        font-weight: 600;
    }

    .register-card p {
        color: #666;
        font-size: 14px;
        margin-bottom: 25px;
    }

    .form-group {
        position: relative;
        margin-bottom: 15px;
        text-align: left;
    }

    .form-group i {
        position: absolute;
        left: 15px;
        top: 38px;
        color: #888;
    }

    label {
        font-size: 13px;
        font-weight: 600;
        color: #555;
        display: block;
        margin-bottom: 5px;
    }

    input {
        width: 100%;
        padding: 10px 12px 10px 40px;
        border: 1px solid #ddd;
        border-radius: 8px;
        box-sizing: border-box;
        font-size: 14px;
    }

    input:focus {
        border-color: var(--primary);
        outline: none;
        box-shadow: 0 0 5px rgba(231, 76, 60, 0.2);
    }

    button {
        width: 100%;
        padding: 12px;
        background: var(--primary);
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        font-weight: bold;
        cursor: pointer;
        margin-top: 15px;
        transition: 0.3s;
    }

    button:hover {
        background: #c0392b;
        transform: translateY(-2px);
    }

    .login-link {
        margin-top: 20px;
        font-size: 14px;
        color: #555;
    }

    .login-link a {
        color: var(--primary);
        text-decoration: none;
        font-weight: 600;
    }

    .error-msg {
        background: #f8d7da;
        color: #721c24;
        padding: 10px;
        border-radius: 5px;
        margin-bottom: 20px;
        font-size: 14px;
    }
    </style>
</head>

<body>
    <div class="register-card">
        <img src="Logo.png" alt="Logo">
        <h2>Join FixNDrive</h2>
        <p>Create an account to manage your vehicle services</p>

        <?php if(!empty($error)): ?>
        <div class="error-msg"><?php echo $error; ?></div>
        <?php endif; ?>

        <div class="auth-wrapper">
            <form action="register.php" class="auth-card" method="POST">
                <div class="form-group">
                    <label>Username</label>
                    <i class="fa fa-user"></i>
                    <input type="text" name="username" placeholder="Username" required>
                </div>

                <div class="form-group">
                    <label>Email Address</label>
                    <i class="fa fa-envelope"></i>
                    <input type="email" name="email" placeholder="abc@example.com" required>
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <i class="fa fa-lock"></i>
                    <input type="password" name="password" placeholder="Min. 6 characters" required minlength="6">
                </div>

                <div class="form-group">
                    <label>Confirm Password</label>
                    <i class="fa fa-shield-alt"></i>
                    <input type="password" name="confirmPassword" placeholder="Repeat your password" required>
                </div>

                <button type="submit" name="register">Create Account</button>

                <div class="login-link">
                    Already have an account? <a href="login.php">Login here</a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>