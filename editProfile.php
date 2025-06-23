<?php
  session_start();
  require_once("db.php");
  $user_id = $_SESSION['user_id'];
  $user_role = $_SESSION['user_role'];
  $msg = "";

  if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
  }

  if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $newName = trim($_POST['name']);
    $newEmail = trim($_POST['email']);

    if(!empty($newName) && !empty($newEmail)) {
        $stmt = $conn->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
        $stmt->bind_param("ssi", $newName, $newEmail, $user_id);

        if($stmt->execute()) {
            $msg = "✅ Profile updated successfully.";
        } else {
            $msg = "❌ Error updating profile: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $msg = "❗ Name and email cannot be empty.";
    }
  }

  $stmt = $conn->prepare("SELECT id, name, email, role FROM users WHERE id = ?");
  $stmt->bind_param("i", $user_id);
  $stmt->execute();
  $userResult = $stmt->get_result();
  $user = $userResult->fetch_assoc();
  $stmt->close();



  if ($user_role === 'admin') {
    $home = 'admin.php';
  } elseif ($user_role === 'staff') {
      $home = 'staff.php';
  } elseif ($user_role === 'user') {
      $home = 'dashboard.php';
  } else {
      $home = 'login.php';
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Personal Profile</title>
  <link rel="stylesheet" href="styleLogin.css">
  <link rel="stylesheet" href="styleGeneral.css">
</head>
<body>
  <div class="container">
    <h1>🪪 Personal Profile</h1>
    <h2>Editing [Name / Email]</h2>
    <br>
    <?php
        if (!empty($msg)) {
            echo "<p>$msg</p>";
        }
    ?>
    <form action="" method="post">
        <table>
            <tr>
                <th class='profile_th'>ID</th>
                <td class='profile_td'><?php echo $user['id']; ?></td>
            </tr>
            <tr>
                <th class='profile_th'>Name</th>
                <td class='profile_edit'><input type='text' name='name' value='<?php echo htmlspecialchars($user['name']); ?>'></td>
            </tr>
            <tr>
                <th class='profile_th'>Email</th>
                <td class='profile_edit'><input type='text' name='email' value='<?php echo htmlspecialchars($user['email']); ?>'></td>
            </tr>
            <tr>
                <th class='profile_th'>Role</th>
                <td class='profile_td'><?php echo $user['role']; ?></td>
            </tr>
        </table>
        <br><br>
        <button type="submit">Submit</button>
    </form>
    <p><a href="<?php echo $home ?>">[ Home ]</a></p>
</body>
</html>

