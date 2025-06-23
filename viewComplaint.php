<?php
    session_start();
    require_once("db.php");
    $user_id = $_SESSION['user_id'];
    $msg = "";

    if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'user') {
        header("Location: login.php");
        exit;
    }

    $stmt = $conn->prepare("
        SELECT c.id, f.location, f.name AS location_name, f.level, c.issues, c.other, c.status, c.created_at
        FROM complaints c
        JOIN facilities f ON c.facility_id = f.id
        WHERE c.user_id = ?
        ");

    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    $result = $stmt->get_result();
    $complaints = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>View Complaint History</title>
  <link rel="stylesheet" href="styleRegLog.css">
</head>
<body>
<div class="container">
  <h1>📃 Complaint History</h1>
  <h2>Faculty of Computing Facilities Complaint</h2>

  <?php
    if (!empty($msg)) {
        echo "<p>$msg</p>";
    }

    if (count($complaints) > 0) {
        echo "<table class='tablestyle'>";
        echo "<tr>
                <th>ID</th>
                <th>Location</th>
                <th>Location Name</th>
                <th>Level</th>
                <th>Issues</th>
                <th>Other</th>
                <th>Status</th>
                <th>Date</th>
                <th>Image</th>
                </tr>";

        foreach ($complaints as $comp) {
            echo "<tr>
                    <td>{$comp['id']}</td>
                    <td>{$comp['location']}</td>
                    <td>{$comp['location_name']}</td>
                    <td>{$comp['level']}</td>
                    <td>{$comp['issues']}</td>
                    <td>{$comp['other']}</td>
                    <td>{$comp['status']}</td>
                    <td>{$comp['created_at']}</td>";

            if (!empty($comp['image_path']) && file_exists($comp['image_path'])) {
                echo "<td><img src='{$comp['image_path']}' width='100'></td>";
            } else {
                echo "<td>No image</td>";
            }

            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "<p>No complaints found.</p>";
    }
  ?>

  <br>
  <p><a href="dashboard.php">[ Home ]</a></p>
</div>
</body>
</html>
