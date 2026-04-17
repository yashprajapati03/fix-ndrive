<?php
include 'connect.php';
session_start();

$error = "";

if (isset($_POST['login'])) {
    $loginUsername = mysqli_real_escape_string($conn, $_POST['loginUsername']);
    $loginPassword = $_POST['loginPassword'];

    $sql = "SELECT * FROM users WHERE username='$loginUsername' OR email='$loginUsername'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($loginPassword, $user['password'])) {
            $_SESSION['username'] = $user['username'];
            header("Location: index.php");
            exit();
        } else {
            $error = "Incorrect password.";
        }
    } else {
        $error = "User not found.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FixNDrive | Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
    :root {
        --primary: #e74c3c;
        --dark: #2c3e50;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('Logo.png');
        /* Uses your logo as faded bg */
        background-size: cover;
        background-position: center;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
    }

    .login-card {
        background: rgba(255, 255, 255, 0.95);
        padding: 40px;
        border-radius: 15px;
        width: 100%;
        max-width: 400px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        text-align: center;
    }

    .login-card img {
        width: 80px;
        margin-bottom: 10px;
    }

    .login-card h2 {
        color: var(--dark);
        margin-bottom: 30px;
        font-weight: 600;
    }

    .form-group {
        position: relative;
        margin-bottom: 20px;
        text-align: left;
    }

    .form-group i {
        position: absolute;
        left: 15px;
        top: 40px;
        color: #888;
    }

    label {
        font-size: 14px;
        font-weight: 600;
        color: #555;
        display: block;
        margin-bottom: 5px;
    }

    input {
        width: 100%;
        padding: 12px 12px 12px 40px;
        border: 1px solid #ddd;
        border-radius: 8px;
        box-sizing: border-box;
        transition: 0.3s;
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
        transition: 0.3s;
        margin-top: 10px;
    }

    button:hover {
        background: #c0392b;
        transform: translateY(-2px);
    }

    .links {
        margin-top: 20px;
        font-size: 14px;
        display: flex;
        justify-content: space-between;
    }

    .links a {
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
    <div class="login-card">
        <img src="Logo.png" alt="Logo">
        <h2>Welcome Back</h2>

        <?php if($error): ?>
        <div class="error-msg"><?php echo $error; ?></div>
        <?php endif; ?>

        <div class="auth-wrapper">
        <form action="login.php" class="auth-card" method="POST">
            <div class="form-group">
                <label>Username or Email</label>
                <i class="fa fa-user"></i>
                <input type="text" name="loginUsername" placeholder="Enter your username" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <i class="fa fa-lock"></i>
                <input type="password" name="loginPassword" placeholder="••••••••" required>
            </div>
            <button type="submit" name="login">Login to FixNDrive</button>

            <div class="links">
                <a href="forgot_password.php">Forgot Password?</a>
                <a href="register.php">Create Account</a>
            </div>
        </form>
        </div>
    </div>
</body>

</html>