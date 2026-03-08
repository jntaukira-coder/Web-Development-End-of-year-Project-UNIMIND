<?php
require_once 'auth_protect.php';
$page_title = "MUBAS Campus Map";
require_once 'db_connect.php';

// Fetch campus buildings
$stmt = $conn->prepare("SELECT * FROM campus_buildings ORDER BY name");
$stmt->execute();
$buildings = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Get building categories
$stmt = $conn->prepare("SELECT DISTINCT category FROM campus_buildings ORDER BY category");
$stmt->execute();
$categories = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<?php require_once 'components/header.php'; ?>

<main class="container" style="margin-top: 120px; min-height: calc(100vh - 200px);">
  <div class="mb-6">
    <h1 class="font-bold text-3xl mb-2">MUBAS Campus Map</h1>
    <p class="text-secondary">Navigate Malawi University of Business and Applied Sciences with interactive building maps and directions</p>
  </div>

  <!-- Campus Overview -->
  <div class="card mb-6">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
      <div>
        <h2 class="font-bold text-2xl mb-4">Campus Overview</h2>
        <p class="text-secondary mb-4">
          MUBAS campus is located in Blantyre, Malawi, and features modern facilities designed to support excellence in business and applied sciences education.
        </p>
        <div class="space-y-3">
          <div class="flex items-center gap-3">
            <span class="text-2xl">📍</span>
            <div>
              <div class="font-semibold">Main Campus</div>
              <div class="text-sm text-muted">Chichiri, Blantyre, Malawi</div>
            </div>
          </div>
          <div class="flex items-center gap-3">
            <span class="text-2xl">📞</span>
            <div>
              <div class="font-semibold">Campus Security</div>
              <div class="text-sm text-muted">+265 991 234 567 (24/7)</div>
            </div>
          </div>
          <div class="flex items-center gap-3">
            <span class="text-2xl">🕐</span>
            <div>
              <div class="font-semibold">Campus Hours</div>
              <div class="text-sm text-muted">6:00 AM - 10:00 PM Daily</div>
            </div>
          </div>
        </div>
      </div>
      
      <div>
        <h3 class="font-bold text-xl mb-4">Quick Categories</h3>
        <div class="grid grid-cols-2 gap-3">
          <?php foreach ($categories as $cat): ?>
            <button onclick="filterBuildings('<?php echo strtolower($cat['category']); ?>')" 
                    class="btn btn-outline text-sm">
              <?php echo htmlspecialchars($cat['category']); ?>
            </button>
          <?php endforeach; ?>
        </div>
        <div class="mt-4">
          <button onclick="filterBuildings('all')" class="btn btn-primary w-full">Show All Buildings</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Building Search -->
  <div class="card mb-6">
    <div class="flex gap-4">
      <input type="text" id="buildingSearch" placeholder="Search buildings by name, department, or facility..." 
             class="form-input flex-1" onkeyup="searchBuildings()">
      <button onclick="searchBuildings()" class="btn btn-primary">Search</button>
    </div>
  </div>

  <!-- Buildings Grid -->
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="buildingsGrid">
    <?php foreach ($buildings as $building): ?>
      <div class="card building-card" data-category="<?php echo strtolower($building['category']); ?>" 
           data-name="<?php echo strtolower($building['name']); ?>" data-search="<?php echo strtolower($building['name'] . ' ' . $building['departments'] . ' ' . $building['description']); ?>">
        
        <!-- Building Header -->
        <div class="flex items-center gap-3 mb-4">
          <div class="w-12 h-12 bg-primary rounded-lg flex items-center justify-center">
            <span class="text-white font-bold"><?php echo substr($building['name'], 0, 2); ?></span>
          </div>
          <div>
            <h3 class="font-bold text-lg"><?php echo htmlspecialchars($building['name']); ?></h3>
            <span class="text-xs px-2 py-1 rounded bg-gray-700 text-white">
              <?php echo htmlspecialchars($building['category']); ?>
            </span>
          </div>
        </div>
        
        <!-- Building Info -->
        <div class="space-y-2 text-sm mb-4">
          <div class="flex justify-between">
            <span class="text-muted">📍 Block:</span>
            <span class="text-secondary"><?php echo htmlspecialchars($building['block']); ?></span>
          </div>
          <div class="flex justify-between">
            <span class="text-muted">🏢 Floors:</span>
            <span class="text-secondary"><?php echo $building['floors']; ?></span>
          </div>
          <?php if ($building['has_wifi']): ?>
          <div class="flex justify-between">
            <span class="text-muted">📶 WiFi:</span>
            <span class="text-success">Available</span>
          </div>
          <?php endif; ?>
          <?php if ($building['has_library']): ?>
          <div class="flex justify-between">
            <span class="text-muted">📚 Library:</span>
            <span class="text-success">Available</span>
          </div>
          <?php endif; ?>
          <?php if ($building['has_cafe']): ?>
          <div class="flex justify-between">
            <span class="text-muted">☕ Cafe:</span>
            <span class="text-success">Available</span>
          </div>
          <?php endif; ?>
        </div>
        
        <!-- Departments -->
        <?php if ($building['departments']): ?>
        <div class="mb-4">
          <span class="text-sm font-semibold text-primary">Departments:</span>
          <p class="text-sm text-secondary"><?php echo htmlspecialchars($building['departments']); ?></p>
        </div>
        <?php endif; ?>
        
        <!-- Description -->
        <p class="text-secondary text-sm mb-4">
          <?php echo htmlspecialchars(substr($building['description'], 0, 100)) . '...'; ?>
        </p>
        
        <!-- Action Buttons -->
        <div class="space-y-2">
          <button onclick="showBuildingDetails(<?php echo $building['id']; ?>)" class="btn btn-primary w-full btn-sm">
            🗺️ View Map & Directions
          </button>
          <button onclick="showBuildingImage(<?php echo $building['id']; ?>)" class="btn btn-outline w-full btn-sm">
            📷 View Photos
          </button>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <!-- Building Details Modal -->
  <div id="buildingModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="card max-w-4xl w-full max-h-[90vh] overflow-y-auto">
      <div id="modalContent">
        <!-- Content will be loaded dynamically -->
      </div>
    </div>
  </div>
</main>

<script>
function filterBuildings(category) {
  const cards = document.querySelectorAll('.building-card');
  
  cards.forEach(card => {
    if (category === 'all') {
      card.style.display = 'block';
    } else {
      const cardCategory = card.getAttribute('data-category');
      card.style.display = cardCategory === category ? 'block' : 'none';
    }
  });
}

function searchBuildings() {
  const searchTerm = document.getElementById('buildingSearch').value.toLowerCase();
  const cards = document.querySelectorAll('.building-card');
  
  cards.forEach(card => {
    const searchData = card.getAttribute('data-search');
    card.style.display = searchData.includes(searchTerm) ? 'block' : 'none';
  });
}

function showBuildingDetails(buildingId) {
  // This would typically fetch detailed building information via AJAX
  // For now, we'll show a placeholder
  const modal = document.getElementById('buildingModal');
  const content = document.getElementById('modalContent');
  
  content.innerHTML = `
    <div class="flex justify-between items-center mb-6">
      <h2 class="font-bold text-2xl">Building Map & Directions</h2>
      <button onclick="closeModal()" class="btn btn-outline btn-sm">✕ Close</button>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <div>
        <h3 class="font-bold text-xl mb-4">Interactive Map</h3>
        <div class="bg-gray-800 rounded-lg p-8 text-center">
          <div class="text-6xl mb-4">🗺️</div>
          <p class="text-muted">Interactive campus map would be displayed here</p>
          <p class="text-sm text-muted mt-2">Zoom in/out, click buildings for details</p>
        </div>
        
        <h3 class="font-bold text-xl mt-6 mb-4">Getting Here</h3>
        <div class="space-y-3">
          <div class="border border-gray-700 rounded-lg p-3">
            <div class="font-semibold mb-1">🚶 Walking Routes</div>
            <p class="text-sm text-secondary">5-minute walk from main gate</p>
          </div>
          <div class="border border-gray-700 rounded-lg p-3">
            <div class="font-semibold mb-1">🚌 Public Transport</div>
            <p class="text-sm text-secondary">Minibus route 23 stops at campus entrance</p>
          </div>
          <div class="border border-gray-700 rounded-lg p-3">
            <div class="font-semibold mb-1">🚗 Parking</div>
            <p class="text-sm text-secondary">Student parking available at Block B</p>
          </div>
        </div>
      </div>
      
      <div>
        <h3 class="font-bold text-xl mb-4">Building Facilities</h3>
        <div class="space-y-2">
          <div class="flex items-center gap-2">
            <span class="text-success">✓</span>
            <span>WiFi Available</span>
          </div>
          <div class="flex items-center gap-2">
            <span class="text-success">✓</span>
            <span>Air Conditioning</span>
          </div>
          <div class="flex items-center gap-2">
            <span class="text-success">✓</span>
            <span>Elevator Access</span>
          </div>
          <div class="flex items-center gap-2">
            <span class="text-success">✓</span>
            <span>Wheelchair Accessible</span>
          </div>
          <div class="flex items-center gap-2">
            <span class="text-success">✓</span>
            <span>Security Desk</span>
          </div>
        </div>
        
        <h3 class="font-bold text-xl mt-6 mb-4">Operating Hours</h3>
        <div class="space-y-2 text-sm">
          <div class="flex justify-between">
            <span>Monday - Friday:</span>
            <span>7:00 AM - 9:00 PM</span>
          </div>
          <div class="flex justify-between">
            <span>Saturday:</span>
            <span>8:00 AM - 6:00 PM</span>
          </div>
          <div class="flex justify-between">
            <span>Sunday:</span>
            <span>9:00 AM - 5:00 PM</span>
          </div>
        </div>
        
        <h3 class="font-bold text-xl mt-6 mb-4">Emergency Information</h3>
        <div class="space-y-2 text-sm">
          <div class="flex justify-between">
            <span>🚨 Emergency Exit:</span>
            <span>Ground Floor, East Wing</span>
          </div>
          <div class="flex justify-between">
            <span>🧯 Fire Extinguisher:</span>
            <span>Each Floor</span>
          </div>
          <div class="flex justify-between">
            <span>🏥 First Aid:</span>
            <span>Reception Desk</span>
          </div>
        </div>
      </div>
    </div>
  `;
  
  modal.classList.remove('hidden');
}

function showBuildingImage(buildingId) {
  const modal = document.getElementById('buildingModal');
  const content = document.getElementById('modalContent');
  
  content.innerHTML = `
    <div class="flex justify-between items-center mb-6">
      <h2 class="font-bold text-2xl">Building Photos</h2>
      <button onclick="closeModal()" class="btn btn-outline btn-sm">✕ Close</button>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div class="bg-gray-800 rounded-lg p-8 text-center">
        <div class="text-6xl mb-4">📷</div>
        <p class="text-muted">Exterior View</p>
      </div>
      <div class="bg-gray-800 rounded-lg p-8 text-center">
        <div class="text-6xl mb-4">🏢</div>
        <p class="text-muted">Interior View</p>
      </div>
      <div class="bg-gray-800 rounded-lg p-8 text-center">
        <div class="text-6xl mb-4">🗺️</div>
        <p class="text-muted">Floor Plan</p>
      </div>
      <div class="bg-gray-800 rounded-lg p-8 text-center">
        <div class="text-6xl mb-4">🚪</div>
        <p class="text-muted">Entrance</p>
      </div>
    </div>
  `;
  
  modal.classList.remove('hidden');
}

function closeModal() {
  document.getElementById('buildingModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('buildingModal').addEventListener('click', function(e) {
  if (e.target === this) {
    closeModal();
  }
});
</script>

<?php require_once 'components/footer.php'; ?>
