<?php
$page_title = "Page Not Found";
require_once 'components/header.php';
?>

<main class="container container-sm" style="margin-top: 120px; min-height: calc(100vh - 200px);">
  <div class="card text-center">
    <div class="mb-6">
      <div class="text-6xl mb-4">🔍</div>
      <h1 class="font-bold text-4xl mb-4">404 - Page Not Found</h1>
      <p class="text-secondary text-lg mb-6">
        Oops! The page you're looking for doesn't exist or has been moved.
      </p>
    </div>
    
    <div class="space-y-4">
      <div>
        <h3 class="font-semibold text-primary mb-2">What can you do?</h3>
        <ul class="text-secondary space-y-1">
          <li>• Check the URL for typos</li>
          <li>• Go back to the previous page</li>
          <li>• Use the search function</li>
          <li>• Navigate using the menu above</li>
        </ul>
      </div>
      
      <div class="flex gap-4 justify-center mt-6">
        <a href="index.php" class="btn btn-primary">Go Home</a>
        <a href="search.php" class="btn btn-outline">Search</a>
        <?php if (is_logged_in()): ?>
          <a href="Home.php" class="btn btn-outline">Dashboard</a>
        <?php else: ?>
          <a href="login.php" class="btn btn-outline">Login</a>
        <?php endif; ?>
      </div>
    </div>
    
    <div class="mt-8 pt-6 border-t border-gray-700">
      <p class="text-muted text-sm">
        If you believe this is an error, please contact us at info@unimind.mw
      </p>
    </div>
  </div>
</main>

<?php require_once 'components/footer.php'; ?>
