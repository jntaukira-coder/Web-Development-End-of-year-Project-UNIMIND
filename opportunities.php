<?php
require_once 'auth_protect.php';
$page_title = "Student Opportunities";
require_once 'db_connect.php';

$search_query = sanitize_input($_GET['q'] ?? '');
$opportunity_type = sanitize_input($_GET['type'] ?? '');
$department = sanitize_input($_GET['department'] ?? '');
$location = sanitize_input($_GET['location'] ?? '');

// Fetch opportunities with filtering
$where_conditions = ["o.status = 'active'"];
$params = [];
$types = "";

if (!empty($search_query)) {
    $where_conditions[] = "(o.title LIKE ? OR o.description LIKE ? OR c.name LIKE ?)";
    $search_term = '%' . $search_query . '%';
    $params = array_merge($params, [$search_term, $search_term, $search_term]);
    $types .= "sss";
}

if (!empty($opportunity_type)) {
    $where_conditions[] = "o.opportunity_type = ?";
    $params[] = $opportunity_type;
    $types .= "s";
}

if (!empty($department)) {
    $where_conditions[] = "o.department LIKE ?";
    $dept_term = '%' . $department . '%';
    $params[] = $dept_term;
    $types .= "s";
}

if (!empty($location)) {
    $where_conditions[] = "o.location LIKE ?";
    $loc_term = '%' . $location . '%';
    $params[] = $loc_term;
    $types .= "s";
}

$where_clause = "WHERE " . implode(" AND ", $where_conditions);

$stmt = $conn->prepare("
    SELECT o.*, c.name as company_name, c.logo as company_logo, c.location as company_location,
           (SELECT COUNT(*) FROM applications a WHERE a.opportunity_id = o.id) as application_count
    FROM opportunities o 
    JOIN companies c ON o.company_id = c.id 
    $where_clause
    ORDER BY o.created_at DESC
");

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$opportunities = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Get unique departments for filter
$dept_stmt = $conn->prepare("SELECT DISTINCT department FROM opportunities WHERE department IS NOT NULL AND department != '' ORDER BY department");
$dept_stmt->execute();
$departments = $dept_stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$dept_stmt->close();
?>

<?php require_once 'components/header.php'; ?>

<main class="container" style="margin-top: 120px; min-height: calc(100vh - 200px);">
  <div class="mb-6">
    <h1 class="font-bold text-3xl mb-2">Student Opportunities</h1>
    <p class="text-secondary">Discover internships, jobs, and opportunities from top companies</p>
  </div>

  <!-- Search and Filters -->
  <div class="card mb-6">
    <form action="opportunities.php" method="GET" class="mb-4">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="md:col-span-2">
          <input type="text" name="q" class="form-input" placeholder="Search opportunities, companies..." 
                 value="<?php echo htmlspecialchars($search_query); ?>">
        </div>
        <div>
          <select name="type" class="form-input">
            <option value="">All Types</option>
            <option value="internship" <?php echo $opportunity_type === 'internship' ? 'selected' : ''; ?>>Internship</option>
            <option value="part_time" <?php echo $opportunity_type === 'part_time' ? 'selected' : ''; ?>>Part-time</option>
            <option value="full_time" <?php echo $opportunity_type === 'full_time' ? 'selected' : ''; ?>>Full-time</option>
            <option value="volunteer" <?php echo $opportunity_type === 'volunteer' ? 'selected' : ''; ?>>Volunteer</option>
            <option value="scholarship" <?php echo $opportunity_type === 'scholarship' ? 'selected' : ''; ?>>Scholarship</option>
            <option value="training" <?php echo $opportunity_type === 'training' ? 'selected' : ''; ?>>Training</option>
          </select>
        </div>
        <div>
          <input type="text" name="location" class="form-input" placeholder="Location..." 
                 value="<?php echo htmlspecialchars($location); ?>">
        </div>
      </div>
      <div class="flex gap-4 mt-4">
        <button type="submit" class="btn btn-primary">Search</button>
        <a href="opportunities.php" class="btn btn-outline">Clear Filters</a>
      </div>
    </form>
    
    <!-- Department Filters -->
    <div class="flex flex-wrap gap-2">
      <span class="text-sm text-muted">Departments:</span>
      <?php foreach ($departments as $dept): ?>
        <a href="?department=<?php echo urlencode($dept['department']); ?>" 
           class="text-xs px-2 py-1 rounded bg-gray-700 text-white hover:bg-gray-600">
          <?php echo htmlspecialchars($dept['department']); ?>
        </a>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- Results Count -->
  <div class="mb-4">
    <span class="text-secondary">
      Found <?php echo count($opportunities); ?> opportunities
      <?php if (!empty($search_query) || !empty($opportunity_type) || !empty($department) || !empty($location)): ?>
        (filtered)
      <?php endif; ?>
    </span>
  </div>

  <!-- Opportunities Grid -->
  <?php if (empty($opportunities)): ?>
    <div class="card text-center">
      <div class="text-6xl mb-4">💼</div>
      <h3 class="font-bold text-xl mb-2">No opportunities found</h3>
      <p class="text-secondary mb-4">Try adjusting your search criteria or check back later for new opportunities.</p>
      <a href="opportunities.php" class="btn btn-primary">View All Opportunities</a>
    </div>
  <?php else: ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <?php foreach ($opportunities as $opp): ?>
        <div class="card">
          <!-- Company Header -->
          <div class="flex items-center gap-3 mb-4">
            <?php if ($opp['company_logo']): ?>
              <img src="<?php echo htmlspecialchars($opp['company_logo']); ?>" 
                   alt="<?php echo htmlspecialchars($opp['company_name']); ?>" 
                   class="w-12 h-12 rounded-lg object-cover">
            <?php else: ?>
              <div class="w-12 h-12 bg-gray-700 rounded-lg flex items-center justify-center">
                <span class="text-sm font-bold text-primary"><?php echo strtoupper(substr($opp['company_name'], 0, 2)); ?></span>
              </div>
            <?php endif; ?>
            <div>
              <div class="font-semibold text-primary"><?php echo htmlspecialchars($opp['company_name']); ?></div>
              <div class="text-xs text-muted"><?php echo htmlspecialchars($opp['company_location']); ?></div>
            </div>
          </div>
          
          <!-- Opportunity Details -->
          <h3 class="font-bold text-lg mb-2"><?php echo htmlspecialchars($opp['title']); ?></h3>
          
          <div class="flex flex-wrap gap-2 mb-3">
            <span class="text-xs px-2 py-1 rounded bg-primary text-primary-dark">
              <?php echo ucfirst(str_replace('_', ' ', $opp['opportunity_type'])); ?>
            </span>
            <?php if ($opp['is_remote']): ?>
              <span class="text-xs px-2 py-1 rounded bg-green-600 text-white">🏠 Remote</span>
            <?php endif; ?>
            <?php if ($opp['department']): ?>
              <span class="text-xs px-2 py-1 rounded bg-gray-600 text-white">
                <?php echo htmlspecialchars($opp['department']); ?>
              </span>
            <?php endif; ?>
          </div>
          
          <p class="text-secondary text-sm mb-4">
            <?php echo htmlspecialchars(substr($opp['description'], 0, 150)) . '...'; ?>
          </p>
          
          <!-- Key Details -->
          <div class="space-y-2 text-sm mb-4">
            <?php if ($opp['location']): ?>
            <div class="flex justify-between">
              <span class="text-muted">📍 Location:</span>
              <span class="text-secondary"><?php echo htmlspecialchars($opp['location']); ?></span>
            </div>
            <?php endif; ?>
            
            <?php if ($opp['salary_range']): ?>
            <div class="flex justify-between">
              <span class="text-muted">💰 Salary:</span>
              <span class="text-secondary"><?php echo htmlspecialchars($opp['salary_range']); ?></span>
            </div>
            <?php endif; ?>
            
            <div class="flex justify-between">
              <span class="text-muted">📅 Deadline:</span>
              <span class="text-secondary <?php echo (strtotime($opp['application_deadline']) < time()) ? 'text-error' : ''; ?>">
                <?php echo date('M j, Y', strtotime($opp['application_deadline'])); ?>
              </span>
            </div>
            
            <div class="flex justify-between">
              <span class="text-muted">📋 Applications:</span>
              <span class="text-secondary"><?php echo $opp['application_count']; ?> applied</span>
            </div>
          </div>
          
          <!-- Action Buttons -->
          <div class="space-y-2">
            <a href="opportunity_details.php?id=<?php echo $opp['id']; ?>" class="btn btn-primary w-full">
              View Details
            </a>
            <?php if ($is_logged_in): ?>
              <a href="apply_opportunity.php?id=<?php echo $opp['id']; ?>" class="btn btn-outline w-full">
                Apply Now
              </a>
            <?php else: ?>
              <a href="login.php?redirect=opportunity_details.php?id=<?php echo $opp['id']; ?>" class="btn btn-outline w-full">
                Login to Apply
              </a>
            <?php endif; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <!-- For Companies Section -->
  <div class="card mt-8">
    <div class="text-center">
      <h3 class="font-bold text-2xl mb-4">Are You a Company?</h3>
      <p class="text-secondary mb-6">Post opportunities and connect with talented students from Malawi's top universities.</p>
      <div class="flex gap-4 justify-center">
        <a href="company_register.php" class="btn btn-primary">Register Your Company</a>
        <a href="company_login.php" class="btn btn-outline">Company Login</a>
      </div>
    </div>
  </div>
</main>

<?php require_once 'components/footer.php'; ?>
