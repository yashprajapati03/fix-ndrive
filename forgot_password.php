<?php
include 'connect.php';
session_start();

$message = "";
$showResetForm = false;
$user_id = "";

// STEP 1: Find Account
if (isset($_POST['verify_account'])) {
    $identifier = mysqli_real_escape_string($conn, $_POST['identifier']);
    $sql = "SELECT id FROM users WHERE username='$identifier' OR email='$identifier'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $user_id = $user['id'];
        $showResetForm = true; // Switch the UI to the reset form
    } else {
        $message = "<div class='error-msg'>No account found with that username or email.</div>";
    }
}

// STEP 2: Update Password
if (isset($_POST['update_password'])) {
    $new_pass = $_POST['new_password'];
    $conf_pass = $_POST['conf_password'];
    $target_id = $_POST['user_id'];

    if ($new_pass !== $conf_pass) {
        $message = "<div class='error-msg'>Passwords do not match!</div>";
        $showResetForm = true; // Keep the reset form visible
        $user_id = $target_id;
    } else {
        $hashedPassword = password_hash($new_pass, PASSWORD_DEFAULT);
        $update_sql = "UPDATE users SET password='$hashedPassword' WHERE id='$target_id'";
        
        if ($conn->query($update_sql)) {
            echo "<script>alert('Password updated successfully! Please login.'); window.location.href='login.php';</script>";
            exit();
        } else {
            $message = "<div class='error-msg'>Error updating password.</div>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>FixNDrive | Reset Password</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --primary: #e74c3c; --dark: #2c3e50; }
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('Logo.png');
            background-size: cover; background-position: center;
            display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0;
        }
        .card {
            background: white; padding: 40px; border-radius: 15px;
            width: 100%; max-width: 400px; text-align: center;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
        }
        .form-group { position: relative; margin-bottom: 20px; text-align: left; }
        .form-group i { position: absolute; left: 15px; top: 40px; color: #888; }
        label { font-size: 14px; font-weight: 600; color: #555; display: block; margin-bottom: 5px; }
        input {
            width: 100%; padding: 12px 12px 12px 40px; border: 1px solid #ddd;
            border-radius: 8px; box-sizing: border-box;
        }
        button {
            width: 100%; padding: 12px; background: var(--primary);
            color: white; border: none; border-radius: 8px;
            font-weight: bold; cursor: pointer; transition: 0.3s;
        }
        button:hover { background: #c0392b; }
        .error-msg { color: #721c24; background: #f8d7da; padding: 10px; border-radius: 5px; margin-bottom: 15px; }
        .back-link { margin-top: 20px; display: block; color: var(--primary); text-decoration: none; font-size: 14px; }
    </style>
</head>
<body>
    <div class="card">
        <?php if (!$showResetForm): ?>
            <h2>Identify Account</h2>
            <p>Enter your username or email to proceed.</p>
            <?php echo $message; ?>
            <form action="" method="POST">
                <div class="form-group">
                    <label>Username or Email</label>
                    <i class="fa fa-user-tag"></i>
                    <input type="text" name="identifier" required>
                </div>
                <button type="submit" name="verify_account">Continue</button>
            </form>
        <?php else: ?>
            <h2>Create New Password</h2>
            <p>Your account has been verified. Enter a new password below.</p>
            <?php echo $message; ?>
            <form action="" method="POST">
                <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                <div class="form-group">
                    <label>New Password</label>
                    <i class="fa fa-lock"></i>
                    <input type="password" name="new_password" required minlength="6">
                </div>
                <div class="form-group">
                    <label>Confirm Password</label>
                    <i class="fa fa-check-double"></i>
                    <input type="password" name="conf_password" required>
                </div>
                <button type="submit" name="update_password">Update Password</button>
            </form>
        <?php endif; ?>
        
        <a href="login.php" class="back-link">← Cancel and Login</a>
    </div>
</body>
</html>