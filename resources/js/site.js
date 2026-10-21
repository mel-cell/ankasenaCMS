const toggle = document.getElementById('nav-toggle');
const menu = document.getElementById('nav-mobile');

if (toggle && menu) {
    toggle.addEventListener('click', () => {
        const open = toggle.getAttribute('aria-expanded') === 'true';
        toggle.setAttribute('aria-expanded', String(!open));
        menu.hidden = open;
    });
}
