<?php
  session_start();
  require_once("db.php");
  $user_id = $_SESSION['user_id'];
  $msg = '';

  if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'staff') {
    header("Location: login.php");
    exit;
  }

  if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['complaint_id'], $_POST['status'])) {
    $complaint_id = intval($_POST['complaint_id']);
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE complaints SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $complaint_id);


    if ($stmt->execute()) {
      $msg = "✅ Complaint status updated!";
    } else {
      $msg = "❌ Failed to update: " . $stmt->error;
    }
      $stmt->close();
    }

    $stmt = $conn->prepare("SELECT * FROM complaints");
    $stmt->execute();

    $result = $stmt->get_result();
    $complaints = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Complaints</title>
  <link rel="stylesheet" href="styleRegLog.css">
</head>
<body>
  <div class="container">
    <h1>📋 Complaints</h1>
    <?php
      if (!empty($msg)) {
        echo "<p>$msg</p>";
      }

      if (count($complaints) > 0) {
          echo '<table class="tablestyle">';
          echo '<tr>
                <th>ID</th>
                <th>Location</th>
                <th>Location Name</th>
                <th>Issues</th>
                <th>Other</th>
                <th>Status</th>
                <th>Update</th>
                </tr>';

          foreach ($complaints as $comp) {
              echo '<tr>';
              echo '<td>' . $comp['id'] . '</td>';
              echo '<td>' . $comp['location'] . '</td>';
              echo '<td>' . $comp['location_name'] . '</td>';
              echo '<td>' . $comp['issues'] . '</td>';
              echo '<td>' . $comp['other'] . '</td>';
              echo '<td>' . $comp['status'] . '</td>';
              echo '<td>
                      <form method="post" style="display: inline;">
                          <input type="hidden" name="complaint_id" value="' . $comp['id'] . '">
                          <select name="status">
                              <option value="Pending"' . ($comp['status'] == 'Pending' ? ' selected' : '') . '>Pending</option>
                              <option value="In Progress"' . ($comp['status'] == 'In Progress' ? ' selected' : '') . '>In Progress</option>
                              <option value="Resolved"' . ($comp['status'] == 'Resolved' ? ' selected' : '') . '>Resolved</option>
                          </select>
                          <button type="submit">Update</button>
                      </form>
                    </td>';
              echo '</tr>';
          }
          echo '</table>';
      } else {
          echo '<p>No complaints found.</p>';
      }
    ?>
<p><a href="staff.php">[ Home ]</a></p>
</body>
</html>

