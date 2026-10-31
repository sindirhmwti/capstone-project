import './bootstrap';

/**
 * Temukerja — Global JavaScript
 * Sidebar toggle, mobile menu, notifications, etc.
 */

// ─── Sidebar Toggle ───────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const sidebar       = document.getElementById('sidebar');
    const sidebarOverlay = document.getElementById('sidebar-overlay');

    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
            if (sidebarOverlay) sidebarOverlay.classList.toggle('hidden');
        });
    }

    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', () => {
            sidebar.classList.add('-translate-x-full');
            sidebarOverlay.classList.add('hidden');
        });
    }

    // ─── Mobile Navbar Menu ─────────────────────────────────────────
    const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
    const mobileMenu       = document.getElementById('mobile-menu');

    if (mobileMenuToggle && mobileMenu) {
        mobileMenuToggle.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    }

    // ─── User Dropdown ──────────────────────────────────────────────
    const userMenuButton = document.getElementById('user-menu-button');
    const userDropdown   = document.getElementById('user-dropdown');

    if (userMenuButton && userDropdown) {
        userMenuButton.addEventListener('click', (e) => {
            e.stopPropagation();
            userDropdown.classList.toggle('hidden');
        });

        document.addEventListener('click', () => {
            userDropdown.classList.add('hidden');
        });
    }

    // ─── Notification Dropdown ──────────────────────────────────────
    const notifButton   = document.getElementById('notif-button');
    const notifDropdown = document.getElementById('notif-dropdown');

    if (notifButton && notifDropdown) {
        notifButton.addEventListener('click', (e) => {
            e.stopPropagation();
            notifDropdown.classList.toggle('hidden');
        });

        document.addEventListener('click', () => {
            notifDropdown.classList.add('hidden');
        });
    }

    // ─── Flash message auto-dismiss ─────────────────────────────────
    const flashMessages = document.querySelectorAll('[data-auto-dismiss]');
    flashMessages.forEach(msg => {
        setTimeout(() => {
            msg.style.opacity = '0';
            msg.style.transition = 'opacity 0.5s';
            setTimeout(() => msg.remove(), 500);
        }, 4000);
    });
});
