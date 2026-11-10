
'use strict';

document.addEventListener('DOMContentLoaded', () => {
    if (window.lucide) {
        window.lucide.createIcons();
    }

    const sidebar = document.querySelector('.sidebar');
    const toggle = document.querySelector('.sidebar-toggle');
    const body = document.body;

    const logoutOpen = document.getElementById('logoutModalOpen');
    const logoutBackdrop = document.getElementById('logoutModalBackdrop');
    const logoutCancel = document.getElementById('logoutModalCancel');
    const logoutClose = document.getElementById('logoutModalClose');
    const previousBodyOverflow = body.style.overflow;

    const closeLogoutModal = () => {
        if (!logoutBackdrop) return;
        logoutOpen?.focus();
        logoutBackdrop.classList.add('hidden');
        logoutBackdrop.setAttribute('aria-hidden', 'true');
        body.style.overflow = previousBodyOverflow;
    };

    if (logoutOpen && logoutBackdrop && logoutCancel) {
        logoutOpen.addEventListener('click', () => {
            logoutBackdrop.classList.remove('hidden');
            logoutBackdrop.setAttribute('aria-hidden', 'false');
            body.style.overflow = 'hidden';
            logoutCancel.focus();
        });

        logoutCancel.addEventListener('click', closeLogoutModal);
        logoutClose?.addEventListener('click', closeLogoutModal);

        logoutBackdrop.addEventListener('click', (event) => {
            if (event.target === logoutBackdrop) closeLogoutModal();
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && !logoutBackdrop.classList.contains('hidden')) {
                closeLogoutModal();
            }
        });
    }

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
