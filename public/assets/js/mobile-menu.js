document.addEventListener('DOMContentLoaded', function() {
    const burger = document.getElementById('mobile-menu-button');
    const sidebar = document.getElementById('mobile-sidebar');
    const overlay = document.getElementById('mobile-overlay');
    
    if (!burger || !sidebar || !overlay) return;
    
    function openMenu() {
        sidebar.classList.remove('-translate-x-full');
        overlay.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    
    function closeMenu() {
        sidebar.classList.add('-translate-x-full');
        overlay.classList.add('hidden');
        document.body.style.overflow = '';
    }
    
    burger.addEventListener('click', openMenu);
    overlay.addEventListener('click', closeMenu);
    
    // Закрытие по Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !sidebar.classList.contains('-translate-x-full')) {
            closeMenu();
        }
    });
});