<?php
    session_start();
    require_once("db.php");
    $msg = '';

    if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
        header("Location: login.php");
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $name  = trim($_POST['name']);
        $email = trim($_POST['email']);
        $pwd   = trim($_POST['password']);
        $repwd = trim($_POST['repassword']);

        $add    = isset($_POST['add']);
        $remove = isset($_POST['remove']);

        if ($add) {
            if (empty($name) || empty($email) || empty($pwd) || empty($repwd)) {
                $msg = "❗ All fields are required to add a user ❗";
            } elseif ($pwd !== $repwd) {
                $msg = "❗ Passwords do not match ❗";
            } else {
                $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'user')");
                $stmt->bind_param("sss", $name, $email, $hashedPwd);

                if ($stmt->execute()) {
                    $msg = "✅ User added successfully.";
                } else {
                    $msg = "❌ Failed to add user: " . $stmt->error;
                }
                $stmt->close();
            }
        } elseif ($remove) {
            if (empty($name) || empty($email)) {
                $msg = "❗ Name and Email required to remove a user ❗";
            } else {
                $stmt = $conn->prepare("DELETE FROM users WHERE name = ? AND email = ? AND role != 'admin'");
                $stmt->bind_param("ss", $name, $email);
                $stmt->execute();

                if ($stmt->affected_rows > 0) {
                    $msg = "✅ User removed successfully.";
                } else {
                    $msg = "❌ No matching user found or cannot remove admin.";
                }
                $stmt->close();
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add or Remove Users</title>
    <link rel="stylesheet" href="styleForm.css">
    <script>
        function Delete() {
            return confirm("Are you sure you want to remove this user?");
        }
    </script>
</head>
<body>
    <div class="container">
        <h1><b>➕ Add Or Remove Users ➖</b></h1>
        <h2>Faculty of Computing Facilities Complaint</h2>
        <?php
            if (!empty($msg)) {
                echo "<p>$msg</p>";
            }
        ?>

        <div class="reg">
            <form action="" method="post">
                <label for="name">Name</label><br>
                <input type="text" name="name" id="name"><br><br>

                <label for="email">Email</label><br>
                <input type="text" name="email" id="email"><br><br>

                <hr>
                <p>*Enter password when add new user only*</p>

                <label for="password">Password</label><br>
                <input type="text" name="password" id="password"><br><br>

                <label for="repassword">Retype Password</label><br>
                <input type="text" name="repassword" id="repassword"><br><br><br>

                <button type="submit" name="add">Add</button>
                <button type="submit" name="remove" onclick="return Delete();">Remove</button>

                <p><a href="admin.php">[ Home ]</a> <a href="uploadFile.php">[ Insert using file ]</p>
            </form>
        </div>
    </div>
</body>
</html>