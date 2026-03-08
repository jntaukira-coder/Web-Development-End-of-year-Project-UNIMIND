<?php
$page_title = "Accommodation Details";
require_once 'functions.php';
require_once 'db_connect.php';

$accommodation_id = $_GET['id'] ?? 0;
$accommodation = null;
$announcements = [];
$reviews = [];

// Fetch accommodation details with owner info
if ($accommodation_id) {
    $stmt = $conn->prepare("
        SELECT a.*, u.full_name as owner_name, u.phone as owner_phone, u.email as owner_email,
               (SELECT COUNT(*) FROM accommodation_bookings WHERE accommodation_id = a.id AND status != 'cancelled') as current_bookings 
        FROM accommodations a 
        LEFT JOIN users u ON a.owner_id = u.id
        WHERE a.id = ? AND a.verified = TRUE
    ");
    $stmt->bind_param("i", $accommodation_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $accommodation = $result->fetch_assoc();
    $stmt->close();
    
    if ($accommodation) {
        // Fetch announcements for this accommodation
        $stmt = $conn->prepare("
            SELECT * FROM announcements 
            WHERE accommodation_id = ? 
            ORDER BY priority DESC, created_at DESC
            LIMIT 5
        ");
        $stmt->bind_param("i", $accommodation_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $announcements = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        
        // Fetch reviews
        $stmt = $conn->prepare("
            SELECT r.*, u.full_name, u.username 
            FROM reviews r 
            JOIN users u ON r.user_id = u.id 
            WHERE r.target_id = ? AND r.target_type = 'accommodation'
            ORDER BY r.created_at DESC
            LIMIT 10
        ");
        $stmt->bind_param("i", $accommodation_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $reviews = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    }
}

if (!$accommodation) {
    header('Location: search.php');
    exit;
}
?>

<?php require_once 'components/header.php'; ?>

<main class="container" style="margin-top: 120px; min-height: calc(100vh - 200px);">
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Main Content -->
    <div class="lg:col-span-2">
      <div class="card mb-6">
        <h1 class="font-bold text-3xl mb-4"><?php echo htmlspecialchars($accommodation['name']); ?></h1>
        
        <!-- Image Gallery -->
        <?php if ($accommodation['photos']): ?>
          <div class="mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <?php 
              $photos = json_decode($accommodation['photos'], true) ?: [];
              foreach ($photos as $photo): 
              ?>
                <img src="<?php echo htmlspecialchars($photo); ?>" 
                     alt="<?php echo htmlspecialchars($accommodation['name']); ?>" 
                     class="w-full h-48 object-cover rounded-lg">
              <?php endforeach; ?>
            </div>
          </div>
        <?php endif; ?>
        
        <!-- Quick Info -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
          <div class="text-center p-3 bg-gray-800 rounded-lg">
            <div class="text-2xl font-bold text-primary">MWK <?php echo number_format($accommodation['price_per_month']); ?></div>
            <div class="text-sm text-muted">per month</div>
          </div>
          <div class="text-center p-3 bg-gray-800 rounded-lg">
            <div class="text-2xl font-bold text-success"><?php echo $accommodation['available_spaces']; ?></div>
            <div class="text-sm text-muted">spaces available</div>
          </div>
          <div class="text-center p-3 bg-gray-800 rounded-lg">
            <div class="text-2xl font-bold"><?php echo $accommodation['capacity']; ?></div>
            <div class="text-sm text-muted">total capacity</div>
          </div>
          <div class="text-center p-3 bg-gray-800 rounded-lg">
            <div class="text-2xl font-bold">⭐ <?php echo $accommodation['rating'] ?: 'N/A'; ?></div>
            <div class="text-sm text-muted">rating</div>
          </div>
        </div>
        
        <!-- Description -->
        <div class="mb-6">
          <h3 class="font-bold text-xl mb-3">About This Accommodation</h3>
          <p class="text-secondary"><?php echo nl2br(htmlspecialchars($accommodation['description'])); ?></p>
        </div>
        
        <!-- Amenities -->
        <?php if ($accommodation['amenities']): ?>
        <div class="mb-6">
          <h3 class="font-bold text-xl mb-3">Amenities</h3>
          <div class="text-secondary">
            <?php 
            $amenities = explode(',', $accommodation['amenities']);
            foreach ($amenities as $amenity) {
              echo '<span class="inline-block bg-gray-700 text-white px-3 py-1 rounded-full text-sm mr-2 mb-2">' . htmlspecialchars(trim($amenity)) . '</span>';
            }
            ?>
          </div>
        </div>
        <?php endif; ?>
        
        <!-- Contact Information -->
        <div class="mb-6">
          <h3 class="font-bold text-xl mb-3">Contact Information</h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <span class="text-primary font-semibold">👤 Owner:</span>
              <p class="text-secondary"><?php echo htmlspecialchars($accommodation['owner_name'] ?: $accommodation['contact_person']); ?></p>
            </div>
            <div>
              <span class="text-primary font-semibold">📞 Phone:</span>
              <p class="text-secondary"><?php echo htmlspecialchars($accommodation['owner_phone'] ?: $accommodation['contact_phone']); ?></p>
            </div>
            <div>
              <span class="text-primary font-semibold">📧 Email:</span>
              <p class="text-secondary"><?php echo htmlspecialchars($accommodation['owner_email'] ?: $accommodation['contact_email']); ?></p>
            </div>
            <div>
              <span class="text-primary font-semibold">📍 Address:</span>
              <p class="text-secondary"><?php echo htmlspecialchars($accommodation['address']); ?></p>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Announcements Section -->
      <?php if (!empty($announcements)): ?>
      <div class="card mb-6">
        <h3 class="font-bold text-xl mb-4">📢 Latest Announcements</h3>
        <div class="space-y-4">
          <?php foreach ($announcements as $announcement): ?>
            <div class="border-l-4 pl-4 py-2" 
                 style="border-color: <?php 
                 echo $announcement['priority'] === 'urgent' ? 'var(--error)' : 
                      ($announcement['priority'] === 'important' ? 'var(--warning)' : 'var(--primary-blue)'); ?>;">
              <div class="flex justify-between items-start mb-1">
                <h4 class="font-semibold"><?php echo htmlspecialchars($announcement['title']); ?></h4>
                <span class="text-xs px-2 py-1 rounded" 
                      style="background: <?php 
                      echo $announcement['priority'] === 'urgent' ? 'rgba(239, 68, 68, 0.2)' : 
                           ($announcement['priority'] === 'important' ? 'rgba(245, 158, 11, 0.2)' : 'rgba(84, 131, 179, 0.2)'); ?>;
                      color: <?php 
                      echo $announcement['priority'] === 'urgent' ? 'var(--error)' : 
                           ($announcement['priority'] === 'important' ? 'var(--warning)' : 'var(--primary-blue)'); ?>;">
                  <?php echo ucfirst($announcement['priority']); ?>
                </span>
              </div>
              <p class="text-secondary text-sm mb-1"><?php echo nl2br(htmlspecialchars($announcement['content'])); ?></p>
              <div class="text-xs text-muted">
                Posted <?php echo date('M j, Y', strtotime($announcement['created_at'])); ?>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>
      
      <!-- Reviews Section -->
      <?php if (!empty($reviews)): ?>
      <div class="card">
        <h3 class="font-bold text-xl mb-4">⭐ Reviews</h3>
        <div class="space-y-4">
          <?php foreach ($reviews as $review): ?>
            <div class="border-b border-gray-700 pb-4 last:border-b-0">
              <div class="flex justify-between items-start mb-2">
                <div>
                  <div class="font-semibold"><?php echo htmlspecialchars($review['full_name']); ?></div>
                  <div class="text-yellow-400 text-sm">
                    <?php echo str_repeat('⭐', $review['rating']); ?>
                  </div>
                </div>
                <div class="text-xs text-muted">
                  <?php echo date('M j, Y', strtotime($review['created_at'])); ?>
                </div>
              </div>
              <p class="text-secondary text-sm"><?php echo nl2br(htmlspecialchars($review['review_text'])); ?></p>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>
    </div>
    
    <!-- Sidebar -->
    <div class="lg:col-span-1">
      <!-- Booking Card -->
      <div class="card">
        <h3 class="font-bold text-xl mb-4">Book This Accommodation</h3>
        
        <?php if ($accommodation['available_spaces'] > 0): ?>
          <div class="mb-4">
            <div class="text-2xl font-bold text-primary mb-2">MWK <?php echo number_format($accommodation['price_per_month']); ?></div>
            <div class="text-secondary text-sm mb-4">per month</div>
            
            <div class="space-y-3 mb-4">
              <div class="flex justify-between text-sm">
                <span class="text-muted">Available Spaces:</span>
                <span class="font-semibold text-success"><?php echo $accommodation['available_spaces']; ?></span>
              </div>
              <div class="flex justify-between text-sm">
                <span class="text-muted">Total Capacity:</span>
                <span class="font-semibold"><?php echo $accommodation['capacity']; ?></span>
              </div>
            </div>
          </div>
          
          <a href="booking.php?id=<?php echo $accommodation_id; ?>" class="btn btn-primary w-full mb-3">
            Book Now
          </a>
          <a href="#" onclick="window.print()" class="btn btn-outline w-full">
            🖨️ Print Details
          </a>
        <?php else: ?>
          <div class="text-center">
            <div class="text-error mb-4">❌ Fully Booked</div>
            <p class="text-secondary mb-4">This accommodation is currently at full capacity.</p>
            <a href="search.php" class="btn btn-outline">Find Other Options</a>
          </div>
        <?php endif; ?>
      </div>
      
      <!-- Safety Notice -->
      <div class="card mt-6">
        <h3 class="font-bold text-lg mb-3">🛡️ Safety Tips</h3>
        <ul class="text-secondary text-sm space-y-2">
          <li>• Always visit before booking</li>
          <li>• Verify the landlord's identity</li>
          <li>• Read agreements carefully</li>
          <li>• Use secure payment methods</li>
          <li>• Keep payment records</li>
        </ul>
      </div>
      
      <!-- Quick Actions -->
      <div class="card mt-6">
        <h3 class="font-bold text-lg mb-3">Quick Actions</h3>
        <div class="space-y-2">
          <a href="search.php" class="btn btn-outline btn-sm w-full">← Back to Search</a>
          <?php if (is_logged_in()): ?>
            <a href="profile.php" class="btn btn-outline btn-sm w-full">My Profile</a>
          <?php else: ?>
            <a href="login.php" class="btn btn-outline btn-sm w-full">Login to Book</a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</main>

<?php require_once 'components/footer.php'; ?>
