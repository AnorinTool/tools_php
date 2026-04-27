#!/data/data/com.termux/files/usr/bin/bash

# ===== FIX CHÍNH FILE NẾU BỊ CRLF =====
SELF="$(readlink -f "$0")"
sed -i 's/\r$//' "$SELF" 2>/dev/null

# ===== CONFIG =====
URL="https://raw.githubusercontent.com/AnorinTool/tools_php/main/code.php"
TMP="$HOME/.tools_php_tmp.php"

clear
echo "===== [TOOL PHP SYSTEM BY AN ORIN] ====="
echo ""

# ===== CHECK PHP =====
if ! command -v php >/dev/null 2>&1; then
    echo "[+] Đang cài PHP..."
    pkg update -y && pkg install php -y
fi

# ===== LOAD TOOL =====
echo "[+] Đang tải dữ liệu..."

curl -L -s "$URL" -o "$TMP"

# ===== CHECK FILE =====
if [ ! -s "$TMP" ]; then
    echo "❌ Không tải được dữ liệu"
    rm -f "$TMP"
    exit 1
fi

# ===== FIX CRLF FILE PHP =====
tr -d '\r' < "$TMP" > "${TMP}_fix"

# ===== RUN =====
php "${TMP}_fix"

# ===== CLEAN =====
rm -f "$TMP" "${TMP}_fix"