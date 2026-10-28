
'use strict';

document.addEventListener('DOMContentLoaded', () => {
    if (window.lucide) {
        window.lucide.createIcons();
    }

    const sidebar = document.querySelector('.sidebar');
    const toggle = document.querySelector('.sidebar-toggle');
    const body = document.body;

    if (!sidebar || !toggle) return;

    const syncSidebarState = (isOpen) => {
        body.classList.toggle('sidebar-open', isOpen);
        toggle.setAttribute('aria-expanded', String(isOpen));
        toggle.setAttribute(
            'aria-label',
            isOpen ? 'Fechar menu' : 'Abrir menu'
        );
    };

    toggle.addEventListener('click', () => {
        syncSidebarState(!body.classList.contains('sidebar-open'));
    });

    document.querySelectorAll('.nav-item').forEach((item) => {
        item.addEventListener('click', () => {
            if (window.innerWidth <= 991) {
                syncSidebarState(false);
            }
        });
    });

    window.addEventListener('resize', () => {
        if (window.innerWidth > 991) {
            syncSidebarState(false);
        }
    });

    document.addEventListener('click', (event) => {
        if (window.innerWidth > 991) return;

        if (
            !sidebar.contains(event.target) &&
            !toggle.contains(event.target) &&
            body.classList.contains('sidebar-open')
        ) {
            syncSidebarState(false);
        }
    });
});
