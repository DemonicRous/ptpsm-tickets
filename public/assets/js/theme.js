(function() {
    const storageKey = 'ptpsm-theme';
    const theme = localStorage.getItem(storageKey) || 'light';
    if (theme === 'dark') {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }
})();

document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById('theme-toggle');
    if (!toggleBtn) return;
    toggleBtn.addEventListener('click', function() {
        const html = document.documentElement;
        const isDark = html.classList.contains('dark');
        if (isDark) {
            html.classList.remove('dark');
            localStorage.setItem('ptpsm-theme', 'light');
        } else {
            html.classList.add('dark');
            localStorage.setItem('ptpsm-theme', 'dark');
        }
    });
});