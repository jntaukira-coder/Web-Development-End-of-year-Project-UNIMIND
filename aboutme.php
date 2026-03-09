<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Discover Yourself — UniMind</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: "Poppins", sans-serif;
    }

    body {
      background-color: #0f0f20;
      color: #f0f0f0;
      min-height: 100vh;
      display: flex;
      font-family: "Inter", sans-serif;
    }

    /* App Container */
    .app-container {
      display: flex;
      width: 100%;
      height: 100vh;
    }

    /* Sidebar */
    .sidebar {
      width: 280px;
      background: linear-gradient(180deg, #1a0a2e, #0f0f20);
      padding: 2rem 1.5rem;
      border-right: 1px solid #4a00ff;
      display: flex;
      flex-direction: column;
      position: fixed;
      height: 100vh;
      overflow: hidden;
      z-index: 1000;
    }

    .logo-section {
      margin-bottom: 2rem;
      text-align: center;
    }

    .logo {
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.5rem;
      font-weight: 700;
      color: #00d4ff;
      gap: 0.5rem;
      margin-bottom: 1rem;
    }

    .logo i {
      font-size: 1.8rem;
    }

    .user-profile {
      background: rgba(0, 212, 255, 0.1);
      border-radius: 12px;
      padding: 1rem;
      margin-bottom: 2rem;
      border: 1px solid #4a00ff;
    }

    .user-avatar {
      width: 40px;
      height: 40px;
      background: #00d4ff;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 700;
      color: #021526;
      margin: 0 auto 0.5rem;
    }

    .user-info {
      text-align: center;
    }

    .user-name {
      font-weight: 600;
      color: #ffffff;
      margin-bottom: 0.25rem;
    }

    .user-status {
      font-size: 0.875rem;
      color: #00d4ff;
    }

    .sidebar-nav {
      display: flex;
      flex-direction: column;
      gap: 0.5rem;
    }

    .nav-item {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      padding: 0.75rem 1rem;
      border-radius: 8px;
      text-decoration: none;
      color: #e0e0ff;
      transition: all 0.3s ease;
      font-weight: 500;
    }

    .nav-item:hover {
      background: rgba(0, 212, 255, 0.1);
      color: #00d4ff;
      transform: translateX(5px);
    }

    .nav-item.active {
      background: rgba(0, 212, 255, 0.2);
      color: #00d4ff;
      border-left: 3px solid #00d4ff;
    }

    .nav-item i {
      width: 20px;
      text-align: center;
    }

    /* Main Content */
    .main-content {
      margin-left: 280px;
      padding: 2rem;
      background: #0f0f20;
      flex: 1;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    .card {
      background: linear-gradient(135deg, #1a0a2e, #2a0a4e);
      padding: 2rem;
      border-radius: 12px;
      margin-bottom: 1.5rem;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.4);
      transition: 0.3s;
      border: 1px solid #4a00ff;
      max-width: 800px;
    }

    .card:hover {
      transform: scale(1.02);
      box-shadow: 0 0 35px rgba(56, 189, 248, 0.7);
    }

    h1, h2 {
      color: #00d4ff;
      margin-top: 0;
    }

    p {
      margin-bottom: 15px;
      line-height: 1.6;
      color: #e0e0ff;
    }

    input[type="radio"] {
      margin-right: 8px;
    }

    button {
      padding: 10px 20px;
      margin-top: 10px;
      background: #00d4ff;
      border: none;
      border-radius: 8px;
      color: #021526;
      font-weight: bold;
      cursor: pointer;
      transition: 0.3s;
    }

    button:hover {
      background: #00a8cc;
      transform: scale(1.05);
    }

    a.back {
      display: inline-block;
      margin-top: 25px;
      padding: 12px 25px;
      background: #00d4ff;
      color: #021526;
      text-decoration: none;
      font-weight: bold;
      border-radius: 8px;
      transition: 0.3s;
    }

    a.back:hover {
      background: #00a8cc;
    }

    footer {
      background: #1a0a2e;
      padding: 15px;
      text-align: center;
      font-size: 0.9rem;
      border-top: 2px solid #4a00ff;
      margin-top: auto;
    }

    /* Feature Headers */
    .feature-header {
      display: flex;
      align-items: center;
      gap: 1rem;
      margin-bottom: 1.5rem;
      padding-bottom: 1rem;
      border-bottom: 1px solid #4a00ff;
    }

    .feature-icon {
      width: 50px;
      height: 50px;
      background: linear-gradient(135deg, #00d4ff, #4a00ff);
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #ffffff;
      font-size: 1.5rem;
      transition: all 0.3s ease;
      box-shadow: 0 2px 8px rgba(0, 212, 255, 0.2);
    }

    .feature-icon:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(0, 212, 255, 0.3);
      background: linear-gradient(135deg, #00d4ff, #4a00ff);
    }

    .feature-content h3 {
      color: #00d4ff;
      margin-bottom: 0.25rem;
      font-size: 1.25rem;
      font-weight: 600;
    }

    .feature-content p {
      color: #e0e0ff;
      margin: 0;
      font-size: 0.875rem;
      opacity: 0.9;
    }

    /* Enhanced Quiz Results */
    .quiz-result {
      margin-top: 1.5rem;
      padding: 1.5rem;
      background: linear-gradient(135deg, rgba(0, 212, 255, 0.1), rgba(74, 0, 255, 0.1));
      border-radius: 12px;
      border: 1px solid #4a00ff;
      display: none;
      transition: all 0.3s ease;
    }

    .quiz-result.show {
      display: block;
      animation: fadeIn 0.5s ease-out;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(-10px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .quiz-result h4 {
      color: #00d4ff;
      margin-bottom: 1rem;
      font-size: 1.2rem;
      font-weight: 600;
    }

    .quiz-result p {
      color: #e0e0ff;
      margin-bottom: 0.75rem;
      line-height: 1.6;
      font-size: 0.95rem;
    }

    .quiz-result ul {
      color: #e0e0ff;
      margin-left: 1.5rem;
      margin-bottom: 1rem;
    }

    .quiz-result li {
      margin-bottom: 0.5rem;
      transition: all 0.2s ease;
    }

    .quiz-result li:hover {
      color: #00d4ff;
      transform: translateX(3px);
    }

    .strength-item {
      background: linear-gradient(135deg, rgba(0, 255, 136, 0.1), rgba(0, 212, 255, 0.05));
      border: 1px solid #00ff88;
      padding: 0.75rem;
      border-radius: 8px;
      margin-bottom: 0.5rem;
      color: #ffffff;
      transition: all 0.3s ease;
    }

    .strength-item:hover {
      transform: translateY(-1px);
      box-shadow: 0 3px 10px rgba(0, 255, 136, 0.2);
      border-color: #00d4ff;
    }

    /* Enhanced Reflection Journal */
    .reflection-prompts {
      background: linear-gradient(135deg, rgba(0, 212, 255, 0.1), rgba(74, 0, 255, 0.1));
      border: 1px solid #4a00ff;
      padding: 1rem;
      border-radius: 12px;
      margin-bottom: 1rem;
    }

    .reflection-prompts p {
      margin-bottom: 0.5rem;
    }

    #dailyPrompt {
      color: #00d4ff;
      font-weight: 500;
      font-size: 1rem;
    }

    textarea {
      width: 100%;
      padding: 1rem;
      border: 1px solid #4a00ff;
      border-radius: 8px;
      background: rgba(0, 212, 255, 0.05);
      color: #ffffff;
      font-family: inherit;
      resize: vertical;
      transition: all 0.3s ease;
      font-size: 0.95rem;
    }

    textarea:focus {
      outline: none;
      border-color: #00d4ff;
      box-shadow: 0 0 10px rgba(0, 212, 255, 0.2);
      background: rgba(0, 212, 255, 0.08);
    }

    textarea::placeholder {
      color: rgba(224, 224, 255, 0.5);
      font-style: italic;
    }

    .success-message {
      background: linear-gradient(135deg, rgba(0, 255, 136, 0.1), rgba(0, 212, 255, 0.05));
      border: 1px solid #00ff88;
      color: #00ff88;
      padding: 0.75rem;
      border-radius: 8px;
      text-align: center;
      margin-top: 1rem;
      font-weight: 500;
      animation: fadeIn 0.5s ease-out;
    }

    /* Enhanced Growth Tracker */
    .growth-goals {
      margin-bottom: 1.5rem;
    }

    .goal-item {
      display: flex;
      align-items: center;
      gap: 1rem;
      margin-bottom: 1rem;
      padding: 0.75rem;
      background: rgba(0, 212, 255, 0.05);
      border-radius: 8px;
      border: 1px solid #4a00ff;
      transition: all 0.3s ease;
    }

    .goal-item:hover {
      background: rgba(0, 212, 255, 0.08);
      transform: translateX(3px);
      box-shadow: 0 2px 8px rgba(0, 212, 255, 0.15);
    }

    .goal-item span:first-child {
      min-width: 160px;
      color: #e0e0ff;
      font-size: 0.875rem;
      font-weight: 500;
    }

    .progress-bar {
      flex: 1;
      height: 8px;
      background: rgba(0, 212, 255, 0.2);
      border-radius: 4px;
      overflow: hidden;
    }

    .progress-fill {
      height: 100%;
      background: linear-gradient(90deg, #00d4ff, #00ff88);
      border-radius: 4px;
      transition: width 0.5s ease;
    }

    .progress-text {
      min-width: 40px;
      text-align: right;
      color: #00d4ff;
      font-weight: 600;
      font-size: 0.875rem;
    }

    /* Enhanced Future Vision */
    .future-vision {
      background: linear-gradient(135deg, rgba(0, 212, 255, 0.1), rgba(74, 0, 255, 0.1));
      border: 1px solid #4a00ff;
      padding: 1.5rem;
      border-radius: 12px;
      margin-top: 1rem;
    }

    .future-vision h4 {
      color: #00d4ff;
      margin-bottom: 1rem;
      font-size: 1.2rem;
      font-weight: 600;
    }

    .vision-item {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      margin-bottom: 0.75rem;
      color: #e0e0ff;
      padding: 0.5rem;
      border-radius: 6px;
      transition: all 0.3s ease;
    }

    .vision-item:hover {
      background: rgba(0, 212, 255, 0.08);
      transform: translateX(3px);
    }

    .vision-item i {
      color: #00ff88;
      min-width: 20px;
      font-size: 1rem;
    }

    /* Enhanced Input Fields */
    input[type="text"] {
      width: 100%;
      padding: 0.75rem;
      border: 1px solid #4a00ff;
      border-radius: 8px;
      background: rgba(0, 212, 255, 0.05);
      color: #ffffff;
      margin-bottom: 1rem;
      transition: all 0.3s ease;
      font-size: 0.95rem;
    }

    input[type="text"]:focus {
      outline: none;
      border-color: #00d4ff;
      box-shadow: 0 0 10px rgba(0, 212, 255, 0.2);
      background: rgba(0, 212, 255, 0.08);
    }

    input[type="text"]::placeholder {
      color: rgba(224, 224, 255, 0.5);
      font-style: italic;
    }

    /* Enhanced Buttons */
    button {
      padding: 0.75rem 1.5rem;
      background: linear-gradient(135deg, #00d4ff, #4a00ff);
      border: none;
      border-radius: 8px;
      color: #ffffff;
      font-weight: 600;
      font-size: 0.9rem;
      cursor: pointer;
      transition: all 0.3s ease;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    button:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(0, 212, 255, 0.3);
      background: linear-gradient(135deg, #00d4ff, #4a00ff);
    }

    button:active {
      transform: translateY(0);
    }

    /* Enhanced animations */
    @keyframes slideIn {
      from {
        opacity: 0;
        transform: translateY(-20px) scale(0.95);
      }
      to {
        opacity: 1;
        transform: translateY(0) scale(1);
      }
    }

    /* Responsive */
    @media (max-width: 768px) {
      .sidebar {
        width: 100%;
        height: auto;
        position: relative;
      }
      
      .main-content {
        margin-left: 0;
      }

      .feature-header {
        flex-direction: column;
        text-align: center;
        gap: 1rem;
      }

      .feature-icon {
        width: 50px;
        height: 50px;
        font-size: 1.5rem;
      }

      .goal-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
      }

      .goal-item span:first-child {
        min-width: auto;
      }
    }
  </style>
</head>
<body>

<div class="app-container">
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="logo-section">
            <div class="logo">
                <i class="fas fa-graduation-cap"></i>
                UNIMIND
            </div>
        </div>
        
        <div class="user-profile">
            <div class="user-avatar">S</div>
            <div class="user-info">
                <div class="user-name">Student</div>
                <div class="user-status">Discover Mode</div>
            </div>
        </div>
        
        <nav class="sidebar-nav">
            <a href="Home.php" class="nav-item">
                <i class="fas fa-home"></i>
                Dashboard
            </a>
            <a href="focus.php" class="nav-item">
                <i class="fas fa-brain"></i>
                Focus Zone
            </a>
            <a href="aboutme.php" class="nav-item active">
                <i class="fas fa-user"></i>
                Discover Yourself
            </a>
            <a href="campus life.php" class="nav-item">
                <i class="fas fa-map-marked-alt"></i>
                Campus Navigation
            </a>
            <a href="login.php" class="nav-item">
                <i class="fas fa-sign-out-alt"></i>
                Logout
            </a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
          <div class="card">
            <h2>Discover Yourself</h2>
            <p>Find your strengths, passions, and purpose through these powerful self-discovery tools.</p>
          </div>

          <!-- Personality Discovery Quiz -->
          <div class="card">
            <div class="feature-header">
              <div class="feature-icon">
                <i class="fas fa-brain"></i>
              </div>
              <div class="feature-content">
                <h3>Personality Discovery Quiz</h3>
                <p>Understand your personality type and learning style</p>
              </div>
            </div>
            <form id="personalityQuiz">
              <p><strong>1.</strong> Do you prefer studying alone or with others?</p>
              <label><input type="radio" name="q1" value="alone"> Alone</label><br>
              <label><input type="radio" name="q1" value="others"> With others</label><br><br>

              <p><strong>2.</strong> Do you learn better by reading, listening, or practicing?</p>
              <label><input type="radio" name="q2" value="reading"> Reading</label><br>
              <label><input type="radio" name="q2" value="listening"> Listening</label><br>
              <label><input type="radio" name="q2" value="practicing"> Practicing</label><br><br>

              <p><strong>3.</strong> Do you plan ahead or work under pressure?</p>
              <label><input type="radio" name="q3" value="planner"> Plan ahead</label><br>
              <label><input type="radio" name="q3" value="pressure"> Work under pressure</label><br><br>

              <button type="button" onclick="submitPersonalityQuiz()">Discover My Personality</button>
            </form>
            <div id="personalityResult" class="quiz-result"></div>
          </div>

          <!-- Strengths Finder -->
          <div class="card">
            <div class="feature-header">
              <div class="feature-icon">
                <i class="fas fa-star"></i>
              </div>
              <div class="feature-content">
                <h3>Strengths Finder</h3>
                <p>Discover what you're naturally good at</p>
              </div>
            </div>
            <form id="strengthsQuiz">
              <p><strong>What activities make you lose track of time?</strong></p>
              <label><input type="checkbox" name="activities" value="problem-solving"> Problem-solving</label><br>
              <label><input type="checkbox" name="activities" value="creative-work"> Creative work</label><br>
              <label><input type="checkbox" name="activities" value="helping-others"> Helping others</label><br>
              <label><input type="checkbox" name="activities" value="organizing"> Organizing things</label><br><br>

              <p><strong>What subjects feel easiest to you?</strong></p>
              <label><input type="checkbox" name="subjects" value="math"> Mathematics</label><br>
              <label><input type="checkbox" name="subjects" value="writing"> Writing/Communication</label><br>
              <label><input type="checkbox" name="subjects" value="science"> Science</label><br>
              <label><input type="checkbox" name="subjects" value="arts"> Arts/Design</label><br><br>

              <button type="button" onclick="submitStrengthsQuiz()">Find My Strengths</button>
            </form>
            <div id="strengthsResult" class="quiz-result"></div>
          </div>

          <!-- Interest Explorer -->
          <div class="card">
            <div class="feature-header">
              <div class="feature-icon">
                <i class="fas fa-compass"></i>
              </div>
              <div class="feature-content">
                <h3>Interest Explorer</h3>
                <p>Explore career paths and interests</p>
              </div>
            </div>
            <form id="interestsQuiz">
              <p><strong>Select areas that interest you:</strong></p>
              <label><input type="checkbox" name="interests" value="technology"> Technology</label><br>
              <label><input type="checkbox" name="interests" value="business"> Business</label><br>
              <label><input type="checkbox" name="interests" value="science"> Science</label><br>
              <label><input type="checkbox" name="interests" value="arts"> Arts</label><br>
              <label><input type="checkbox" name="interests" value="social"> Social Impact</label><br><br>

              <button type="button" onclick="submitInterestsQuiz()">Explore My Interests</button>
            </form>
            <div id="interestsResult" class="quiz-result"></div>
          </div>

          <!-- Reflection Journal -->
          <div class="card">
            <div class="feature-header">
              <div class="feature-icon">
                <i class="fas fa-book"></i>
              </div>
              <div class="feature-content">
                <h3>Reflection Journal</h3>
                <p>Write short daily reflections</p>
              </div>
            </div>
            <div class="reflection-prompts">
              <p><strong>Today's Prompt:</strong></p>
              <p id="dailyPrompt">What went well today?</p>
            </div>
            <textarea id="reflectionText" placeholder="Write your reflection here..." rows="4"></textarea><br>
            <button type="button" onclick="saveReflection()">Save Reflection</button>
            <div id="reflectionSaved" class="success-message" style="display: none;">Reflection saved!</div>
          </div>

          <!-- Personal Growth Tracker -->
          <div class="card">
            <div class="feature-header">
              <div class="feature-icon">
                <i class="fas fa-chart-line"></i>
              </div>
              <div class="feature-content">
                <h3>Personal Growth Tracker</h3>
                <p>Track personal development goals</p>
              </div>
            </div>
            <div class="growth-goals">
              <div class="goal-item">
                <span>Time Management</span>
                <div class="progress-bar">
                  <div class="progress-fill" style="width: 60%"></div>
                </div>
                <span class="progress-text">60%</span>
              </div>
              <div class="goal-item">
                <span>Confidence in Class</span>
                <div class="progress-bar">
                  <div class="progress-fill" style="width: 40%"></div>
                </div>
                <span class="progress-text">40%</span>
              </div>
              <div class="goal-item">
                <span>Study Consistency</span>
                <div class="progress-bar">
                  <div class="progress-fill" style="width: 75%"></div>
                </div>
                <span class="progress-text">75%</span>
              </div>
            </div>
            <button type="button" onclick="updateGrowthGoals()">Update Goals</button>
          </div>

          <!-- Future You Visualization -->
          <div class="card">
            <div class="feature-header">
              <div class="feature-icon">
                <i class="fas fa-rocket"></i>
              </div>
              <div class="feature-content">
                <h3>Future You Visualization</h3>
                <p>Visualize your future self and goals</p>
              </div>
            </div>
            <form id="futureQuiz">
              <p><strong>What skills do you want to develop?</strong></p>
              <input type="text" id="futureSkills" placeholder="e.g., programming, public speaking, leadership"><br><br>

              <p><strong>What career paths interest you?</strong></p>
              <input type="text" id="futureCareer" placeholder="e.g., software development, marketing, research"><br><br>

              <p><strong>What habits do you want to build?</strong></p>
              <input type="text" id="futureHabits" placeholder="e.g., daily exercise, reading, networking"><br><br>

              <button type="button" onclick="showFutureVision()">See My Future Vision</button>
            </form>
            <div id="futureResult" class="quiz-result"></div>
          </div>

        </div>
    </main>

<script>
  // Personality Discovery Quiz
  function submitPersonalityQuiz() {
    let q1 = document.querySelector('input[name="q1"]:checked');
    let q2 = document.querySelector('input[name="q2"]:checked');
    let q3 = document.querySelector('input[name="q3"]:checked');
    let result = document.getElementById('personalityResult');

    if (!q1 || !q2 || !q3) {
      result.innerHTML = '<p style="color: #ff8800;">Please answer all questions!</p>';
    } else {
      let personality = '';
      let strengths = [];
      let suggestions = [];

      if (q1.value === 'alone' && q2.value === 'reading' && q3.value === 'planner') {
        personality = 'The Structured Planner';
        strengths = ['Organized', 'Goal-driven', 'Good at managing deadlines'];
        suggestions = ['Use study schedules', 'Break tasks into smaller goals', 'Create study plans'];
      } else if (q1.value === 'others' && q2.value === 'listening' && q3.value === 'pressure') {
        personality = 'The Social Learner';
        strengths = ['Collaborative', 'Good communicator', 'Thrives under pressure'];
        suggestions = ['Join study groups', 'Participate in discussions', 'Use peer teaching'];
      } else if (q1.value === 'alone' && q2.value === 'practicing' && q3.value === 'planner') {
        personality = 'The Practical Achiever';
        strengths = ['Hands-on learner', 'Methodical', 'Self-motivated'];
        suggestions = ['Practice problems regularly', 'Apply concepts to real situations', 'Create study routines'];
      } else {
        personality = 'The Balanced Learner';
        strengths = ['Adaptable', 'Multi-talented', 'Flexible approach'];
        suggestions = ['Try different study methods', 'Mix individual and group study', 'Experiment with learning styles'];
      }

      result.innerHTML = `
        <h4>Your Study Personality: ${personality}</h4>
        <p><strong>Strengths:</strong></p>
        <ul>
          ${strengths.map(s => `<li>${s}</li>`).join('')}
        </ul>
        <p><strong>Suggestions:</strong></p>
        <ul>
          ${suggestions.map(s => `<li>${s}</li>`).join('')}
        </ul>
      `;
      result.classList.add('show');
    }
  }

  // Strengths Finder
  function submitStrengthsQuiz() {
    let activities = document.querySelectorAll('input[name="activities"]:checked');
    let subjects = document.querySelectorAll('input[name="subjects"]:checked');
    let result = document.getElementById('strengthsResult');

    if (activities.length === 0 && subjects.length === 0) {
      result.innerHTML = '<p style="color: #ff8800;">Please select at least one option!</p>';
    } else {
      let strengths = [];
      
      activities.forEach(activity => {
        switch(activity.value) {
          case 'problem-solving':
            strengths.push('Analytical thinking', 'Critical reasoning', 'Problem-solving skills');
            break;
          case 'creative-work':
            strengths.push('Creativity', 'Innovation', 'Artistic expression');
            break;
          case 'helping-others':
            strengths.push('Empathy', 'Communication', 'Team collaboration');
            break;
          case 'organizing':
            strengths.push('Organization', 'Planning', 'Time management');
            break;
        }
      });

      subjects.forEach(subject => {
        switch(subject.value) {
          case 'math':
            strengths.push('Logical reasoning', 'Quantitative analysis');
            break;
          case 'writing':
            strengths.push('Written communication', 'Storytelling', 'Critical thinking');
            break;
          case 'science':
            strengths.push('Scientific thinking', 'Research skills', 'Curiosity');
            break;
          case 'arts':
            strengths.push('Visual creativity', 'Design thinking', 'Aesthetic sense');
            break;
        }
      });

      // Remove duplicates
      strengths = [...new Set(strengths)];

      result.innerHTML = `
        <h4>Your Natural Strengths</h4>
        ${strengths.map(s => `<div class="strength-item">${s}</div>`).join('')}
        <p style="margin-top: 1rem;">These are areas where you naturally excel. Focus on developing them further!</p>
      `;
      result.classList.add('show');
    }
  }

  // Interest Explorer
  function submitInterestsQuiz() {
    let interests = document.querySelectorAll('input[name="interests"]:checked');
    let result = document.getElementById('interestsResult');

    if (interests.length === 0) {
      result.innerHTML = '<p style="color: #ff8800;">Please select at least one interest!</p>';
    } else {
      let suggestions = {};
      
      interests.forEach(interest => {
        switch(interest.value) {
          case 'technology':
            suggestions['Career Paths'] = suggestions['Career Paths'] || [];
            suggestions['Career Paths'].push('Software Development', 'Data Science', 'IT Consulting', 'Cybersecurity');
            suggestions['Skills to Learn'] = suggestions['Skills to Learn'] || [];
            suggestions['Skills to Learn'].push('Programming', 'Cloud Computing', 'AI/ML');
            break;
          case 'business':
            suggestions['Career Paths'] = suggestions['Career Paths'] || [];
            suggestions['Career Paths'].push('Marketing', 'Finance', 'Management', 'Entrepreneurship');
            suggestions['Skills to Learn'] = suggestions['Skills to Learn'] || [];
            suggestions['Skills to Learn'].push('Business Analytics', 'Leadership', 'Financial Planning');
            break;
          case 'science':
            suggestions['Career Paths'] = suggestions['Career Paths'] || [];
            suggestions['Career Paths'].push('Research', 'Medicine', 'Engineering', 'Environmental Science');
            suggestions['Skills to Learn'] = suggestions['Skills to Learn'] || [];
            suggestions['Skills to Learn'].push('Research Methods', 'Lab Techniques', 'Data Analysis');
            break;
          case 'arts':
            suggestions['Career Paths'] = suggestions['Career Paths'] || [];
            suggestions['Career Paths'].push('Graphic Design', 'Content Creation', 'UX/UI Design', 'Fine Arts');
            suggestions['Skills to Learn'] = suggestions['Skills to Learn'] || [];
            suggestions['Skills to Learn'].push('Digital Art', 'Creative Writing', 'Design Software');
            break;
          case 'social':
            suggestions['Career Paths'] = suggestions['Career Paths'] || [];
            suggestions['Career Paths'].push('Social Work', 'Education', 'Non-profit', 'Public Policy');
            suggestions['Skills to Learn'] = suggestions['Skills to Learn'] || [];
            suggestions['Skills to Learn'].push('Communication', 'Advocacy', 'Community Building');
            break;
        }
      });

      let html = '<h4>You Might Enjoy:</h4>';
      for (let category in suggestions) {
        html += `<p><strong>${category}:</strong></p><ul>`;
        suggestions[category].forEach(item => {
          html += `<li>${item}</li>`;
        });
        html += '</ul>';
      }

      result.innerHTML = html;
      result.classList.add('show');
    }
  }

  // Reflection Journal
  function saveReflection() {
    let reflectionText = document.getElementById('reflectionText').value;
    let savedMessage = document.getElementById('reflectionSaved');
    
    if (reflectionText.trim() === '') {
      alert('Please write a reflection before saving.');
      return;
    }
    
    // Simulate saving (in real app, this would save to database)
    localStorage.setItem('reflection_' + new Date().toISOString().split('T')[0], reflectionText);
    
    // Show success message
    savedMessage.style.display = 'block';
    setTimeout(() => {
      savedMessage.style.display = 'none';
    }, 3000);
    
    // Clear textarea
    document.getElementById('reflectionText').value = '';
    
    // Update daily prompt
    updateDailyPrompt();
  }

  function updateDailyPrompt() {
    const prompts = [
      'What went well today?',
      'What challenged you this week?',
      'What are you proud of today?',
      'What did you learn about yourself?',
      'What would you do differently?',
      'What are you grateful for?',
      'What progress did you make?'
    ];
    
    const randomPrompt = prompts[Math.floor(Math.random() * prompts.length)];
    document.getElementById('dailyPrompt').textContent = randomPrompt;
  }

  // Growth Tracker
  function updateGrowthGoals() {
    // Simulate updating goals (in real app, this would update based on user input)
    const goals = document.querySelectorAll('.goal-item');
    goals.forEach(goal => {
      const currentWidth = parseInt(goal.querySelector('.progress-fill').style.width);
      const newWidth = Math.min(currentWidth + 5, 100); // Increase by 5%
      goal.querySelector('.progress-fill').style.width = newWidth + '%';
      goal.querySelector('.progress-text').textContent = newWidth + '%';
    });
  }

  // Future You Visualization
  function showFutureVision() {
    let skills = document.getElementById('futureSkills').value;
    let career = document.getElementById('futureCareer').value;
    let habits = document.getElementById('futureHabits').value;
    let result = document.getElementById('futureResult');

    if (skills.trim() === '' || career.trim() === '' || habits.trim() === '') {
      result.innerHTML = '<p style="color: #ff8800;">Please fill in all fields!</p>';
    } else {
      result.innerHTML = `
        <div class="future-vision">
          <h4>Your Future Vision</h4>
          <div class="vision-item">
            <i class="fas fa-rocket"></i>
            <span><strong>Skills to Develop:</strong> ${skills}</span>
          </div>
          <div class="vision-item">
            <i class="fas fa-briefcase"></i>
            <span><strong>Career Paths to Explore:</strong> ${career}</span>
          </div>
          <div class="vision-item">
            <i class="fas fa-check-circle"></i>
            <span><strong>Habits to Build:</strong> ${habits}</span>
          </div>
          <div class="vision-item">
            <i class="fas fa-star"></i>
            <span><strong>Your Potential:</strong> Limitless! Keep working towards your goals.</span>
          </div>
        </div>
      `;
      result.classList.add('show');
    }
  }

  // Initialize daily prompt on page load
  document.addEventListener('DOMContentLoaded', function() {
    updateDailyPrompt();
  });
</script>

</body>
</html>
