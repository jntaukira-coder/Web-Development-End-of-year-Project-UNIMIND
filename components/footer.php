<footer class="mt-8 p-6" style="background: var(--secondary-dark); border-top: 1px solid rgba(255, 255, 255, 0.1);">
    <div class="container">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- About Section -->
            <div>
                <h3 class="font-bold text-lg mb-4 text-primary">About UNIMIND</h3>
                <p class="text-secondary">
                    Your comprehensive student arrival toolkit designed to help first-year university students in Malawi navigate campus life safely and confidently.
                </p>
            </div>
            
            <!-- Quick Links -->
            <div>
                <h3 class="font-bold text-lg mb-4 text-primary">Quick Links</h3>
                <ul class="space-y-2">
                    <li><a href="index.php" class="text-secondary hover:text-primary transition">Home</a></li>
                    <li><a href="Accomodation.php" class="text-secondary hover:text-primary transition">Accommodation</a></li>
                    <li><a href="services.php" class="text-secondary hover:text-primary transition">Services</a></li>
                    <li><a href="campus life.php" class="text-secondary hover:text-primary transition">Campus Life</a></li>
                    <li><a href="aboutme.php" class="text-secondary hover:text-primary transition">About</a></li>
                </ul>
            </div>
            
            <!-- Contact Info -->
            <div>
                <h3 class="font-bold text-lg mb-4 text-primary">Get in Touch</h3>
                <p class="text-secondary mb-2">
                    <strong>Email:</strong> info@unimind.mw<br>
                    <strong>Phone:</strong> +265 123 456 789<br>
                    <strong>Location:</strong> MUBAS, Blantyre, Malawi
                </p>
                <div class="flex gap-4 mt-4">
                    <a href="#" class="text-secondary hover:text-primary transition">Facebook</a>
                    <a href="#" class="text-secondary hover:text-primary transition">Twitter</a>
                    <a href="#" class="text-secondary hover:text-primary transition">Instagram</a>
                </div>
            </div>
        </div>
        
        <div class="mt-8 pt-6 border-t border-gray-700 text-center">
            <p class="text-secondary">
                &copy; <?php echo date('Y'); ?> UNIMIND. All rights reserved. | 
                Designed with ❤️ by <a href="aboutme.php" class="text-primary hover:underline">Jubeda Orleen Ntaukira</a>
            </p>
        </div>
    </div>
</footer>

<!-- Back to Top Button -->
<button id="backToTop" class="btn btn-primary" style="position: fixed; bottom: 20px; right: 20px; z-index: 1000; display: none;" aria-label="Back to top">
    ↑
</button>

<script>
// Back to top functionality
const backToTopButton = document.getElementById('backToTop');
    
window.addEventListener('scroll', () => {
    if (window.pageYOffset > 300) {
        backToTopButton.style.display = 'flex';
    } else {
        backToTopButton.style.display = 'none';
    }
});

backToTopButton.addEventListener('click', () => {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
});

// Smooth scroll for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});
</script>

</body>
</html>
