#!/data/data/com.termux/files/usr/bin/bash

# ===== CONFIG =====
URL="https://raw.githubusercontent.com/AnorinTool/tools_php/main/code.php"
TMP="/data/data/com.termux/files/home/.tools_php_tmp.php"

clear
echo "===== [TOOL PHP SYSTEM BY AN ORIN] ====="
echo ""

# ===== CHECK PHP =====
if ! command -v php >/dev/null 2>&1; then
    echo "[+] Đang cài PHP..."
    pkg update -y && pkg install php -y
fi

# ===== LOAD CODE =====
echo "[+] Đang tải tool..."

curl -L -s "$URL" > "$TMP"

# kiểm tra file tải
if [ ! -s "$TMP" ]; then
    echo "❌ Lỗi tải dữ liệu (file rỗng hoặc lỗi mạng)"
    rm -f "$TMP"
    exit 1
fi

# ===== FIX CRLF =====
tr -d '\r' < "$TMP" > "${TMP}_fix"

# ===== EXECUTE =====
php "${TMP}_fix"

# ===== CLEAN =====
rm -f "$TMP" "${TMP}_fix"