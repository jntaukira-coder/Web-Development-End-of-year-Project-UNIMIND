<?php
$page_title = "Search";
require_once 'functions.php';
require_once 'db_connect.php';

redirect_if_not_logged_in();

$search_query = '';
$search_type = 'all';
$results = [];
$error = '';

// Handle search
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $search_query = sanitize_input($_GET['q'] ?? '');
    $search_type = sanitize_input($_GET['type'] ?? 'all');
    
    if (!empty($search_query)) {
        // Log search for analytics
        $user_id = $_SESSION['user_id'];
        $ip_address = $_SERVER['REMOTE_ADDR'];
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        
        $log_stmt = $conn->prepare("INSERT INTO search_logs (user_id, search_query, search_type, ip_address, user_agent) VALUES (?, ?, ?, ?, ?)");
        $log_stmt->bind_param("issss", $user_id, $search_query, $search_type, $ip_address, $user_agent);
        $log_stmt->execute();
        $log_stmt->close();
        
        // Perform search
        $search_term = '%' . $search_query . '%';
        
        if ($search_type === 'all' || $search_type === 'accommodation') {
            $stmt = $conn->prepare("
                SELECT 'accommodation' as type, id, name, description, address, price_per_month, rating, photos 
                FROM accommodations 
                WHERE (name LIKE ? OR description LIKE ? OR address LIKE ?) AND verified = TRUE
                ORDER BY rating DESC
            ");
            $stmt->bind_param("sss", $search_term, $search_term, $search_term);
            $stmt->execute();
            $accommodation_results = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $results = array_merge($results, $accommodation_results);
            $stmt->close();
        }
        
        if ($search_type === 'all' || $search_type === 'service') {
            $stmt = $conn->prepare("
                SELECT 'service' as type, id, name, description, address, category, rating, photos 
                FROM services 
                WHERE (name LIKE ? OR description LIKE ? OR address LIKE ?) AND verified = TRUE
                ORDER BY rating DESC
            ");
            $stmt->bind_param("sss", $search_term, $search_term, $search_term);
            $stmt->execute();
            $service_results = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $results = array_merge($results, $service_results);
            $stmt->close();
        }
        
        if ($search_type === 'all' || $search_type === 'mentor') {
            $stmt = $conn->prepare("
                SELECT 'mentor' as type, id, full_name as name, bio as description, program as address, rating 
                FROM users 
                WHERE user_type = 'mentor' AND (full_name LIKE ? OR bio LIKE ? OR program LIKE ?) AND is_active = TRUE
                ORDER BY rating DESC
            ");
            $stmt->bind_param("sss", $search_term, $search_term, $search_term);
            $stmt->execute();
            $mentor_results = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $results = array_merge($results, $mentor_results);
            $stmt->close();
        }
    }
}
?>

<?php require_once 'components/header.php'; ?>

<main class="container" style="margin-top: 120px; min-height: calc(100vh - 200px);">
  <!-- Search Section -->
  <div class="card mb-6">
    <h1 class="font-bold text-3xl mb-6">Search UNIMIND</h1>
    
    <form action="search.php" method="GET" class="mb-6">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="md:col-span-2">
          <input type="text" name="q" class="form-input" placeholder="Search accommodations, services, mentors..." 
                 value="<?php echo htmlspecialchars($search_query); ?>" required>
        </div>
        <div>
          <select name="type" class="form-input">
            <option value="all" <?php echo $search_type === 'all' ? 'selected' : ''; ?>>All Categories</option>
            <option value="accommodation" <?php echo $search_type === 'accommodation' ? 'selected' : ''; ?>>Accommodation</option>
            <option value="service" <?php echo $search_type === 'service' ? 'selected' : ''; ?>>Services</option>
            <option value="mentor" <?php echo $search_type === 'mentor' ? 'selected' : ''; ?>>Mentors</option>
          </select>
        </div>
      </div>
      <button type="submit" class="btn btn-primary mt-4">Search</button>
    </form>
    
    <!-- Quick Filters -->
    <div class="flex flex-wrap gap-2">
      <a href="search.php?q=hostel&type=accommodation" class="btn btn-outline btn-sm">Hostels</a>
      <a href="search.php?q=healthcare&type=service" class="btn btn-outline btn-sm">Healthcare</a>
      <a href="search.php?q=shopping&type=service" class="btn btn-outline btn-sm">Shopping</a>
      <a href="search.php?q=banking&type=service" class="btn btn-outline btn-sm">Banking</a>
      <a href="search.php?q=transport&type=service" class="btn btn-outline btn-sm">Transport</a>
      <a href="search.php?q=engineering&type=mentor" class="btn btn-outline btn-sm">Engineering Mentors</a>
      <a href="search.php?q=computer&type=mentor" class="btn btn-outline btn-sm">Computer Science Mentors</a>
    </div>
  </div>
  
  <!-- Results Section -->
  <?php if ($search_query): ?>
    <div class="mb-4">
      <h2 class="font-bold text-xl">
        Search Results for "<?php echo htmlspecialchars($search_query); ?>"
        <span class="text-secondary text-base">(<?php echo count($results); ?> results found)</span>
      </h2>
    </div>
    
    <?php if (empty($results)): ?>
      <div class="card text-center">
        <div class="text-muted text-lg mb-4">No results found for your search.</div>
        <p class="text-secondary">Try different keywords or browse our categories above.</p>
      </div>
    <?php else: ?>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($results as $result): ?>
          <div class="card">
            <?php if ($result['type'] === 'accommodation'): ?>
              <div class="card-header">
                <span class="text-primary text-sm">🏠 ACCOMMODATION</span>
                <h3 class="card-title"><?php echo htmlspecialchars($result['name']); ?></h3>
              </div>
              <p class="card-description"><?php echo htmlspecialchars(substr($result['description'], 0, 100)) . '...'; ?></p>
              <div class="text-secondary text-sm mb-2">
                📍 <?php echo htmlspecialchars($result['address']); ?>
              </div>
              <div class="flex justify-between items-center">
                <span class="text-primary font-bold">MWK <?php echo number_format($result['price_per_month']); ?>/month</span>
                <?php if ($result['rating'] > 0): ?>
                  <span class="text-yellow-400">⭐ <?php echo $result['rating']; ?></span>
                <?php endif; ?>
              </div>
              <div class="card-footer">
                <a href="accommodation_details.php?id=<?php echo $result['id']; ?>" class="btn btn-primary btn-sm">View Details</a>
              </div>
            
            <?php elseif ($result['type'] === 'service'): ?>
              <div class="card-header">
                <span class="text-primary text-sm">📍 SERVICE</span>
                <h3 class="card-title"><?php echo htmlspecialchars($result['name']); ?></h3>
              </div>
              <p class="card-description"><?php echo htmlspecialchars(substr($result['description'], 0, 100)) . '...'; ?></p>
              <div class="text-secondary text-sm mb-2">
                📍 <?php echo htmlspecialchars($result['address']); ?>
              </div>
              <div class="text-secondary text-sm mb-2">
                Category: <?php echo ucfirst(htmlspecialchars($result['category'])); ?>
              </div>
              <?php if ($result['rating'] > 0): ?>
                <div class="text-yellow-400 mb-2">⭐ <?php echo $result['rating']; ?></div>
              <?php endif; ?>
              <div class="card-footer">
                <a href="service_details.php?id=<?php echo $result['id']; ?>" class="btn btn-primary btn-sm">View Details</a>
              </div>
            
            <?php elseif ($result['type'] === 'mentor'): ?>
              <div class="card-header">
                <span class="text-primary text-sm">👥 MENTOR</span>
                <h3 class="card-title"><?php echo htmlspecialchars($result['name']); ?></h3>
              </div>
              <p class="card-description"><?php echo htmlspecialchars(substr($result['description'], 0, 100)) . '...'; ?></p>
              <div class="text-secondary text-sm mb-2">
                🎓 <?php echo htmlspecialchars($result['address']); ?>
              </div>
              <?php if ($result['rating'] > 0): ?>
                <div class="text-yellow-400 mb-2">⭐ <?php echo $result['rating']; ?></div>
              <?php endif; ?>
              <div class="card-footer">
                <a href="mentor_profile.php?id=<?php echo $result['id']; ?>" class="btn btn-primary btn-sm">View Profile</a>
              </div>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  
  <?php else: ?>
    <!-- Popular Searches -->
    <div class="card">
      <h2 class="font-bold text-xl mb-4">Popular Searches</h2>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <h3 class="font-semibold text-primary mb-2">🏠 Accommodations</h3>
          <ul class="space-y-1">
            <li><a href="search.php?q=mubas+hostel&type=accommodation" class="text-secondary hover:text-primary">MUBAS Hostel</a></li>
            <li><a href="search.php?q=chichiri&type=accommodation" class="text-secondary hover:text-primary">Chichiri Area</a></li>
            <li><a href="search.php?q=affordable&type=accommodation" class="text-secondary hover:text-primary">Affordable Options</a></li>
          </ul>
        </div>
        <div>
          <h3 class="font-semibold text-primary mb-2">📍 Services</h3>
          <ul class="space-y-1">
            <li><a href="search.php?q=hospital&type=service" class="text-secondary hover:text-primary">Hospitals & Clinics</a></li>
            <li><a href="search.php?q=supermarket&type=service" class="text-secondary hover:text-primary">Supermarkets</a></li>
            <li><a href="search.php?q=bank&type=service" class="text-secondary hover:text-primary">Banks & ATMs</a></li>
          </ul>
        </div>
      </div>
    </div>
  <?php endif; ?>
</main>

<?php require_once 'components/footer.php'; ?>
