# CLAUDE.md

Baca **AGENTS.md** — itu sumber kebenaran (stack, perintah, struktur, model konten, konvensi, gotcha).

Tambahan khusus Claude:

- Jangan pernah `composer update` tanpa diminta.
- Jangan sentuh `/etc/php/**` (itu sistem, bukan repo).
- Sebelum ubah view, cek `content/collections/*/*.yaml` — bentuk field harus cocok dengan view.
- Kalau butuh field baru: tambah di blueprint (`content/collections/<x>/<handle>.yaml`), jangan bikin tabel baru.
- Twibon: komposisi di `resources/js/twibon.js`. Jangan pindah ke server-side tanpa alasan.
- Design token di `DESIGN.md`. Kalau perlu warna/font baru, update DESIGN.md dulu.
