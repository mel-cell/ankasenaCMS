// Entry Vite (dipakai layouts/site). Interaksi nav/mode/bahasa
// ada inline di partial nav & footer, dekat markup-nya masing-masing.

// Reveal on scroll — native IntersectionObserver, tanpa plugin.
// (ponytail: ganti ke AOS kalau nanti butuh arah/durasi beda-beda per elemen.)
(function () {
    var sections = document.querySelectorAll('main section');
    var cards = document.querySelectorAll('main .card');

    if (!('IntersectionObserver' in window)) {
        return; // tanpa dukungan: tampil langsung, tanpa animasi
    }

    sections.forEach(function (el) { el.classList.add('reveal'); });

    // Kartu dalam satu grid: stagger 70ms.
    cards.forEach(function (el) {
        el.classList.add('reveal');
        var i = Array.prototype.indexOf.call(el.parentElement.children, el);
        el.style.transitionDelay = Math.min(i * 70, 280) + 'ms';
    });

    var io = new IntersectionObserver(function (entries) {
        entries.forEach(function (e) {
            if (!e.isIntersecting) return;
            e.target.classList.add('in');
            io.unobserve(e.target);
        });
    }, { threshold: 0.12, rootMargin: '0px 0px -6% 0px' });

    sections.forEach(function (el) { io.observe(el); });
    cards.forEach(function (el) { io.observe(el); });
})();
