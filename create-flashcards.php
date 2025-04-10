<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("This page is for submitting flashcards only.");
  }
  

$host = 'localhost';
$username = 'webdevproj';
$password = 'password123';
$dbname = 'prepmate';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Get form data
$title = $_POST['title'];
$description = $_POST['description'];
$questions = $_POST['question'];
$answers = $_POST['answer'];

// Insert deck
$deck_sql = "INSERT INTO decks (title, description) VALUES (?, ?)";
$stmt = $conn->prepare($deck_sql);
$stmt->bind_param("ss", $title, $description);
$stmt->execute();
$deck_id = $stmt->insert_id;
$stmt->close();

// Insert flashcards
$card_sql = "INSERT INTO flashcards (deck_id, question, answer) VALUES (?, ?, ?)";
$stmt = $conn->prepare($card_sql);

for ($i = 0; $i < count($questions); $i++) {
  $q = $questions[$i];
  $a = $answers[$i];
  $stmt->bind_param("iss", $deck_id, $q, $a);
  $stmt->execute();
}
$stmt->close();

$conn->close();

// Redirect to dashboard or confirmation page
header("Location: flashcards.php");
exit();
?>
