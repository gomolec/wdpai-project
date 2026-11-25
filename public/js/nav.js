const menuToggle = document.querySelector('[data-menu-toggle]');
const mobileNav = document.getElementById('mobile-nav-drawer');

if (menuToggle && mobileNav) {
    menuToggle.addEventListener('click', () => {
        const isOpen = menuToggle.getAttribute('aria-expanded') === 'true';
        const nextOpen = !isOpen;

        menuToggle.setAttribute('aria-expanded', String(nextOpen));
        menuToggle.setAttribute(
            'aria-label',
            nextOpen ? 'Zamknij menu nawigacji' : 'Otwórz menu nawigacji'
        );
        mobileNav.hidden = !nextOpen;
    });
}
