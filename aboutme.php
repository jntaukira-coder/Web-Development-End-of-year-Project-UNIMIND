<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Discover Yourself — UniMind</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: "Poppins", sans-serif;
    }

    body {
      background-color: #0f1724;
      color: #f0f0f0;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    /* Navbar */
    header {
      background-color: #1a2236;
      padding: 15px 50px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-bottom: 2px solid #38bdf8;
      position: sticky;
      top: 0;
      z-index: 100;
    }

    header h1 {
      color: #38bdf8;
      font-size: 1.6rem;
      letter-spacing: 1px;
    }

    nav a {
      color: #f0f0f0;
      margin-left: 25px;
      text-decoration: none;
      font-weight: 500;
      transition: color 0.3s;
    }

    nav a:hover {
      color: #38bdf8;
    }

    /*  Container */
    .container {
      max-width: 800px;
      margin: 40px auto;
      padding: 20px;
    }

    .card {
      background: #1a2236;
      padding: 25px;
      border-radius: 12px;
      margin-bottom: 25px;
      box-shadow: 0 0 10px rgba(0,0,0,0.3);
    }

    h2 {
      color: #38bdf8;
      margin-bottom: 10px;
    }

    p {
      margin-bottom: 15px;
      line-height: 1.6;
    }

    input[type="radio"] {
      margin-right: 8px;
    }

    button {
      padding: 10px 20px;
      margin-top: 10px;
      background: #38bdf8;
      border: none;
      border-radius: 8px;
      color: #021526;
      font-weight: bold;
      cursor: pointer;
      transition: 0.3s;
    }

    button:hover {
      background: #1e90ff;
    }

    a.back {
      display: inline-block;
      margin-top: 25px;
      padding: 12px 25px;
      background: #38bdf8;
      color: #021526;
      text-decoration: none;
      font-weight: bold;
      border-radius: 8px;
      transition: 0.3s;
    }

    a.back:hover {
      background: #1e90ff;
    }

    footer {
      background: #1a2236;
      padding: 15px;
      text-align: center;
      font-size: 0.9rem;
      border-top: 2px solid #38bdf8;
      margin-top: auto;
    }
  </style>
</head>
<body>

<header>
  <h1>UniMind</h1>
  <nav>
    <a href="Home.php">Home</a>
    <a href="aboutme.php" style="color:#38bdf8;">Discover Yourself</a>
    <a href="focus.php">Focus Zone</a>
    <li><a href="logout.php">Logout</a></li>
  </nav>
</header>

<div class="container">
  <div class="card">
    <h2>Discover Yourself</h2>
    <p>Find your strengths, passions, and purpose through this short quiz.</p>
  </div>

  <div class="card">
    <h2>Mini Self-Discovery Quiz</h2>
    <form id="quizForm">
      <p><strong>1.</strong> You feel happiest when:</p>
      <label><input type="radio" name="q1" value="Helping others"> Helping others</label><br>
      <label><input type="radio" name="q1" value="Solving problems"> Solving problems</label><br>
      <label><input type="radio" name="q1" value="Creating things"> Creating things</label><br><br>

      <p><strong>2.</strong> Your dream role involves:</p>
      <label><input type="radio" name="q2" value="Leadership"> Leadership</label><br>
      <label><input type="radio" name="q2" value="Innovation"> Innovation</label><br>
      <label><input type="radio" name="q2" value="Community"> Community</label><br><br>

      <button type="button" onclick="submitQuiz()">See My Result</button>
    </form>
    <p id="quizResult" style="margin-top:10px; font-weight:bold; color:#38bdf8;"></p>
  </div>

</div>

<footer>
  <p>UniMind © 2025 | Discover Your Best Self</p>
</footer>

<script>
  function submitQuiz() {
    let q1 = document.querySelector('input[name="q1"]:checked');
    let q2 = document.querySelector('input[name="q2"]:checked');
    let result = "";

    if (!q1 || !q2) {
      result = "Please answer all questions!";
    } else {
      if (q1.value === "Helping others" && q2.value === "Community") {
        result = "You are a Community Builder!";
      } else if (q1.value === "Solving problems" && q2.value === "Innovation") {
        result = " You are an Innovator!";
      } else if (q1.value === "Creating things" && q2.value === "Leadership") {
        result = " You are a Visionary Leader!";
      } else {
        result = " You have unique strengths — embrace them!";
      }
    }
    document.getElementById("quizResult").innerText = result;
  }
</script>

</body>
</html>
