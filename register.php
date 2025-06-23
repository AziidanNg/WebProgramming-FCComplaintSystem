<?php
    require_once("db.php");
    $msg = '';
    $email_value = isset($_COOKIE['email']) ? $_COOKIE['email'] : '';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $name  = trim($_POST['name']);
        $email = trim($_POST['email']);
        $pwd   = trim($_POST['password']);
        $repwd = trim($_POST['repassword']);

        setcookie("email", $email, time() + (86400 * 7), "/");

        $checkEmail = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $checkEmail->bind_param("s", $email);
        $checkEmail->execute();
        $checkEmail->store_result();


        if (empty($name) || empty($email) || empty($pwd) || empty($repwd)) {
            $msg = "❗All fields are required❗";
        } elseif ($pwd !== $repwd) {
            $msg = "❗Passwords do not match❗";
        } elseif ($checkEmail->num_rows >0){
            $msg = "❗Email already registered❗";
        } else {
            $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
            $role_id = 1; 
            $stmt->bind_param("sssi", $name, $email, $hashedPwd, $role_id);

            if ($stmt->execute()) {
                $msg = "✅ Registered successfully! <a href='login.php'>Login here</a>.";
            } else {
                $msg = "❌ Registration failed: " . $stmt->error;
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
    <title>Register</title>
    <link rel="stylesheet" href="styleRegLog.css">
</head>
<body>
    <div class="container animate-fade-in">
          <h1 class="animate-slide-down"><b>🧾 Registration</b></h1>
        <h2 class="animate-slide-down delay-1">Faculty of Computing Facilities Complaint</h2>

        <div class="reg">
            <form action="" method="post">
               <?php if (!empty($msg)): ?>
                    <p class="message animate-pop-in"><?php echo htmlspecialchars($msg); ?></p>
                <?php endif; ?>

               <div class="form-group animate-fade-in delay-3">
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name">
                </div>

                <div class="form-group animate-fade-in delay-4">
                    <label for="email">Email</label>
                    <input type="text" name="email" id="email" value="<?php echo htmlspecialchars($email_value); ?>">
                </div>

                <div class="form-group animate-fade-in delay-5">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password">
                </div>

                <div class="form-group animate-fade-in delay-6">
                    <label for="repassword">Retype Password</label>
                    <input type="password" name="repassword" id="repassword">
                </div>
                
                <button>Submit</button>

                <div class="section animate-fade-in"></div>

                <p>Already registered? <a href="login.php"> [ Log In ]</a></p><br>
            </form>
        </div>
    </div>
</body>
</html>