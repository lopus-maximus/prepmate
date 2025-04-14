<?php
// Connect to database
$conn = new mysqli("localhost", "webdevproj", "password123", "prepmate");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get deck ID and stats from URL parameters
$deckId = isset($_GET['deck_id']) ? intval($_GET['deck_id']) : 0;
$know = isset($_GET['know']) ? intval($_GET['know']) : 0;
$learning = isset($_GET['learning']) ? intval($_GET['learning']) : 0;
$total = $know + $learning;

// Calculate percentage for know cards
$percentage = $total > 0 ? round(($know / $total) * 100) : 0;

// Get deck title
$sql = "SELECT title FROM decks WHERE id = $deckId";
$result = $conn->query($sql);
$deckTitle = "Flashcards";
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $deckTitle = htmlspecialchars($row['title']);
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Flashcard Stats</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="css/styles.css" />
    <style>
        body{
            background: black;
        }
        .container {
            text-align: center;
            background: black;
            border-radius: 1rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 2rem;
            margin-top: 2rem;
        }

        h2 {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 1rem;
            color:rgb(255, 255, 255);
        }

        .review-section {
            display: flex;
            color:rgb(200, 121, 250);
            flex-direction: column;
            align-items: center;
            margin-bottom: 2rem;
        }

        .progress-circle {
            width: 10rem;
            height: 10rem;
            border-radius: 50%;
            border: 0.6rem solid #a373c2;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: bold;
            color: white;
            background: black;
            position: relative;
            margin-right: 5rem;
        }

        .progress-circle::before {
            position: absolute;
            font-size: 1.5rem;
            font-weight: bold;
        }

        .review-text {
            font-size: 1.5rem;
            font-weight: bold;
            margin: 1rem;
        }

        .stats {
            margin-bottom: 2rem;
        }

        .stat-box {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            border-radius: 0.6rem;
            font-weight: bold;
            width: 16rem;
            margin: 0.5rem auto;
            font-size: 1.5rem;
        }

        .still-learning {
            border: 0.2rem solid #8d67ab;
            color: #381354;
            background: #eed7fd;
            margin: 2rem;
        }

        .know {
            border: 0.2rem solid #8d67ab;
            color: #381354;
            background: #eed7fd;
        }

        .buttons {
            display: flex;
            justify-content: center;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .btn {
            border: 0.2rem solid #8d67ab;
            background: white;
            padding: 1rem 1.5rem;
            border-radius: 0.6rem;
            cursor: pointer;
            font-weight: bold;
            font-size: 1.2rem;
            color: #492665;
            text-decoration: none;
            margin: 0.5rem;
        }

        .btn:hover {
            background: #492665;
            color: white;
            transition: 0.3s ease;
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <div id="sidebar"></div>
    
    <div id="header"></div>
    
    <main class="main-content">
        <div class="container">
            <h2><?php echo $deckTitle; ?></h2>
            
            <div class="review-section">
                <div class="review-text">Review</div>
            </div>
            
            <div style="display:flex; align-items:center; margin-bottom:2rem;">
                <div class="progress-circle">
                    <span><?php echo $percentage; ?>%</span>
                </div>
                <div class="stats">
                    <div class="stat-box still-learning">
                        <span>Still Learning</span>
                        <span><?php echo $learning; ?></span>
                    </div>
                    <div class="stat-box know">
                        <span>Know</span>
                        <span><?php echo $know; ?></span>
                    </div>
                </div>
            </div>
            
            <div class="buttons">
                <a href="attempt-flashcards.php?deck_id=<?php echo $deckId; ?>" class="btn">
                    Restart Flashcards
                </a>
                <a href="flashcards.php" class="btn">
                    Back to Decks
                </a>
            </div>
        </div>
    </main>
    
    <script src="js/layout.js"></script>
</body>
</html>