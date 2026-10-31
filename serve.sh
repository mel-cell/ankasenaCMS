#!/bin/sh
# Dev server Ankasena — cwd public/ wajib (server.php pakai getcwd()), limit upload dinaikkan
# tanpa sentuh /etc/php. Jalankan: sh serve.sh
cd "$(dirname "$0")/public" || exit 1
exec php -d upload_max_filesize=25M -d post_max_size=30M -S 127.0.0.1:8899 \
    ../vendor/laravel/framework/src/Illuminate/Foundation/resources/server.php
