<?php
$page_title = "Company Portal";
require_once 'functions.php';
require_once 'db_connect.php';

redirect_if_not_logged_in();

$user_id = $_SESSION['user_id'];
$company = null;
$opportunities = [];
$error = '';
$success = '';

// Check if user is a company representative
$stmt = $conn->prepare("SELECT * FROM companies WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$company = $result->fetch_assoc();
$stmt->close();

if (!$company) {
    header('Location: Home.php');
    exit;
}

// Handle new opportunity submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_opportunity'])) {
    $csrf_token = $_POST['csrf_token'] ?? '';
    
    if (!verify_csrf_token($csrf_token)) {
        $error = "Security token invalid. Please try again.";
    } else {
        $title = sanitize_input($_POST['title']);
        $description = sanitize_input($_POST['description']);
        $opportunity_type = sanitize_input($_POST['opportunity_type']);
        $department = sanitize_input($_POST['department']);
        $requirements = sanitize_input($_POST['requirements']);
        $application_deadline = $_POST['application_deadline'];
        $salary_range = sanitize_input($_POST['salary_range']);
        $location = sanitize_input($_POST['location']);
        $is_remote = isset($_POST['is_remote']) ? 1 : 0;
        
        if (!empty($title) && !empty($description) && !empty($application_deadline)) {
            $stmt = $conn->prepare("
                INSERT INTO opportunities (company_id, title, description, opportunity_type, department, 
                requirements, application_deadline, salary_range, location, is_remote, created_by) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->bind_param("isssssssssi", $company['id'], $title, $description, $opportunity_type, 
                              $department, $requirements, $application_deadline, $salary_range, 
                              $location, $is_remote, $user_id);
            
            if ($stmt->execute()) {
                $success = "Opportunity posted successfully!";
                
                // Notify interested students
                $notif_stmt = $conn->prepare("
                    INSERT INTO notifications (user_id, title, message, type) 
                    SELECT DISTINCT u.id, ?, ?, 'info' 
                    FROM users u 
                    WHERE u.user_type = 'student' AND u.is_active = 1
                    LIMIT 100
                ");
                $notif_title = "New Opportunity: " . $title . " at " . $company['name'];
                $notif_message = substr($description, 0, 100) . "...";
                $notif_stmt->bind_param("ss", $notif_title, $notif_message);
                $notif_stmt->execute();
                $notif_stmt->close();
            } else {
                $error = "Failed to post opportunity. Please try again.";
            }
            $stmt->close();
        } else {
            $error = "Title, description, and deadline are required.";
        }
    }
}

// Handle opportunity deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_opportunity'])) {
    $csrf_token = $_POST['csrf_token'] ?? '';
    $opportunity_id = (int)$_POST['opportunity_id'];
    
    if (verify_csrf_token($csrf_token)) {
        // Verify ownership
        $stmt = $conn->prepare("
            DELETE o FROM opportunities o 
            JOIN companies c ON o.company_id = c.id 
            WHERE o.id = ? AND c.user_id = ?
        ");
        $stmt->bind_param("ii", $opportunity_id, $user_id);
        $stmt->execute();
        $stmt->close();
        
        $success = "Opportunity deleted successfully!";
    }
}

// Fetch company opportunities
$stmt = $conn->prepare("
    SELECT * FROM opportunities 
    WHERE company_id = ? 
    ORDER BY created_at DESC
");
$stmt->bind_param("i", $company['id']);
$stmt->execute();
$result = $stmt->get_result();
$opportunities = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<?php require_once 'components/header.php'; ?>

<main class="container" style="margin-top: 120px; min-height: calc(100vh - 200px);">
  <div class="mb-6">
    <h1 class="font-bold text-3xl mb-2">Company Portal</h1>
    <p class="text-secondary">Manage opportunities and connect with talented students</p>
  </div>

  <!-- Company Profile Card -->
  <div class="card mb-6">
    <div class="flex items-center gap-6">
      <?php if ($company['logo']): ?>
        <img src="<?php echo htmlspecialchars($company['logo']); ?>" alt="<?php echo htmlspecialchars($company['name']); ?>" 
             class="w-20 h-20 rounded-lg object-cover">
      <?php else: ?>
        <div class="w-20 h-20 bg-gray-700 rounded-lg flex items-center justify-center">
          <span class="text-2xl font-bold text-primary"><?php echo strtoupper(substr($company['name'], 0, 2)); ?></span>
        </div>
      <?php endif; ?>
      
      <div class="flex-1">
        <h2 class="font-bold text-2xl mb-2"><?php echo htmlspecialchars($company['name']); ?></h2>
        <p class="text-secondary mb-2"><?php echo htmlspecialchars($company['description']); ?></p>
        <div class="flex flex-wrap gap-4 text-sm">
          <span class="text-muted">📍 <?php echo htmlspecialchars($company['location']); ?></span>
          <span class="text-muted">🌐 <?php echo htmlspecialchars($company['website']); ?></span>
          <span class="text-muted">📧 <?php echo htmlspecialchars($company['contact_email']); ?></span>
        </div>
      </div>
      
      <div class="text-right">
        <div class="text-3xl font-bold text-primary mb-1"><?php echo count($opportunities); ?></div>
        <div class="text-sm text-muted">Active Opportunities</div>
      </div>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Left Column - New Opportunity Form -->
    <div class="lg:col-span-1">
      <div class="card">
        <h3 class="font-bold text-xl mb-4">Post New Opportunity</h3>
        
        <?php if ($error): ?>
          <div class="text-error text-center mb-4 p-3" style="background: rgba(239, 68, 68, 0.1); border-radius: 8px;">
            <?php echo $error; ?>
          </div>
        <?php endif; ?>
        
        <?php if ($success): ?>
          <div class="text-success text-center mb-4 p-3" style="background: rgba(16, 185, 129, 0.1); border-radius: 8px;">
            <?php echo $success; ?>
          </div>
        <?php endif; ?>

        <form action="company_portal.php" method="POST">
          <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
          
          <div class="form-group">
            <label for="title" class="form-label">Opportunity Title</label>
            <input type="text" id="title" name="title" class="form-input" 
                   placeholder="e.g., Software Engineering Internship" required>
          </div>
          
          <div class="form-group">
            <label for="opportunity_type" class="form-label">Opportunity Type</label>
            <select id="opportunity_type" name="opportunity_type" class="form-input" required>
              <option value="">Select Type</option>
              <option value="internship">Internship</option>
              <option value="part_time">Part-time Job</option>
              <option value="full_time">Full-time Job</option>
              <option value="volunteer">Volunteer Work</option>
              <option value="scholarship">Scholarship</option>
              <option value="training">Training/Workshop</option>
              <option value="competition">Competition</option>
              <option value="other">Other</option>
            </select>
          </div>
          
          <div class="form-group">
            <label for="department" class="form-label">Department/Field</label>
            <input type="text" id="department" name="department" class="form-input" 
                   placeholder="e.g., IT, Engineering, Marketing, Finance">
          </div>
          
          <div class="form-group">
            <label for="location" class="form-label">Location</label>
            <input type="text" id="location" name="location" class="form-input" 
                   placeholder="e.g., Blantyre, Lilongwe, Remote">
          </div>
          
          <div class="form-group">
            <label for="salary_range" class="form-label">Salary/Stipend Range</label>
            <input type="text" id="salary_range" name="salary_range" class="form-input" 
                   placeholder="e.g., MWK 50,000 - 100,000, Unpaid, Commission-based">
          </div>
          
          <div class="form-group">
            <label for="application_deadline" class="form-label">Application Deadline</label>
            <input type="date" id="application_deadline" name="application_deadline" class="form-input" required>
          </div>
          
          <div class="form-group">
            <label class="flex items-center gap-2">
              <input type="checkbox" name="is_remote" class="form-checkbox">
              <span>Remote Opportunity</span>
            </label>
          </div>
          
          <div class="form-group">
            <label for="description" class="form-label">Description</label>
            <textarea id="description" name="description" class="form-input" rows="4" 
                      placeholder="Describe the opportunity, responsibilities, and what students will learn..." required></textarea>
          </div>
          
          <div class="form-group">
            <label for="requirements" class="form-label">Requirements</label>
            <textarea id="requirements" name="requirements" class="form-input" rows="3" 
                      placeholder="List the skills, qualifications, or experience needed..."></textarea>
          </div>
          
          <button type="submit" name="add_opportunity" class="btn btn-primary w-full">Post Opportunity</button>
        </form>
      </div>
    </div>

    <!-- Right Column - Opportunities List -->
    <div class="lg:col-span-2">
      <div class="card">
        <h3 class="font-bold text-xl mb-6">Your Opportunities</h3>
        
        <?php if (empty($opportunities)): ?>
          <div class="text-center text-muted py-8">
            <div class="text-4xl mb-4">💼</div>
            <p>No opportunities posted yet.</p>
            <p class="text-sm">Use the form to post your first opportunity!</p>
          </div>
        <?php else: ?>
          <div class="space-y-4">
            <?php foreach ($opportunities as $opportunity): ?>
              <div class="border border-gray-700 rounded-lg p-4">
                <div class="flex justify-between items-start mb-2">
                  <div>
                    <h4 class="font-semibold text-lg"><?php echo htmlspecialchars($opportunity['title']); ?></h4>
                    <div class="flex flex-wrap gap-2 mt-1">
                      <span class="text-xs px-2 py-1 rounded bg-primary text-primary-dark">
                        <?php echo ucfirst(str_replace('_', ' ', $opportunity['opportunity_type'])); ?>
                      </span>
                      <?php if ($opportunity['is_remote']): ?>
                        <span class="text-xs px-2 py-1 rounded bg-green-600 text-white">🏠 Remote</span>
                      <?php endif; ?>
                      <?php if ($opportunity['department']): ?>
                        <span class="text-xs px-2 py-1 rounded bg-gray-600 text-white">
                          <?php echo htmlspecialchars($opportunity['department']); ?>
                        </span>
                      <?php endif; ?>
                    </div>
                  </div>
                  <div class="flex gap-2">
                    <form action="company_portal.php" method="POST" 
                          onsubmit="return confirm('Are you sure you want to delete this opportunity?');">
                      <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                      <input type="hidden" name="opportunity_id" value="<?php echo $opportunity['id']; ?>">
                      <button type="submit" name="delete_opportunity" class="text-error hover:text-red-400">
                        🗑️
                      </button>
                    </form>
                  </div>
                </div>
                
                <p class="text-secondary mb-3"><?php echo nl2br(htmlspecialchars(substr($opportunity['description'], 0, 200))) . '...'; ?></p>
                
                <?php if ($opportunity['requirements']): ?>
                <div class="mb-3">
                  <span class="text-sm font-semibold text-primary">Requirements:</span>
                  <p class="text-sm text-secondary"><?php echo nl2br(htmlspecialchars($opportunity['requirements'])); ?></p>
                </div>
                <?php endif; ?>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                  <?php if ($opportunity['location']): ?>
                  <div>
                    <span class="text-muted">📍 Location:</span>
                    <span class="text-secondary"><?php echo htmlspecialchars($opportunity['location']); ?></span>
                  </div>
                  <?php endif; ?>
                  
                  <?php if ($opportunity['salary_range']): ?>
                  <div>
                    <span class="text-muted">💰 Salary:</span>
                    <span class="text-secondary"><?php echo htmlspecialchars($opportunity['salary_range']); ?></span>
                  </div>
                  <?php endif; ?>
                  
                  <div>
                    <span class="text-muted">📅 Deadline:</span>
                    <span class="text-secondary <?php echo (strtotime($opportunity['application_deadline']) < time()) ? 'text-error' : ''; ?>">
                      <?php echo date('M j, Y', strtotime($opportunity['application_deadline'])); ?>
                    </span>
                  </div>
                </div>
                
                <div class="mt-3 pt-3 border-t border-gray-700 flex justify-between items-center">
                  <div class="text-xs text-muted">
                    Posted on <?php echo date('M j, Y \a\t g:i A', strtotime($opportunity['created_at'])); ?>
                  </div>
                  <div class="text-xs text-muted">
                    <?php
                    // Count applications (you'd need to implement applications table)
                    $app_stmt = $conn->prepare("SELECT COUNT(*) as count FROM applications WHERE opportunity_id = ?");
                    $app_stmt->bind_param("i", $opportunity['id']);
                    $app_stmt->execute();
                    $app_result = $app_stmt->get_result();
                    $app_count = $app_result->fetch_assoc()['count'];
                    $app_stmt->close();
                    echo $app_count . ' applications';
                    ?>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- Quick Actions -->
  <div class="card mt-6">
    <h3 class="font-bold text-xl mb-4">Quick Actions</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <a href="view_applications.php" class="btn btn-outline">
        📋 View Applications
      </a>
      <a href="edit_company_profile.php" class="btn btn-outline">
        ✏️ Edit Company Profile
      </a>
      <a href="opportunities.php" class="btn btn-outline">
        👁️ Public View
      </a>
    </div>
  </div>
</main>

<?php require_once 'components/footer.php'; ?>
