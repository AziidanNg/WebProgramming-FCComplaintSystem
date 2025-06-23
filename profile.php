<?php
  session_start();
  require_once("db.php");
  $user_id = $_SESSION['user_id'];
  $user_role = $_SESSION['user_role'];

  if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
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
  <title>Personal Profile</title>
  <link rel="stylesheet" href="styleLogin.css">
  <link rel="stylesheet" href="styleGeneral.css">
</head>
<body>
  <div class="container">
    <h1>🪪 Personal Profile</h1><br><br>
    <table>
      <tr>
        <th class='profile_th'>ID</th>
        <td class='profile_td'><?php echo $user['id']; ?></td>
      </tr>
      <tr>
        <th class='profile_th'>Name</th>
        <td class='profile_td'><?php echo $user['name']; ?></td>
      </tr>
      <tr>
        <th class='profile_th'>Email</th>
        <td class='profile_td'><?php echo $user['email']; ?></td>
      </tr>
      <tr>
        <th class='profile_th'>Role</th>
        <td class='profile_td'><?php echo $user['role']; ?></td>
      </tr>
    </table>
    <br><br>
    <button onclick="location.href='editProfile.php'">Update</button>
    <p><a href="<?php echo $home ?>">[ Home ]</a></p>
</body>
</html>

