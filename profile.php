<?php
$page_title = "My Profile";
require_once 'functions.php';
require_once 'db_connect.php';

redirect_if_not_logged_in();

$user_id = $_SESSION['user_id'];
$user_data = null;
$error = '';
$success = '';

// Fetch user data
$stmt = $conn->prepare("SELECT id, username, email, full_name, registration_number, phone, bio, program, year_of_study, profile_picture FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user_data = $result->fetch_assoc();
$stmt->close();

// Handle profile update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $csrf_token = $_POST['csrf_token'] ?? '';
    
    if (!verify_csrf_token($csrf_token)) {
        $error = "Security token invalid. Please try again.";
    } else {
        $full_name = sanitize_input($_POST['full_name']);
        $phone = sanitize_input($_POST['phone']);
        $bio = sanitize_input($_POST['bio']);
        $program = sanitize_input($_POST['program']);
        $year_of_study = (int)$_POST['year_of_study'];
        
        // Handle profile picture upload
        $profile_picture = $user_data['profile_picture'];
        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
            $max_size = 5 * 1024 * 1024; // 5MB
            
            if (in_array($_FILES['profile_picture']['type'], $allowed_types) && $_FILES['profile_picture']['size'] <= $max_size) {
                $upload_dir = 'uploads/profiles/';
                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }
                
                $filename = 'profile_' . $user_id . '_' . time() . '.' . pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION);
                if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $upload_dir . $filename)) {
                    $profile_picture = $upload_dir . $filename;
                } else {
                    $error = "Failed to upload profile picture.";
                }
            } else {
                $error = "Invalid file type or size. Please upload JPEG, PNG, or GIF under 5MB.";
            }
        }
        
        if (empty($error)) {
            $stmt = $conn->prepare("UPDATE users SET full_name = ?, phone = ?, bio = ?, program = ?, year_of_study = ?, profile_picture = ? WHERE id = ?");
            $stmt->bind_param("ssssisi", $full_name, $phone, $bio, $program, $year_of_study, $profile_picture, $user_id);
            
            if ($stmt->execute()) {
                $success = "Profile updated successfully!";
                // Refresh user data
                $stmt = $conn->prepare("SELECT id, username, email, full_name, registration_number, phone, bio, program, year_of_study, profile_picture FROM users WHERE id = ?");
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $result = $stmt->get_result();
                $user_data = $result->fetch_assoc();
            } else {
                $error = "Failed to update profile. Please try again.";
            }
            $stmt->close();
        }
    }
}
?>

<?php require_once 'components/header.php'; ?>

<main class="container" style="margin-top: 120px; min-height: calc(100vh - 200px);">
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Profile Card -->
    <div class="lg:col-span-1">
      <div class="card">
        <div class="text-center">
          <?php if ($user_data['profile_picture']): ?>
            <img src="<?php echo htmlspecialchars($user_data['profile_picture']); ?>" alt="Profile" class="w-32 h-32 rounded-full mx-auto mb-4 object-cover">
          <?php else: ?>
            <div class="w-32 h-32 bg-gray-600 rounded-full mx-auto mb-4 flex items-center justify-center">
              <span class="text-3xl font-bold"><?php echo strtoupper(substr($user_data['full_name'], 0, 1)); ?></span>
            </div>
          <?php endif; ?>
          
          <h2 class="font-bold text-2xl mb-2"><?php echo htmlspecialchars($user_data['full_name']); ?></h2>
          <p class="text-primary mb-1">@<?php echo htmlspecialchars($user_data['username']); ?></p>
          <p class="text-secondary text-sm mb-4"><?php echo htmlspecialchars($user_data['registration_number']); ?></p>
          
          <div class="text-left space-y-2">
            <div>
              <span class="text-muted text-sm">Program:</span>
              <p class="font-semibold"><?php echo htmlspecialchars($user_data['program'] ?? 'Not specified'); ?></p>
            </div>
            <div>
              <span class="text-muted text-sm">Year of Study:</span>
              <p class="font-semibold">Year <?php echo $user_data['year_of_study']; ?></p>
            </div>
            <?php if ($user_data['phone']): ?>
            <div>
              <span class="text-muted text-sm">Phone:</span>
              <p class="font-semibold"><?php echo htmlspecialchars($user_data['phone']); ?></p>
            </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Edit Profile Form -->
    <div class="lg:col-span-2">
      <div class="card">
        <h3 class="font-bold text-xl mb-6">Edit Profile</h3>
        
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
        
        <form action="profile.php" method="POST" enctype="multipart/form-data">
          <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="form-group">
              <label for="full_name" class="form-label">Full Name</label>
              <input type="text" id="full_name" name="full_name" class="form-input" 
                     value="<?php echo htmlspecialchars($user_data['full_name']); ?>" required>
            </div>
            
            <div class="form-group">
              <label for="phone" class="form-label">Phone Number</label>
              <input type="tel" id="phone" name="phone" class="form-input" 
                     value="<?php echo htmlspecialchars($user_data['phone'] ?? ''); ?>">
            </div>
            
            <div class="form-group">
              <label for="program" class="form-label">Program of Study</label>
              <input type="text" id="program" name="program" class="form-input" 
                     value="<?php echo htmlspecialchars($user_data['program'] ?? ''); ?>">
            </div>
            
            <div class="form-group">
              <label for="year_of_study" class="form-label">Year of Study</label>
              <select id="year_of_study" name="year_of_study" class="form-input">
                <option value="1" <?php echo $user_data['year_of_study'] == 1 ? 'selected' : ''; ?>>Year 1</option>
                <option value="2" <?php echo $user_data['year_of_study'] == 2 ? 'selected' : ''; ?>>Year 2</option>
                <option value="3" <?php echo $user_data['year_of_study'] == 3 ? 'selected' : ''; ?>>Year 3</option>
                <option value="4" <?php echo $user_data['year_of_study'] == 4 ? 'selected' : ''; ?>>Year 4</option>
                <option value="5" <?php echo $user_data['year_of_study'] == 5 ? 'selected' : ''; ?>>Year 5</option>
              </select>
            </div>
          </div>
          
          <div class="form-group">
            <label for="bio" class="form-label">Bio</label>
            <textarea id="bio" name="bio" class="form-input" rows="4" 
                      placeholder="Tell us about yourself..."><?php echo htmlspecialchars($user_data['bio'] ?? ''); ?></textarea>
          </div>
          
          <div class="form-group">
            <label for="profile_picture" class="form-label">Profile Picture</label>
            <input type="file" id="profile_picture" name="profile_picture" class="form-input" accept="image/*">
            <small class="text-muted">JPEG, PNG, or GIF. Maximum size: 5MB</small>
          </div>
          
          <button type="submit" class="btn btn-primary">Update Profile</button>
        </form>
      </div>
      
      <!-- Quick Actions -->
      <div class="card mt-6">
        <h3 class="font-bold text-xl mb-4">Quick Actions</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <a href="my_bookings.php" class="btn btn-outline">My Bookings</a>
          <a href="my_checklists.php" class="btn btn-outline">My Checklists</a>
          <a href="search.php" class="btn btn-outline">Search Services</a>
          <a href="logout.php" class="btn btn-secondary">Logout</a>
        </div>
      </div>
    </div>
  </div>
</main>

<?php require_once 'components/footer.php'; ?>
