<?php
$page_title = "Landlord Portal";
require_once 'functions.php';
require_once 'db_connect.php';

redirect_if_not_logged_in();

$user_id = $_SESSION['user_id'];
$accommodations = [];
$selected_accommodation = null;
$announcements = [];

// Check if user is a landlord (owns accommodations)
$stmt = $conn->prepare("SELECT * FROM accommodations WHERE owner_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$accommodations = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

if (empty($accommodations)) {
    // User is not a landlord, redirect
    header('Location: Home.php');
    exit;
}

// Handle accommodation selection
$selected_id = $_GET['accommodation'] ?? $accommodations[0]['id'];
foreach ($accommodations as $acc) {
    if ($acc['id'] == $selected_id) {
        $selected_accommodation = $acc;
        break;
    }
}

// Handle new announcement submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_announcement'])) {
    $csrf_token = $_POST['csrf_token'] ?? '';
    
    if (!verify_csrf_token($csrf_token)) {
        $error = "Security token invalid. Please try again.";
    } else {
        $title = sanitize_input($_POST['title']);
        $content = sanitize_input($_POST['content']);
        $priority = sanitize_input($_POST['priority']);
        $accommodation_id = (int)$_POST['accommodation_id'];
        
        if (!empty($title) && !empty($content)) {
            $stmt = $conn->prepare("
                INSERT INTO announcements (accommodation_id, title, content, priority, created_by) 
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmt->bind_param("isssi", $accommodation_id, $title, $content, $priority, $user_id);
            
            if ($stmt->execute()) {
                $success = "Announcement posted successfully!";
                
                // Notify tenants
                $notif_stmt = $conn->prepare("
                    INSERT INTO notifications (user_id, title, message, type) 
                    SELECT b.user_id, ?, ?, 'info' 
                    FROM accommodation_bookings b 
                    WHERE b.accommodation_id = ? AND b.status = 'confirmed'
                ");
                $notif_title = "New Announcement: " . $title;
                $notif_message = substr($content, 0, 100) . "...";
                $notif_stmt->bind_param("ssi", $notif_title, $notif_message, $accommodation_id);
                $notif_stmt->execute();
                $notif_stmt->close();
            } else {
                $error = "Failed to post announcement. Please try again.";
            }
            $stmt->close();
        } else {
            $error = "Title and content are required.";
        }
    }
}

// Handle announcement deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_announcement'])) {
    $csrf_token = $_POST['csrf_token'] ?? '';
    $announcement_id = (int)$_POST['announcement_id'];
    
    if (verify_csrf_token($csrf_token)) {
        // Verify ownership
        $stmt = $conn->prepare("
            DELETE a FROM announcements a 
            JOIN accommodations acc ON a.accommodation_id = acc.id 
            WHERE a.id = ? AND acc.owner_id = ?
        ");
        $stmt->bind_param("ii", $announcement_id, $user_id);
        $stmt->execute();
        $stmt->close();
        
        $success = "Announcement deleted successfully!";
    }
}

// Fetch announcements for selected accommodation
if ($selected_accommodation) {
    $stmt = $conn->prepare("
        SELECT * FROM announcements 
        WHERE accommodation_id = ? 
        ORDER BY created_at DESC
    ");
    $stmt->bind_param("i", $selected_accommodation['id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $announcements = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}
?>

<?php require_once 'components/header.php'; ?>

<main class="container" style="margin-top: 120px; min-height: calc(100vh - 200px);">
  <div class="mb-6">
    <h1 class="font-bold text-3xl mb-2">Landlord Portal</h1>
    <p class="text-secondary">Manage your accommodations and communicate with tenants</p>
  </div>

  <!-- Accommodation Selector -->
  <div class="card mb-6">
    <div class="flex flex-wrap gap-4 items-center">
      <span class="font-semibold">Select Accommodation:</span>
      <div class="flex flex-wrap gap-2">
        <?php foreach ($accommodations as $acc): ?>
          <a href="?accommodation=<?php echo $acc['id']; ?>" 
             class="btn <?php echo $selected_accommodation['id'] == $acc['id'] ? 'btn-primary' : 'btn-outline'; ?>">
            <?php echo htmlspecialchars($acc['name']); ?>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <?php if ($selected_accommodation): ?>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      <!-- Left Column - Stats & New Announcement -->
      <div class="lg:col-span-1 space-y-6">
        <!-- Accommodation Stats -->
        <div class="card">
          <h3 class="font-bold text-xl mb-4"><?php echo htmlspecialchars($selected_accommodation['name']); ?></h3>
          <div class="space-y-3">
            <div class="flex justify-between">
              <span class="text-secondary">Total Capacity:</span>
              <span class="font-semibold"><?php echo $selected_accommodation['capacity']; ?></span>
            </div>
            <div class="flex justify-between">
              <span class="text-secondary">Available Spaces:</span>
              <span class="font-semibold text-success"><?php echo $selected_accommodation['available_spaces']; ?></span>
            </div>
            <div class="flex justify-between">
              <span class="text-secondary">Monthly Rate:</span>
              <span class="font-semibold">MWK <?php echo number_format($selected_accommodation['price_per_month']); ?></span>
            </div>
            <div class="flex justify-between">
              <span class="text-secondary">Rating:</span>
              <span class="font-semibold">⭐ <?php echo $selected_accommodation['rating'] ?: 'N/A'; ?></span>
            </div>
          </div>
        </div>

        <!-- New Announcement Form -->
        <div class="card">
          <h3 class="font-bold text-xl mb-4">Post New Announcement</h3>
          
          <?php if (isset($error)): ?>
            <div class="text-error text-center mb-4 p-3" style="background: rgba(239, 68, 68, 0.1); border-radius: 8px;">
              <?php echo $error; ?>
            </div>
          <?php endif; ?>
          
          <?php if (isset($success)): ?>
            <div class="text-success text-center mb-4 p-3" style="background: rgba(16, 185, 129, 0.1); border-radius: 8px;">
              <?php echo $success; ?>
            </div>
          <?php endif; ?>

          <form action="landlord_portal.php?accommodation=<?php echo $selected_accommodation['id']; ?>" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
            <input type="hidden" name="accommodation_id" value="<?php echo $selected_accommodation['id']; ?>">
            
            <div class="form-group">
              <label for="title" class="form-label">Announcement Title</label>
              <input type="text" id="title" name="title" class="form-input" 
                     placeholder="e.g., Maintenance Notice, Rent Reminder" required>
            </div>
            
            <div class="form-group">
              <label for="priority" class="form-label">Priority</label>
              <select id="priority" name="priority" class="form-input">
                <option value="normal">Normal</option>
                <option value="important">Important</option>
                <option value="urgent">Urgent</option>
              </select>
            </div>
            
            <div class="form-group">
              <label for="content" class="form-label">Message</label>
              <textarea id="content" name="content" class="form-input" rows="4" 
                        placeholder="Enter your announcement details..." required></textarea>
            </div>
            
            <button type="submit" name="add_announcement" class="btn btn-primary w-full">Post Announcement</button>
          </form>
        </div>
      </div>

      <!-- Right Column - Announcements List -->
      <div class="lg:col-span-2">
        <div class="card">
          <h3 class="font-bold text-xl mb-6">Recent Announcements</h3>
          
          <?php if (empty($announcements)): ?>
            <div class="text-center text-muted py-8">
              <div class="text-4xl mb-4">📢</div>
              <p>No announcements posted yet.</p>
              <p class="text-sm">Use the form to post your first announcement!</p>
            </div>
          <?php else: ?>
            <div class="space-y-4">
              <?php foreach ($announcements as $announcement): ?>
                <div class="border border-gray-700 rounded-lg p-4" 
                     style="border-left: 4px solid <?php 
                     echo $announcement['priority'] === 'urgent' ? 'var(--error)' : 
                          ($announcement['priority'] === 'important' ? 'var(--warning)' : 'var(--primary-blue)'); ?>;">
                  
                  <div class="flex justify-between items-start mb-2">
                    <h4 class="font-semibold text-lg"><?php echo htmlspecialchars($announcement['title']); ?></h4>
                    <div class="flex gap-2">
                      <span class="text-xs px-2 py-1 rounded" 
                            style="background: <?php 
                            echo $announcement['priority'] === 'urgent' ? 'rgba(239, 68, 68, 0.2)' : 
                                 ($announcement['priority'] === 'important' ? 'rgba(245, 158, 11, 0.2)' : 'rgba(84, 131, 179, 0.2)'); ?>;
                            color: <?php 
                            echo $announcement['priority'] === 'urgent' ? 'var(--error)' : 
                                 ($announcement['priority'] === 'important' ? 'var(--warning)' : 'var(--primary-blue)'); ?>;">
                        <?php echo ucfirst($announcement['priority']); ?>
                      </span>
                      <form action="landlord_portal.php?accommodation=<?php echo $selected_accommodation['id']; ?>" 
                            method="POST" onsubmit="return confirm('Are you sure you want to delete this announcement?');">
                        <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                        <input type="hidden" name="announcement_id" value="<?php echo $announcement['id']; ?>">
                        <button type="submit" name="delete_announcement" class="text-error hover:text-red-400">
                          🗑️
                        </button>
                      </form>
                    </div>
                  </div>
                  
                  <p class="text-secondary mb-3"><?php echo nl2br(htmlspecialchars($announcement['content'])); ?></p>
                  
                  <div class="text-xs text-muted">
                    Posted on <?php echo date('M j, Y \a\t g:i A', strtotime($announcement['created_at'])); ?>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  <?php endif; ?>

  <!-- Quick Actions -->
  <div class="card mt-6">
    <h3 class="font-bold text-xl mb-4">Quick Actions</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <a href="view_bookings.php?accommodation=<?php echo $selected_accommodation['id']; ?>" class="btn btn-outline">
        📋 View Bookings
      </a>
      <a href="edit_accommodation.php?id=<?php echo $selected_accommodation['id']; ?>" class="btn btn-outline">
        ✏️ Edit Accommodation
      </a>
      <a href="profile.php" class="btn btn-outline">
        👤 My Profile
      </a>
    </div>
  </div>
</main>

<?php require_once 'components/footer.php'; ?>
