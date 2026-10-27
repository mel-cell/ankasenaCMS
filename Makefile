# Ankasena — helper dev
# make          : jalan Vite + web server bareng (Ctrl-C menghentikan keduanya)
# make open     : buka port 7943 + 5173 di firewall (akses dari LAN, butuh sudo)
# make close    : tutup lagi portnya
# make status   : status rule firewall
# make clean    : bersihkan cache Laravel + Statamic

PHP  := /usr/bin/php8.4
HOST := 0.0.0.0
PORT := 7943
# IP LAN utama (interface rute default) — dipakai Vite supaya asset bisa diakses dari perangkat lain
LAN_IP := $(shell ip route get 1.1.1.1 2>/dev/null | grep -oP 'dev \K\S+' | head -1 | xargs -r ip -4 -o addr show 2>/dev/null | grep -oP 'inet \K[\d.]+' | head -1)

.DEFAULT_GOAL := run
.PHONY: run open close status clean

run:
	trap 'kill 0' INT TERM EXIT; \
	$(PHP) artisan serve --host=$(HOST) --port=$(PORT) & \
	VITE_HOST="$(LAN_IP)" npm run dev

open:
	sudo bash bin/firewall.sh open
	sudo PORT=5173 bash bin/firewall.sh open

close:
	sudo bash bin/firewall.sh close
	sudo PORT=5173 bash bin/firewall.sh close

status:
	sudo bash bin/firewall.sh status

clean:
	$(PHP) artisan optimize:clear
	$(PHP) artisan cache:clear
	$(PHP) artisan statamic:stache:clear
