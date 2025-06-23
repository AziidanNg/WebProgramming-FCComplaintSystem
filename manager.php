<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Staff</title>
  <link rel="stylesheet" href="styleGeneral.css">
</head>
<body>
  <div class="header">
    <nav>
      <h1>🛠️ Facilities Complaint</h1>
      <p><a href="profile.php" class="task">Profile</a></p>
      <p><a href="manageComplaint.php" class="task">Manage Complaint</a></p>
      <p><a href="logout.php" class="task">Logout</a></p>
    </nav>
  </div>
    <br>

  <div class="main-content">
    <div class="title">
      <h1>Welcome to Faculty of Computing Facilities Complaint</h1>
      <h3>Please make complaint wisely 🤗</h3>
      <hr><br>
    </div>

    <div class="image">
      <div class="section">
        <h3>Classroom</h3>
        <img src="classroom.png" alt="Classroom">
        <p>A well-maintained classroom ensures a comfortable and focused environment 
            for students and lecturers to do lectures, discussions and presentations.</p><br>
        <p><b>Report issues such as:</b></p>
        <ul>
            <li>Broken chairs or desks 🪑</li>
            <li>Air-conditioning problems ❄️</li>
            <li>Faulty projector, speakers, or mic 🎤</li>
        </ul>
        <br><br>
      </div>

      <div class="section">
        <h3>Lab</h3>
        <img src="lab.png" alt="Lab">
        <p>Submit complaints regarding malfunctioning computers, missing equipment, 
            power outages, or network problems in the lab.</p><br>
        <p><b>Report issues such as:</b></p>
        <ul>
            <li>Slow or no internet connection 🌐</li>
            <li>Computer not booting or freezing 💻</li>
            <li>Faulty keyboard or mouse ⌨️</li>
        </ul>
        <br><br>
      </div>

      <div class="section">
        <h3>Utilities</h3>
        <img src="washroom.png" alt="Washroom">
        <p>This category includes facilities like washrooms and lifts, 
            as well as systems such as lighting, ventilation, and electrical outlets.</p><br>
        <p><b>Report issues such as:</b></p>
        <ul>
            <li>Broken taps or plumbing faults 🚽</li>
            <li>Lift malfunctions 🛗</li>
            <li>Faulty lighting 💡 or non-functioning power sockets 🔌</li>
        </ul>
        <br><br>
      </div>
    </div>
    <br>
    <button onclick="location.href='complaint.php'"><b>Complaint Here</b></button>
  </div>
    <br>
  <div class="footer">
    <p>&copy; 2025 : SECV2223-04 WEB PROGRAMMING</p>
  </div>
</body>
</html>