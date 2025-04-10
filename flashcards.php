<?php
// Connect to database
$conn = new mysqli("localhost", "webdevproj", "password123", "prepmate");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all decks
$sql = "SELECT id, title, description, created_at FROM decks ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Flashcards Dashboard</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
  <link rel="stylesheet" href="css/styles.css" />
  <link rel="stylesheet" href="css/card.css" />
  <link rel="stylesheet" href="css/flashcard.css" />
  <style>
    body{
      background: black;
    }
    button:hover {
      cursor: pointer;
      transform: scale(1.1);
      transition: transform 0.3s ease-in-out;
    }
  </style>
</head>
<body>

<!-- Sidebar -->
<div id="sidebar"></div>
<!-- Header -->
<div id="header"></div>

<main class="main-content">
  <div style="color: #b584dc; font-size: 2.5rem; font-weight: bolder">Flashcards</div>

  <!-- Search box -->
  <div class="search-box">
    <button class="search-btn"><i class="fas fa-search"></i></button>
    <input type="text" class="search-input" placeholder="Search for flashcards" />
  </div>

  <!-- Create new deck -->
  <div class="flashcard-box">
    <div>
      <i class="fas fa-folder folder-icon"></i>
      <span class="flashcard-text" style="margin-left: 1rem">Make your own flashcards</span>
    </div>
    <a href="create-flashcards-form.php" class="create-link" style="text-decoration: none">Create</a>
  </div>

  <!-- All decks -->
  <div class="flashcards">
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $title = htmlspecialchars($row['title']);
            $created = date("d - m - Y", strtotime($row['created_at']));
            $deckId = $row['id'];

            // Count number of cards
            $countRes = $conn->query("SELECT COUNT(*) as total FROM flashcards WHERE deck_id = $deckId");
            $countRow = $countRes->fetch_assoc();
            $totalCards = $countRow['total'];

            echo "
            <div class='card'>
              <h2>$title</h2>
              <div class='setno'>$totalCards set</div>
              <div style='font-size: 0.9rem; color:rgb(255, 255, 255);'>
                Created on: $created
              </div>
              <button onclick=\"window.location.href='attempt-flashcards.php?deck_id=$deckId'\" class='startBtn'>
                Start
              </button>
            </div>";
        }
    } else {
        echo "<p style='margin-top: 2rem; font-size: 1.2rem;'>No decks created yet.</p>";
    }

    $conn->close();
    ?>
  </div>
</main>

<script src="js/layout.js"></script>
</body>
</html>
