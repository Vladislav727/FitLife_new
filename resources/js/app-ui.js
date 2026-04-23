const toastIcons = {
    success: '<svg viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>',
    error: '<svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>',
    warning: '<svg viewBox="0 0 24 24"><path d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-8h2v8z"/></svg>',
    info: '<svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>',
};

function setupHeaderScroll() {
    const header = document.getElementById('mainHeader');

    if (!header) {
        return;
    }

    const syncHeaderState = () => {
        header.classList.toggle('scrolled', window.scrollY > 20);
    };

    syncHeaderState();
    window.addEventListener('scroll', syncHeaderState, { passive: true });
}

function setupUserMenu() {
    const userMenu = document.getElementById('userMenu');
    const userMenuTrigger = document.getElementById('userMenuTrigger');

    if (!userMenu || !userMenuTrigger) {
        return;
    }

    userMenuTrigger.addEventListener('click', (event) => {
        event.stopPropagation();
        userMenu.classList.toggle('open');
    });

    document.addEventListener('click', (event) => {
        if (!userMenu.contains(event.target)) {
            userMenu.classList.remove('open');
        }
    });
}

function setupMobileMenu() {
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const mobileMenuPanel = document.getElementById('mobileMenuPanel');
    const mobileMenuOverlay = document.getElementById('mobileMenuOverlay');
    const mobileMenuClose = document.getElementById('mobileMenuClose');

    if (!mobileMenuBtn || !mobileMenuPanel || !mobileMenuOverlay || !mobileMenuClose) {
        return;
    }

    const openMobileMenu = () => {
        mobileMenuPanel.classList.add('open');
        mobileMenuOverlay.classList.add('open');
        document.body.style.overflow = 'hidden';
    };

    const closeMobileMenu = () => {
        mobileMenuPanel.classList.remove('open');
        mobileMenuOverlay.classList.remove('open');
        document.body.style.overflow = '';
    };

    mobileMenuBtn.addEventListener('click', openMobileMenu);
    mobileMenuClose.addEventListener('click', closeMobileMenu);
    mobileMenuOverlay.addEventListener('click', closeMobileMenu);
}

function setupToastSystem() {
    const rawToastMessages = document.body?.dataset.toastMessages;
    window.toastMessages = rawToastMessages ? JSON.parse(rawToastMessages) : {};

    window.toast = {
        container: null,
        init() {
            if (!this.container) {
                this.container = document.createElement('div');
                this.container.className = 'toast-container';
                document.body.appendChild(this.container);
            }
        },
        show(message, type = 'info', duration = 3000) {
            this.init();

            const toast = document.createElement('div');
            toast.className = `toast toast-${type}`;
            toast.innerHTML = `
                <div class="toast-icon">${toastIcons[type] || toastIcons.info}</div>
                <div class="toast-message">${message}</div>
                <button class="toast-close" type="button" aria-label="Close toast">
                    <svg viewBox="0 0 24 24"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>
                </button>
            `;

            toast.querySelector('.toast-close')?.addEventListener('click', () => {
                toast.remove();
            });

            this.container.appendChild(toast);

            requestAnimationFrame(() => {
                toast.classList.add('show');
            });

            window.setTimeout(() => {
                toast.classList.remove('show');
                window.setTimeout(() => toast.remove(), 300);
            }, duration);
        },
        success(message, duration) {
            this.show(message, 'success', duration);
        },
        error(message, duration) {
            this.show(message, 'error', duration);
        },
        warning(message, duration) {
            this.show(message, 'warning', duration);
        },
        info(message, duration) {
            this.show(message, 'info', duration);
        },
    };
}

function setupConfirmationModal() {
    const backdrop = document.getElementById('fitConfirmBackdrop');
    const messageElement = document.getElementById('fitConfirmMessage');
    const confirmButton = document.getElementById('fitConfirmYes');
    const cancelButton = document.getElementById('fitConfirmNo');

    if (!backdrop || !messageElement || !confirmButton || !cancelButton) {
        return;
    }

    let resolvePromise = null;

    const close = (result) => {
        backdrop.classList.remove('open');
        backdrop.setAttribute('aria-hidden', 'true');

        if (resolvePromise) {
            resolvePromise(result);
            resolvePromise = null;
        }
    };

    const open = (message) => {
        messageElement.textContent = message;
        backdrop.classList.add('open');
        backdrop.setAttribute('aria-hidden', 'false');
        confirmButton.focus();

        return new Promise((resolve) => {
            resolvePromise = resolve;
        });
    };

    confirmButton.addEventListener('click', () => close(true));
    cancelButton.addEventListener('click', () => close(false));
    backdrop.addEventListener('click', (event) => {
        if (event.target === backdrop) {
            close(false);
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && backdrop.classList.contains('open')) {
            close(false);
        }
    });

    window.confirmAsync = open;

    document.addEventListener('submit', (event) => {
        const form = event.target;
        const message = form.dataset.confirm || event.submitter?.dataset?.confirm;

        if (!message || form._fitconfirmed) {
            return;
        }

        event.preventDefault();

        open(message).then((confirmed) => {
            if (!confirmed) {
                return;
            }

            form._fitconfirmed = true;

            if (form.requestSubmit) {
                form.requestSubmit(event.submitter);
            } else {
                form.submit();
            }

            window.setTimeout(() => {
                form._fitconfirmed = false;
            }, 500);
        });
    }, true);
}

document.addEventListener('DOMContentLoaded', () => {
    setupHeaderScroll();
    setupUserMenu();
    setupMobileMenu();
    setupToastSystem();
    setupConfirmationModal();
});