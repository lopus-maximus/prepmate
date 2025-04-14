<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>create-flashcards</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  <link rel="stylesheet" href="css/styles.css" />
  <link rel="stylesheet" href="css/createFlashcards.css" />
</head>
<body>
  <div id="sidebar"></div>

  <div id="header"></div>

  <main class="main-content">
    <form method="POST" action="create-flashcards.php" id="flashcard-form">
      <div style="color:rgb(255, 255, 255); font-size: 2.5rem; font-weight: lighter">
        Create your flashcards
      </div>
      <input type="text" name="title" placeholder="Enter a title" class="title" required />
      <input type="text" name="description" placeholder="Add a description..." class="title description" style="height: 6rem" required />

      <div style="color:rgb(255, 255, 255); font-size: 2rem; font-weight: lighter; margin: 2rem 0 1.5rem 0;">
        Add flashcards
      </div>

      <div class="card-container" id="card-container">
      </div>

      <button type="button" class="addBtn" onclick="addCard()">ADD CARD</button>
      <br><br>
      <input type="submit" value="Save Deck" class="addBtn" style="background-color: #694586; color: white; border: none;" />
    </form>
  </main>

  <script>
    let cardCount = 0;

    function addCard(question = '', answer = '') {
      cardCount++;
      const card = document.createElement('div');
      card.classList.add('createCard');
      card.innerHTML = `
        <div class="card-top">
          <div>${cardCount}</div>
          <button type="button" class="trash-btn" onclick="this.closest('.createCard').remove()">
            <i class="fas fa-trash"></i>
          </button>
        </div>
        <div class="card-bottom">
          <div class="card-main">
            <input type="text" class="cardText" name="question[]" placeholder="Enter a question" value="${question}" required />
            <div style="font-size: 0.7rem; font-weight: bold">QUESTION</div>
          </div>
          <div class="card-main">
            <input type="text" class="cardText" name="answer[]" placeholder="Enter a response" value="${answer}" required />
            <div style="font-size: 0.7rem; font-weight: bold">RESPONSE</div>
          </div>
        </div>
      `;
      document.getElementById('card-container').appendChild(card);
    }

    window.onload = () => {
      addCard();
      addCard();
      addCard();
    };
  </script>

  <script src="js/layout.js"></script>
</body>
</html>
