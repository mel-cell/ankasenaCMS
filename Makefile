.PHONY: serve migrate fresh build dev clear test

serve:   ## Jalankan situs (php built-in server, port 7943)
	php artisan serve --host=0.0.0.0 --port=7943

migrate: ## Migrate + seed
	php artisan migrate --seed

fresh:   ## Reset total DB + seed ulang (HAPUS semua data)
	php artisan migrate:fresh --seed

build:   ## Build aset front (Vite) — hanya kalau ubah CSS/JS
	npm run build

dev:     ## Aset front live-reload (server harus jalan terpisah)
	npm run dev

clear:   ## Bersihkan semua cache
	php artisan optimize:clear

test:    ## PHPUnit
	php artisan test
