<?php
// admin/login.php
require_once __DIR__ . '/../includes/db.php';

// If already logged in, go directly to dashboard
if (!empty($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: index.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($username) || empty($password)) {
        $error = 'Please enter both username and password.';
    } else {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM admins WHERE username = :username LIMIT 1");
        $stmt->execute(['username' => $username]);
        $admin = $stmt->fetch();

        // Check password against bcrypt hash or fallback match
        if ($admin && (password_verify($password, $admin['password']) || $password === $admin['password'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_user'] = $admin['username'];
            $_SESSION['success'] = 'Welcome back, ' . htmlspecialchars($admin['username']) . '!';
            
            header("Location: index.php");
            exit;
        } else {
            // Also allow the exact default requested credential: admin / 12345 as emergency fallback
            if ($username === 'admin' && $password === '12345') {
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_id'] = 1;
                $_SESSION['admin_user'] = 'admin';
                $_SESSION['success'] = 'Welcome back, Admin!';
                
                header("Location: index.php");
                exit;
            }
            $error = 'Invalid username or password.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - VIP Escort Panel</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <style>
        :root {
            --bg-body: #0a0c0e;
            --bg-card: #14171d;
            --border-color: #262c36;
            --primary: #c5a880;
            --primary-hover: #b09169;
            --text-main: #f0f3f6;
            --text-muted: #8b949e;
            --danger: #eb5757;
            --danger-bg: rgba(235, 87, 87, 0.12);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: radial-gradient(circle at top center, #1b2029 0%, var(--bg-body) 70%);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.6);
        }

        .login-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .login-icon {
            width: 60px;
            height: 60px;
            border-radius: 16px;
            background: linear-gradient(135deg, var(--primary), #8f724d);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            color: #000;
            margin-bottom: 16px;
            box-shadow: 0 8px 20px rgba(197, 168, 128, 0.3);
        }

        .login-title {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 6px;
        }

        .login-subtitle {
            font-size: 14px;
            color: var(--text-muted);
        }

        .alert-error {
            background-color: var(--danger-bg);
            color: var(--danger);
            border: 1px solid rgba(235, 87, 87, 0.3);
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 13px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--text-main);
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 15px;
        }

        .form-control {
            width: 100%;
            padding: 13px 14px 13px 42px;
            background: #0d1014;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            color: #fff;
            font-size: 14px;
            font-family: inherit;
            transition: all 0.25s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(197, 168, 128, 0.2);
        }

        .btn-submit {
            width: 100%;
            padding: 13px;
            background: var(--primary);
            color: #000;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.25s ease;
            margin-top: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-submit:hover {
            background: var(--primary-hover);
            transform: translateY(-1px);
        }

        .login-footer {
            margin-top: 28px;
            text-align: center;
            font-size: 13px;
            color: var(--text-muted);
        }

        .login-footer a {
            color: var(--primary);
            text-decoration: none;
        }

        .login-footer a:hover {
            text-decoration: underline;
        }

        .default-credentials {
            background: rgba(255, 255, 255, 0.03);
            border: 1px dashed var(--border-color);
            border-radius: 10px;
            padding: 12px;
            margin-top: 20px;
            font-size: 12px;
            color: var(--text-muted);
            line-height: 1.6;
        }
        .default-credentials strong {
            color: var(--primary);
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="login-header">
            <div class="login-icon"><i class="fa-solid fa-lock"></i></div>
            <h1 class="login-title">Admin Access</h1>
            <p class="login-subtitle">Sign in to manage models, photos & stats</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert-error">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <span><?= htmlspecialchars($error) ?></span>
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <div class="form-group">
                <label class="form-label" for="username">Username</label>
                <div class="input-wrapper">
                    <i class="fa-regular fa-user input-icon"></i>
                    <input type="text" id="username" name="username" class="form-control" placeholder="admin" required autofocus value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <div class="input-wrapper">
                    <i class="fa-solid fa-key input-icon"></i>
                    <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>
            </div>

            <button type="submit" class="btn-submit">
                <span>Sign In</span>
                <i class="fa-solid fa-arrow-right"></i>
            </button>
        </form>

        <div class="default-credentials">
            <div>Login: <strong>admin</strong></div>
            <div>Password: <strong>12345</strong></div>
        </div>

        <div class="login-footer">
            <a href="../index.php"><i class="fa-solid fa-arrow-left"></i> Back to Website</a>
        </div>
    </div>

</body>
</html>
