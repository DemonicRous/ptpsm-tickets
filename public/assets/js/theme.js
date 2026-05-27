(function() {
    const storageKey = 'ptpsm-theme';
    const theme = localStorage.getItem(storageKey) || 'light';
    if (theme === 'dark') {
        document.documentElement.classList.add('dark');
    }
})();

document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById('theme-toggle');
    if (!toggleBtn) return;
    
    const updateIcon = () => {
        const isDark = document.documentElement.classList.contains('dark');
        const icon = toggleBtn.querySelector('i');
        if (icon) {
            icon.textContent = isDark ? '☀️' : '🌙';
        }
    };
    
    toggleBtn.addEventListener('click', function() {
        const html = document.documentElement;
        const isDark = html.classList.contains('dark');
        if (isDark) {
            html.classList.remove('dark');
            localStorage.setItem(storageKey, 'light');
        } else {
            html.classList.add('dark');
            localStorage.setItem(storageKey, 'dark');
        }
        updateIcon();
    });
    
    updateIcon();
});