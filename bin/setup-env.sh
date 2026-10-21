#!/usr/bin/env bash
# Setup + inisialisasi Ankasena: extension PHP, PostgreSQL, Redis, Statamic.
# Jalankan sekali:  sudo bash bin/setup-env.sh
set -euo pipefail

[ "$(id -u)" -eq 0 ] || { echo "jalankan dengan sudo: sudo bash bin/setup-env.sh"; exit 1; }

PHP_VER=8.4
PROJ="$(cd "$(dirname "$0")/.." && pwd)"
OWNER="$(stat -c '%U' "$PROJ")"

echo "==> 1/6 install extension PHP"
apt-get install -y \
  "php${PHP_VER}-mbstring" "php${PHP_VER}-bcmath" "php${PHP_VER}-gd" \
  "php${PHP_VER}-pgsql" "php${PHP_VER}-zip" "php${PHP_VER}-intl" \
  "php${PHP_VER}-xml" "php${PHP_VER}-curl" "php${PHP_VER}-redis"

echo "==> 2/6 matikan pdo_mysql ganda di php.ini (penyebab warning pdo_dbh_ce)"
for ini in "/etc/php/${PHP_VER}/cli/php.ini" "/etc/php/${PHP_VER}/apache2/php.ini"; do
  [ -f "$ini" ] && sed -i 's/^extension=pdo_mysql$/;extension=pdo_mysql/' "$ini"
done

echo "==> 3/6 cek database dari .env"
set -a; . "$PROJ/.env"; set +a
php <<'PHP'
<?php
$host = getenv('DB_HOST'); $port = getenv('DB_PORT');
$user = getenv('DB_USERNAME'); $pass = getenv('DB_PASSWORD'); $db = getenv('DB_DATABASE');
try {
    $pdo = new PDO("pgsql:host={$host};port={$port};dbname=postgres", $user, $pass, [PDO::ATTR_TIMEOUT => 5]);
} catch (Throwable $e) {
    fwrite(STDERR, "GAGAL konek Postgres: {$e->getMessage()}
");
    fwrite(STDERR, "Perbaiki DB_* di .env lalu jalankan ulang.
");
    exit(1);
}
$exists = $pdo->query("SELECT 1 FROM pg_database WHERE datname = " . $pdo->quote($db))->fetchColumn();
if (!$exists) { $pdo->exec('CREATE DATABASE "' . $db . '"'); echo "database {$db} dibuat
"; }
else { echo "database {$db} sudah ada
"; }
PHP

echo "==> 4/6 cek Redis"
if php -r 'try{$r=new Redis();$r->connect("127.0.0.1",6379,1.0);$r->ping();exit(0);}catch(Throwable $e){exit(1);}' >/dev/null 2>&1; then
  sed -i 's/^CACHE_STORE=.*/CACHE_STORE=redis/; s/^SESSION_DRIVER=.*/SESSION_DRIVER=redis/; s/^QUEUE_CONNECTION=.*/QUEUE_CONNECTION=redis/' "$PROJ/.env"
  echo "Redis OK — cache, session, queue dipindah ke Redis"
else
  echo "Redis tidak bisa diakses — tetap pakai file driver"
fi
chown "$OWNER" "$PROJ/.env"

echo "==> 5/6 inisialisasi Statamic + migrate"
runuser -u "$OWNER" -- bash -c "cd '$PROJ' && php artisan statamic:install --no-interaction"
runuser -u "$OWNER" -- bash -c "cd '$PROJ' && php artisan migrate --force"
runuser -u "$OWNER" -- bash -c "cd '$PROJ' && php artisan storage:link || true"

echo "==> 6/6 verifikasi"
php -v | head -1
php -m | grep -ixE 'mbstring|bcmath|gd|pgsql|pdo_pgsql|redis|zip|intl'
runuser -u "$OWNER" -- bash -c "cd '$PROJ' && php artisan about 2>/dev/null | head -30" || true

echo
echo "Selesai. Langkah berikutnya:"
echo "  cd $PROJ"
echo "  php please make:user        # bikin akun admin /cp"
echo "  php artisan serve --host=0.0.0.0 --port=8000"
