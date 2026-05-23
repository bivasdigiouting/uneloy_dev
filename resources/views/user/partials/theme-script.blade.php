<script>
    (function() {
        const htmlElement = document.documentElement;
        const currentTheme = localStorage.getItem('theme');
        
        if (currentTheme) {
            htmlElement.setAttribute('data-theme', currentTheme);
        }

        document.addEventListener('DOMContentLoaded', function() {
            const themeToggle = document.getElementById('flexSwitchCheckDefault');
            
            // Check for saved user preference for toggle state
            if (currentTheme === 'dark' && themeToggle) {
                themeToggle.checked = true;
            }
            
            // Function to switch theme
            if(themeToggle) {
                themeToggle.addEventListener('change', function(e) {
                    if (e.target.checked) {
                        htmlElement.setAttribute('data-theme', 'dark');
                        localStorage.setItem('theme', 'dark');
                    } else {
                        htmlElement.setAttribute('data-theme', 'light');
                        localStorage.setItem('theme', 'light');
                    }
                }, false);
            }
        });
    })();
</script>
