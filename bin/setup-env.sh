#!/usr/bin/env bash
# Setup environment Ankasena: extension PHP + database PostgreSQL.
# Jalankan sekali:  sudo bash bin/setup-env.sh
set -euo pipefail

[ "$(id -u)" -eq 0 ] || { echo "jalankan dengan sudo: sudo bash bin/setup-env.sh"; exit 1; }

PHP_VER=8.4

echo "==> 1/3 install extension PHP"
apt-get install -y \
  "php${PHP_VER}-mbstring" "php${PHP_VER}-bcmath" "php${PHP_VER}-gd" \
  "php${PHP_VER}-pgsql" "php${PHP_VER}-zip" "php${PHP_VER}-intl" \
  "php${PHP_VER}-xml" "php${PHP_VER}-curl"

echo "==> 2/3 matikan pdo_mysql ganda di php.ini (penyebab warning pdo_dbh_ce)"
for ini in "/etc/php/${PHP_VER}/cli/php.ini" "/etc/php/${PHP_VER}/apache2/php.ini"; do
  [ -f "$ini" ] && sed -i 's/^extension=pdo_mysql$/;extension=pdo_mysql/' "$ini"
done

echo "==> 3/3 buat database ankasena (instance Postgres 127.0.0.1:5432)"
php <<'PHP'
<?php
try {
    $pdo = new PDO('pgsql:host=127.0.0.1;port=5432;dbname=postgres', 'postgres', 'postgres', [
        PDO::ATTR_TIMEOUT => 5,
    ]);
} catch (Throwable $e) {
    fwrite(STDERR, "GAGAL konek Postgres: {$e->getMessage()}\n");
    fwrite(STDERR, "Cek user/password di .env, atau buat DB manual.\n");
    exit(1);
}
$exists = $pdo->query("SELECT 1 FROM pg_database WHERE datname = 'ankasena'")->fetchColumn();
if (!$exists) {
    $pdo->exec('CREATE DATABASE ankasena');
    echo "database ankasena dibuat\n";
} else {
    echo "database ankasena sudah ada\n";
}
PHP

echo
echo "==> verifikasi"
php -v | head -1
php -m | grep -ixE 'mbstring|bcmath|gd|pgsql|pdo_pgsql|zip|intl' || true
echo
echo "Selesai. Lanjut: php artisan migrate"
