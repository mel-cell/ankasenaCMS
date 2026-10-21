#!/usr/bin/env bash
# Buka/tutup port dev di nftables — SEMENTARA, bisa dihapus kapan saja.
#   sudo bash bin/firewall.sh open
#   sudo bash bin/firewall.sh close
#   sudo bash bin/firewall.sh status
#
# Kalau table/chain lo beda, timpa lewat env, mis:
#   sudo TABLE="inet filter" CHAIN="input" bash bin/firewall.sh open
set -euo pipefail

ACTION="${1:-status}"
PORT="${PORT:-7943}"
TABLE="${TABLE:-inet filter}"
CHAIN="${CHAIN:-input}"
COMMENT="ankasena-temp"

[ "$(id -u)" -eq 0 ] || { echo "jalankan dengan sudo"; exit 1; }

# shellcheck disable=SC2086
case "$ACTION" in
    open)
        if nft list chain $TABLE $CHAIN 2>/dev/null | grep -q "$COMMENT"; then
            echo "port $PORT sudah terbuka (rule $COMMENT ada)"
            exit 0
        fi
        nft add rule $TABLE $CHAIN tcp dport "$PORT" accept comment "\"$COMMENT\""
        echo "port $PORT dibuka sementara di [$TABLE] chain [$CHAIN]"
        nft -a list chain $TABLE $CHAIN | grep "$COMMENT" || true
        ;;
    close)
        handles="$(nft -a list chain $TABLE $CHAIN 2>/dev/null | awk -v c="$COMMENT" 'index($0,c){print $NF}')"
        if [ -z "$handles" ]; then
            echo "tidak ada rule $COMMENT — port sudah tertutup"
            exit 0
        fi
        for h in $handles; do
            nft delete rule $TABLE $CHAIN handle "$h"
            echo "rule handle $h dihapus"
        done
        ;;
    status)
        nft -a list chain $TABLE $CHAIN 2>/dev/null | grep "$COMMENT" \
            && echo "(port $PORT TERBUKA)" \
            || echo "port $PORT tertutup (tidak ada rule $COMMENT)"
        ;;
    *)
        echo "pakai: sudo bash bin/firewall.sh open|close|status"
        exit 1
        ;;
esac
