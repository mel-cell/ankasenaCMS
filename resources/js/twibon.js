// Komposisi twibon Ankasena 1080x1080 (ponytail: ganti KOMPOSISI kalau desain berubah).
const KOMPOSISI = {
    bg: '#003049',
    aksen: '#FCBF49',
    aksen2: '#F77F00',
    fg: '#F5EFE0',
    // Slot foto: x,y,w,h + radius sudut.
    slot: { x: 190, y: 170, w: 700, h: 700, r: 48 },
    judulY: 110,
    bandY: 940,
};

const cv = document.getElementById('twibon-canvas');
if (cv) {
    const ctx = cv.getContext('2d');
    cv.width = 1080;
    cv.height = 1080;

    const foto = new Image();
    let zoom = 1;
    let ox = 0; // offset geser (px canvas)
    let oy = 0;
    const NAMA = cv.dataset.nama || 'ANKASENA';
    const PERIODE = cv.dataset.periode || '';

    function gambarFrame() {
        ctx.fillStyle = KOMPOSISI.bg;
        ctx.fillRect(0, 0, 1080, 1080);

        // Judul atas.
        ctx.fillStyle = KOMPOSISI.aksen;
        ctx.font = '700 44px system-ui, sans-serif';
        ctx.textAlign = 'center';
        ctx.fillText('TWIBON', 540, KOMPOSISI.judulY);
        ctx.fillStyle = KOMPOSISI.fg;
        ctx.font = '600 30px system-ui, sans-serif';
        ctx.fillText(NAMA + (PERIODE ? ' · ' + PERIODE : ''), 540, KOMPOSISI.judulY + 46);

        // Band bawah.
        ctx.fillStyle = KOMPOSISI.aksen;
        ctx.fillRect(0, KOMPOSISI.bandY, 1080, 140);
        ctx.fillStyle = KOMPOSISI.bg;
        ctx.font = '800 72px system-ui, sans-serif';
        ctx.fillText(NAMA, 540, KOMPOSISI.bandY + 90);
    }

    function gambar() {
        gambarFrame();

        const s = KOMPOSISI.slot;
        if (foto.src) {
            ctx.save();
            rounded(s.x, s.y, s.w, s.h, s.r);
            ctx.clip();
            // Cover: skala terkecil yang menutup slot, lalu zoom + offset drag.
            const skala = Math.max(s.w / foto.width, s.h / foto.height) * zoom;
            const w = foto.width * skala;
            const h = foto.height * skala;
            ctx.drawImage(foto, s.x + (s.w - w) / 2 + ox, s.y + (s.h - h) / 2 + oy, w, h);
            ctx.restore();
        } else {
            ctx.save();
            rounded(s.x, s.y, s.w, s.h, s.r);
            ctx.fillStyle = 'rgba(255,255,255,.08)';
            ctx.fill();
            ctx.restore();
            ctx.fillStyle = 'rgba(245,239,224,.5)';
            ctx.font = '400 30px system-ui, sans-serif';
            ctx.fillText('Unggah fotomu dulu', 540, s.y + s.h / 2);
        }

        // Bingkai slot di atas foto.
        ctx.strokeStyle = KOMPOSISI.aksen;
        ctx.lineWidth = 8;
        rounded(s.x, s.y, s.w, s.h, s.r);
        ctx.stroke();
    }

    function rounded(x, y, w, h, r) {
        ctx.beginPath();
        ctx.moveTo(x + r, y);
        ctx.arcTo(x + w, y, x + w, y + h, r);
        ctx.arcTo(x + w, y + h, x, y + h, r);
        ctx.arcTo(x, y + h, x, y, r);
        ctx.arcTo(x, y, x + w, y, r);
        ctx.closePath();
    }

    document.getElementById('twibon-foto').addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (!file) return;
        zoom = 1;
        ox = oy = 0;
        document.getElementById('twibon-zoom').value = 1;
        foto.onload = gambar;
        foto.src = URL.createObjectURL(file);
    });

    document.getElementById('twibon-zoom').addEventListener('input', (e) => {
        zoom = parseFloat(e.target.value);
        gambar();
    });

    // Drag untuk geser posisi foto.
    let seret = null;
    const skalaLayar = () => 1080 / cv.clientWidth;
    cv.addEventListener('pointerdown', (e) => {
        seret = { x: e.clientX, y: e.clientY, ox, oy };
        cv.setPointerCapture(e.pointerId);
    });
    cv.addEventListener('pointermove', (e) => {
        if (!seret) return;
        ox = seret.ox + (e.clientX - seret.x) * skalaLayar();
        oy = seret.oy + (e.clientY - seret.y) * skalaLayar();
        gambar();
    });
    cv.addEventListener('pointerup', () => (seret = null));

    document.getElementById('twibon-unduh').addEventListener('click', () => {
        const a = document.createElement('a');
        a.download = 'twibon-' + NAMA.toLowerCase() + '.png';
        a.href = cv.toDataURL('image/png');
        a.click();
    });

    gambar();
}
