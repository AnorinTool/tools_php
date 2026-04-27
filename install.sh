#!/data/data/com.termux/files/usr/bin/bash

clear
echo "=== INSTALL TOOL PHP SYSTEM ==="

# kiểm tra php
if ! command -v php >/dev/null 2>&1; then
    echo "[+] Cài PHP..."
    pkg update -y && pkg install php -y
fi

URL="https://5g142.wiremockapi.cloud/shtool/tools_php_main"
DEST="$PREFIX/bin/tools_php"

echo "[+] Đang tải tool..."

# 🔥 FIX CRLF NGAY TỪ NGUỒN
curl -L -s "$URL" | tr -d '\r' > "$DEST"

chmod +x "$DEST"

echo ""
echo "✅ Cài đặt hoàn tất"
echo "👉 Gõ: tools_php để chạy"