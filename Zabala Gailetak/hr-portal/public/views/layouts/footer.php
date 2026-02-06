    <script src="/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script>
        // Theme Toggle
        const themeToggle = document.getElementById('themeToggle');
        const html = document.documentElement;
        
        // Load saved theme or default to light
        const savedTheme = localStorage.getItem('theme') || 'light';
        html.setAttribute('data-theme', savedTheme);
        
        themeToggle?.addEventListener('click', () => {
            const currentTheme = html.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            
            html.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            
            // No transition to avoid flicker
        });
    </script>
</body>
</html>
