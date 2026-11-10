
'use strict';

document.addEventListener('DOMContentLoaded', () => {
    if (window.lucide) {
        window.lucide.createIcons();
    }

    const appNotice = document.getElementById('appNotice');

    if (appNotice) {
        const dismissNotice = () => {
            appNotice.classList.add('app-notice--leaving');
            window.setTimeout(() => appNotice.remove(), 180);
        };

        appNotice.querySelector('.app-notice__close')?.addEventListener('click', dismissNotice);
        window.setTimeout(dismissNotice, 4500);
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

    const deleteConfirmBackdrop = document.getElementById('deleteConfirmBackdrop');
    const deleteConfirmTitle = document.getElementById('deleteConfirmTitle');
    const deleteConfirmMessage = document.getElementById('deleteConfirmMessage');
    const deleteConfirmCancel = document.getElementById('deleteConfirmCancel');
    const deleteConfirmSubmit = document.getElementById('deleteConfirmSubmit');
    let pendingDeleteForm = null;
    let deleteSubmitter = null;

    const closeDeleteConfirm = () => {
        if (!deleteConfirmBackdrop) return;
        deleteConfirmBackdrop.classList.add('hidden');
        deleteConfirmBackdrop.setAttribute('aria-hidden', 'true');
        body.style.overflow = previousBodyOverflow;
        deleteSubmitter?.focus();
        pendingDeleteForm = null;
        deleteSubmitter = null;
    };

    if (
        deleteConfirmBackdrop &&
        deleteConfirmTitle &&
        deleteConfirmMessage &&
        deleteConfirmCancel &&
        deleteConfirmSubmit
    ) {
        const approvedForms = new WeakSet();

        document.addEventListener('submit', (event) => {
            const form = event.target;

            if (!(form instanceof HTMLFormElement) || !form.hasAttribute('data-confirm-title')) {
                return;
            }

            if (approvedForms.delete(form)) {
                return;
            }

            event.preventDefault();
            pendingDeleteForm = form;
            deleteSubmitter = event.submitter;
            deleteConfirmTitle.textContent = form.dataset.confirmTitle || 'Você tem certeza que deseja deletar este item?';
            deleteConfirmMessage.textContent = form.dataset.confirmMessage || 'Esta ação não poderá ser desfeita.';
            deleteConfirmBackdrop.classList.remove('hidden');
            deleteConfirmBackdrop.setAttribute('aria-hidden', 'false');
            body.style.overflow = 'hidden';
            deleteConfirmCancel.focus();
        });

        deleteConfirmCancel.addEventListener('click', closeDeleteConfirm);

        deleteConfirmSubmit.addEventListener('click', () => {
            if (!pendingDeleteForm) return;
            const form = pendingDeleteForm;
            approvedForms.add(form);
            closeDeleteConfirm();
            form.requestSubmit();
        });

        deleteConfirmBackdrop.addEventListener('click', (event) => {
            if (event.target === deleteConfirmBackdrop) closeDeleteConfirm();
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && !deleteConfirmBackdrop.classList.contains('hidden')) {
                closeDeleteConfirm();
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
