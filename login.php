<?php
    session_start();
    require_once("db.php");
    $msg = '';
    $email_value = isset($_COOKIE['email']) ? $_COOKIE['email'] : '';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $email = trim($_POST['email']);
        $pwd   = trim($_POST['password']);

        setcookie("email", $email, time() + (86400 * 7), "/");

        if (empty($email) || empty($pwd)) {
            $msg = "❗All fields are required❗";
        } else {
            $stmt = $conn->prepare("SELECT id, name, password, role FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                $user = $result->fetch_assoc();

                if (password_verify($pwd, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['username'];
                    $_SESSION['user_role'] = $user['role'];

                    if ($user['role'] === 'admin') {
                        header("Location: admin.php");
                    } elseif ($user['role'] === 'staff') {
                        header("Location: staff.php");
                    } else {
                        header("Location: dashboard.php");
                    }
                    exit;
                } else {
                    $msg = "❌ Incorrect password ❌";
                }
            } else {
                $msg = "❌ No user found with that email ❌";
            }
            $stmt->close();
        }
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styleLogin.css">
   
</head>
<body>
    <div class="login-container" >
        <h1><b>Login</b></h1>
        <h2>Faculty of Computing Facilities Complaint</h2>

        <div class="reg">
            <form action="" method="post">
                <label for="email">Email</label>
                <input type="text" name="email" id="email" value="<?php echo htmlspecialchars($email_value); ?>">

                <label for="password">Password</label>
                <input type="password" name="password" id="password">

                <?php
                    if (!empty($msg)) {
                        echo "<p>$msg</p>";
                    }
                ?>

             <button type="submit">Log In</button>

                <p>Not registered? <a href="register.php"> [ Register ]</a></p><br>
            </form>
        </div>

        <div class="login-container animate-fade-in"></div>

        
    </div>
</body>
</html>

               
