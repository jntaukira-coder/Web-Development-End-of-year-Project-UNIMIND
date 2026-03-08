<?php
$page_title = "Book Accommodation";
require_once 'functions.php';
require_once 'db_connect.php';

redirect_if_not_logged_in();

$accommodation_id = $_GET['id'] ?? 0;
$accommodation = null;
$error = '';
$success = '';

// Fetch accommodation details
if ($accommodation_id) {
    $stmt = $conn->prepare("
        SELECT a.*, (SELECT COUNT(*) FROM accommodation_bookings WHERE accommodation_id = a.id AND status != 'cancelled') as current_bookings 
        FROM accommodations a 
        WHERE a.id = ? AND a.verified = TRUE
    ");
    $stmt->bind_param("i", $accommodation_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $accommodation = $result->fetch_assoc();
    $stmt->close();
    
    if (!$accommodation) {
        header('Location: search.php');
        exit;
    }
    
    // Check availability
    $available_spaces = $accommodation['capacity'] - $accommodation['current_bookings'];
    if ($available_spaces <= 0) {
        $error = "This accommodation is currently fully booked.";
    }
}

// Handle booking
if ($_SERVER["REQUEST_METHOD"] == "POST" && $accommodation) {
    $csrf_token = $_POST['csrf_token'] ?? '';
    
    if (!verify_csrf_token($csrf_token)) {
        $error = "Security token invalid. Please try again.";
    } else {
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $notes = sanitize_input($_POST['notes'] ?? '');
        
        // Validate dates
        $start = new DateTime($start_date);
        $end = new DateTime($end_date);
        $today = new DateTime();
        
        if ($start < $today) {
            $error = "Start date cannot be in the past.";
        } elseif ($end <= $start) {
            $error = "End date must be after start date.";
        } else {
            // Calculate total amount (assuming monthly rate)
            $months = ceil($start->diff($end)->days / 30);
            $total_amount = $accommodation['price_per_month'] * $months;
            $user_id = $_SESSION['user_id'];
            
            // Create booking
            $stmt = $conn->prepare("
                INSERT INTO accommodation_bookings (user_id, accommodation_id, start_date, end_date, total_amount, notes) 
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $stmt->bind_param("iissds", $user_id, $accommodation_id, $start_date, $end_date, $total_amount, $notes);
            
            if ($stmt->execute()) {
                $booking_id = $conn->insert_id;
                
                // Update available spaces
                $new_available = $available_spaces - 1;
                $update_stmt = $conn->prepare("UPDATE accommodations SET available_spaces = ? WHERE id = ?");
                $update_stmt->bind_param("ii", $new_available, $accommodation_id);
                $update_stmt->execute();
                $update_stmt->close();
                
                // Create notification
                $notification_title = "Booking Confirmed";
                $notification_message = "Your booking for {$accommodation['name']} has been received. Total amount: MWK " . number_format($total_amount);
                $notif_stmt = $conn->prepare("INSERT INTO notifications (user_id, title, message, type) VALUES (?, ?, ?, 'success')");
                $notif_stmt->bind_param("iss", $user_id, $notification_title, $notification_message);
                $notif_stmt->execute();
                $notif_stmt->close();
                
                $success = "Booking successful! Your booking ID is #" . $booking_id . ". Please proceed with payment.";
                
                // Redirect to payment page (in a real implementation)
                // header("Location: payment.php?booking_id=" . $booking_id);
                // exit;
            } else {
                $error = "Failed to create booking. Please try again.";
            }
            $stmt->close();
        }
    }
}
?>

<?php require_once 'components/header.php'; ?>

<?php if ($accommodation): ?>
<main class="container" style="margin-top: 120px; min-height: calc(100vh - 200px);">
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Accommodation Details -->
    <div class="lg:col-span-2">
      <div class="card mb-6">
        <h1 class="font-bold text-3xl mb-4"><?php echo htmlspecialchars($accommodation['name']); ?></h1>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
          <div>
            <span class="text-primary font-semibold">📍 Address:</span>
            <p class="text-secondary"><?php echo htmlspecialchars($accommodation['address']); ?></p>
          </div>
          <div>
            <span class="text-primary font-semibold">💰 Price:</span>
            <p class="text-secondary">MWK <?php echo number_format($accommodation['price_per_month']); ?>/month</p>
          </div>
          <div>
            <span class="text-primary font-semibold">👥 Capacity:</span>
            <p class="text-secondary"><?php echo $accommodation['capacity']; ?> total spaces</p>
          </div>
          <div>
            <span class="text-primary font-semibold">✅ Available:</span>
            <p class="text-secondary"><?php echo max(0, $accommodation['capacity'] - $accommodation['current_bookings']); ?> spaces</p>
          </div>
        </div>
        
        <div class="mb-6">
          <h3 class="font-bold text-xl mb-3">Description</h3>
          <p class="text-secondary"><?php echo nl2br(htmlspecialchars($accommodation['description'])); ?></p>
        </div>
        
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
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <span class="text-primary font-semibold">📞 Contact:</span>
            <p class="text-secondary"><?php echo htmlspecialchars($accommodation['contact_person']); ?><br>
            <?php echo htmlspecialchars($accommodation['contact_phone']); ?></p>
          </div>
          <?php if ($accommodation['contact_email']): ?>
          <div>
            <span class="text-primary font-semibold">📧 Email:</span>
            <p class="text-secondary"><?php echo htmlspecialchars($accommodation['contact_email']); ?></p>
          </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
    
    <!-- Booking Form -->
    <div class="lg:col-span-1">
      <div class="card">
        <h2 class="font-bold text-2xl mb-6">Book This Accommodation</h2>
        
        <?php if ($error): ?>
          <div class="text-error text-center mb-4 p-3" style="background: rgba(239, 68, 68, 0.1); border-radius: 8px;">
            <?php echo $error; ?>
          </div>
        <?php endif; ?>
        
        <?php if ($success): ?>
          <div class="text-success text-center mb-4 p-3" style="background: rgba(16, 185, 129, 0.1); border-radius: 8px;">
            <?php echo $success; ?>
          </div>
        <?php else: ?>
          <?php if ($available_spaces > 0): ?>
            <form action="booking.php?id=<?php echo $accommodation_id; ?>" method="POST">
              <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
              
              <div class="form-group">
                <label for="start_date" class="form-label">Start Date</label>
                <input type="date" id="start_date" name="start_date" class="form-input" required
                       min="<?php echo date('Y-m-d'); ?>">
              </div>
              
              <div class="form-group">
                <label for="end_date" class="form-label">End Date</label>
                <input type="date" id="end_date" name="end_date" class="form-input" required
                       min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
              </div>
              
              <div class="form-group">
                <label for="notes" class="form-label">Special Requests (Optional)</label>
                <textarea id="notes" name="notes" class="form-input" rows="3" 
                          placeholder="Any special requirements or questions..."></textarea>
              </div>
              
              <div class="bg-gray-800 p-4 rounded-lg mb-4">
                <div class="flex justify-between items-center">
                  <span class="font-semibold">Estimated Total:</span>
                  <span class="text-primary font-bold text-xl" id="estimated-total">MWK 0</span>
                </div>
                <small class="text-muted">Calculated based on selected dates</small>
              </div>
              
              <button type="submit" class="btn btn-primary w-full">Confirm Booking</button>
            </form>
          <?php else: ?>
            <div class="text-center">
              <div class="text-error mb-4">❌ Fully Booked</div>
              <p class="text-secondary mb-4">This accommodation is currently at full capacity.</p>
              <a href="search.php" class="btn btn-outline">Find Other Options</a>
            </div>
          <?php endif; ?>
        <?php endif; ?>
      </div>
      
      <!-- Safety Notice -->
      <div class="card mt-6">
        <h3 class="font-bold text-lg mb-3">🛡️ Safety Notice</h3>
        <ul class="text-secondary text-sm space-y-2">
          <li>• Always visit the accommodation before booking</li>
          <li>• Verify the landlord's identity</li>
          <li>• Read the rental agreement carefully</li>
          <li>• Pay through secure methods only</li>
          <li>• Keep records of all payments</li>
        </ul>
      </div>
    </div>
  </div>
</main>

<script>
document.getElementById('start_date').addEventListener('change', calculateTotal);
document.getElementById('end_date').addEventListener('change', calculateTotal);

function calculateTotal() {
  const startDate = new Date(document.getElementById('start_date').value);
  const endDate = new Date(document.getElementById('end_date').value);
  const pricePerMonth = <?php echo $accommodation['price_per_month']; ?>;
  
  if (startDate && endDate && endDate > startDate) {
    const days = Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24));
    const months = Math.ceil(days / 30);
    const total = pricePerMonth * months;
    
    document.getElementById('estimated-total').textContent = 'MWK ' + total.toLocaleString();
  } else {
    document.getElementById('estimated-total').textContent = 'MWK 0';
  }
}
</script>

<?php else: ?>
<main class="container" style="margin-top: 120px;">
  <div class="card text-center">
    <h1 class="font-bold text-3xl mb-4">Accommodation Not Found</h1>
    <p class="text-secondary mb-6">The accommodation you're looking for doesn't exist or is not available.</p>
    <a href="search.php" class="btn btn-primary">Browse Accommodations</a>
  </div>
</main>
<?php endif; ?>

<?php require_once 'components/footer.php'; ?>
