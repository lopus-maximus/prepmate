<?php
// Connect to database
$conn = new mysqli("localhost", "webdevproj", "password123", "prepmate");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get deck ID from URL
$deckId = isset($_GET['deck_id']) ? intval($_GET['deck_id']) : 0;
$focusMode = isset($_GET['focus']) && $_GET['focus'] === 'learning';

// Fetch flashcards from DB
$sql = "SELECT question, answer FROM flashcards WHERE deck_id = $deckId";
$result = $conn->query($sql);

$flashcards = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $flashcards[] = $row;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Attempt Flashcards</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="css/styles.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <style>
    body {
      background: black;
    }
    .sl-Btn {
      background: white;
      padding: 1rem;
      width: 40%;
      margin: 2rem;
      color: #492665;
      border: 1.5px solid #492665;
      border-radius: 8px;
      font-size: 1rem;
      font-weight: bolder;
    }

    .know-Btn {
      color: #492665;
      border: 1.5px solid #492665;
    }

    .sl-Btn:hover {
      background: #492665;
      color: white;
      cursor: pointer;
    }

    .know-Btn:hover {
      background: #492665;
      color: white;
    }

    /* Updated flipcard styles */
    .flipcard-container {
      perspective: 1000px;
      width: 80%;
      height: 350px;
      margin: 1rem auto;
    }

    .flipcard {
      width: 100%;
      height: 100%;
      position: relative;
      transform-style: preserve-3d;
      transition: transform 0.8s;
      cursor: pointer;
    }

    .flipcard.flipped {
      transform: rotateY(180deg);
    }

    .flipcard-front, .flipcard-back {
      position: absolute;
      width: 100%;
      height: 100%;
      backface-visibility: hidden;
      -webkit-backface-visibility: hidden;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.3rem;
      font-weight: bold;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.2);
      padding: 20px;
      box-sizing: border-box;
    }

    .flipcard-front {
      color:rgb(255, 255, 255);
      background: #8d67ab;
      z-index: 2;
    }

    .flipcard-back {
      color: white;
      background: #492665;
      transform: rotateY(180deg);
    }

    .progress {
      text-align: center;
      font-size: 1.5rem;
      margin-bottom: 1rem;
      color: #cd8af6;
      font-weight: bold;
    }
  </style>
</head>
<body>

<!-- Sidebar -->
<div id="sidebar"></div>
<!-- Header -->
<div id="header"></div>

<main class="main-content" style="padding: 2rem">
  <?php if (count($flashcards) === 0): ?>
    <p style="font-size: 1.2rem; color: red;">No flashcards available in this deck.</p>
  <?php else: ?>
    <div class="progress" id="progress">1 / <?= count($flashcards) ?></div>

    <div class="flipcard-container">
      <div class="flipcard" id="flashcard">
        <div class="flipcard-front" id="front">
          <?php if (count($flashcards) > 0): ?>
            <?= htmlspecialchars($flashcards[0]['question']) ?>
          <?php endif; ?>
        </div>
        <div class="flipcard-back" id="back">
          <?php if (count($flashcards) > 0): ?>
            <?= htmlspecialchars($flashcards[0]['answer']) ?>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <div style="display: flex; justify-content: space-around; width: 80%; margin: 0 auto;">
      <button class="sl-Btn" id="learning-btn">Still Learning</button>
      <button class="sl-Btn know-Btn" id="know-btn">Know</button>
    </div>

<script>
// Immediately executed function to avoid conflicts with other scripts
(function() {
  // Get all necessary elements
  const card = document.getElementById("flashcard");
  const front = document.getElementById("front");
  const back = document.getElementById("back");
  const progress = document.getElementById("progress");
  const knowBtn = document.getElementById("know-btn");
  const learningBtn = document.getElementById("learning-btn");
  
  const flashcards = <?= json_encode($flashcards) ?>;
  const deckId = <?= $deckId ?>;
  let current = 0;
  let isFlipped = false;
  let stats = {
    know: 0,
    learning: 0
  };

  function flipCard() {
    console.log("Flip card clicked");
    isFlipped = !isFlipped;
    card.classList.toggle("flipped");
  }

  function renderCard(index) {
    if (index >= flashcards.length) {
      redirectToStats();
      return;
    }

    front.innerText = flashcards[index].question;
    back.innerText = flashcards[index].answer;
    progress.innerText = (index + 1) + " / " + flashcards.length;

    card.classList.remove("flipped");
    isFlipped = false;
  }

  function markResponse(type) {
    if (type === 'know') stats.know++;
    else if (type === 'learning') stats.learning++;

    current++;
    renderCard(current);
  }

  function redirectToStats() {
    window.location.href = `flashcard-stats.php?deck_id=${deckId}&know=${stats.know}&learning=${stats.learning}`;
  }

  function setupEventListeners() {
    card.addEventListener("click", flipCard);
    
    knowBtn.addEventListener("click", function() {
      markResponse('know');
    });
    
    learningBtn.addEventListener("click", function() {
      markResponse('learning');
    });
  }

  function init() {
    renderCard(current);
    setupEventListeners();
    console.log("Flashcard system initialized");
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", init);
  } else {
    init();
  }
})();
</script>

  <?php endif; ?>
</main>

<script src="js/layout.js" async defer onerror="console.log('Layout script failed to load but won\'t affect core functionality')"></script>
</body>
</html>