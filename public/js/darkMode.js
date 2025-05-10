// Dark mode functionality
document.addEventListener('DOMContentLoaded', function() {
    // Check for saved theme preference or use the system preference
    const darkModeMediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
    const savedTheme = localStorage.getItem('theme');
    
    // Set initial theme
    if (savedTheme === 'dark' || (!savedTheme && darkModeMediaQuery.matches)) {
        document.documentElement.classList.add('dark');
        updateDarkModeToggle(true);
    } else {
        document.documentElement.classList.remove('dark');
        updateDarkModeToggle(false);
    }
    
    // Set up the toggle button
    const darkModeToggle = document.getElementById('dark-mode-toggle');
    if (darkModeToggle) {
        darkModeToggle.addEventListener('click', function() {
            toggleDarkMode();
        });
    }
    
    // Function to toggle dark mode
    function toggleDarkMode() {
        if (document.documentElement.classList.contains('dark')) {
            document.documentElement.classList.remove('dark');
            localStorage.setItem('theme', 'light');
            updateDarkModeToggle(false);
        } else {
            document.documentElement.classList.add('dark');
            localStorage.setItem('theme', 'dark');
            updateDarkModeToggle(true);
        }
    }
    
    // Update toggle button appearance
    function updateDarkModeToggle(isDark) {
        const darkModeToggle = document.getElementById('dark-mode-toggle');
        if (!darkModeToggle) return;
        
        const sunIcon = darkModeToggle.querySelector('.sun-icon');
        const moonIcon = darkModeToggle.querySelector('.moon-icon');
        
        if (isDark) {
            sunIcon.classList.remove('hidden');
            moonIcon.classList.add('hidden');
            darkModeToggle.setAttribute('title', 'Switch to Light Mode');
        } else {
            sunIcon.classList.add('hidden');
            moonIcon.classList.remove('hidden');
            darkModeToggle.setAttribute('title', 'Switch to Dark Mode');
        }
    }
    
    // Listen for changes in system preference
    darkModeMediaQuery.addEventListener('change', function(e) {
        if (!localStorage.getItem('theme')) {
            if (e.matches) {
                document.documentElement.classList.add('dark');
                updateDarkModeToggle(true);
            } else {
                document.documentElement.classList.remove('dark');
                updateDarkModeToggle(false);
            }
        }
    });
});
