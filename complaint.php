<?php
    session_start();
    require_once("db.php");
    $msg = '';

    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }

    $facilities = [];
    $result = $conn->query("SELECT id, name, location FROM facilities");
    while ($row = $result->fetch_assoc()) {
        $facilities[] = $row;
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $user_id     = $_SESSION['user_id'];
        $facility_id = $_POST['facility_id'] ?? null;
        $issues      = $_POST['issues'] ?? [];
        $other       = trim($_POST['other']);
        $image_path  = null;

        if (empty($facility_id) || (empty($issues) && empty($other))) {
            $msg = "❗ Please complete all required fields❗";
        } else {
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $uploadDir = "uploads/";
                $filename = time() . "_" . basename($_FILES['image']['name']);
                $targetPath = $uploadDir . $filename;

                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                    $image_path = $targetPath;
                }
            }

            $issues_str = implode(", ", $issues);
            $stmt = $conn->prepare("INSERT INTO complaints (id, facility_id, title, other, image_path) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("iisss", $user_id, $facility_id, $issues_str, $other, $image_path);

            if ($stmt->execute()) {
                $newComplaintId = $conn->insert_id;
                $action = "Submitted complaint";
                $log = $conn->prepare("INSERT INTO activity_log (user_id, complaint_id, action) VALUES (?, ?, ?)");
                $log->bind_param("iis", $user_id, $newComplaintId, $action);
                $log->execute();
                $msg = "✅ Complaint submitted successfully!";
            } else {
                $msg = "❌ Failed to submit complaint: " . $stmt->error;
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
    <title>Classroom</title>
    <link rel="stylesheet" href="styleForm.css">
</head>
<body>
    <div class="container">
        <h1>❗Complaint❗</h1>
        <h2>Faculty of Computing Facilities Complaint</h2>
        <?php
            if (!empty($msg)) {
                echo "<p>$msg</p>";
            }
        ?>

        <div class="reg">
            <form action="" method="post" enctype="multipart/form-data">
                <label for="facility_id">Facility</label><br>
                <select name="facility_id" id="facility_id" required>
                    <option value="">- Select Facility -</option>
                    <?php foreach ($facilities as $f): ?>
                        <option value="<?= $f['id'] ?>">
                            <?= htmlspecialchars("{$f['name']} - {$f['location']}") ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <br><br>

                <label for="issues">Issue(s)</label><br>
                <label>
                    <input type="checkbox" name="issues[]" value="Air-conditioning problems">
                    Air-conditioning problems ❄️
                </label><br>

                <label>
                    <input type="checkbox" name="issues[]" value="No power supply">
                    No power supply ⚡
                </label><br>

                <label>
                    <input type="checkbox" name="issues[]" value="Damaged chair or desk">
                    Damaged chair or desk 💺
                </label><br>

                <label>
                    <input type="checkbox" name="issues[]" value="Lighting issue">
                    Lighting issue 💡
                </label><br>

                <label>
                    <input type="checkbox" name="issues[]" value="Non-functioning power sockets">
                    Non-functioning power sockets 🔌
                </label><br>

                <label>
                    <input type="checkbox" name="issues[]" value="Faulty projector, speakers, or mic">
                    Faulty projector, speakers, or mic 🎤
                </label><br>

                <label>
                    <input type="checkbox" name="issues[]" value="Slow or no internet connection">
                    Slow or no internet connection 🌐
                </label><br>

                <label>
                    <input type="checkbox" name="issues[]" value="Computer not booting or freezing">
                    Computer not booting or freezing 💻
                </label><br>

                <label>
                    <input type="checkbox" name="issues[]" value="Faulty keyboard or mouse">
                    Faulty keyboard or mouse ⌨️
                </label><br>

                <label>
                    <input type="checkbox" name="issues[]" value="Lift malfunctions">
                    Lift malfunctions 🛗
                </label><br>

                <label>
                    <input type="checkbox" name="issues[]" value="Broken taps or plumbing faults">
                    Broken taps or plumbing faults 🚽
                </label><br>

                <label>
                    <input type="checkbox" name="issues[]" value="Damaged door">
                    Damaged door🚪
                </label><br>

                <label for="other">Other</label><br>
                <input type="text" name="other" id="other">
                <br><br><br>

                <label for="image">Upload Image:</label><br>
                <input type="file" name="image" id="image" accept="image/*"><br><br>

                <button onclick="location.href='viewComplaint.php'">Submit</button><br><br>
                <p><a href="dashboard.php">[ Home ]</a></p>
            </form>
        </div>
    </div>
</body>
</html>