#!/data/data/com.termux/files/usr/bin/bash

clear
echo "=== INSTALL TOOL PHP SYSTEM ==="

# Check PHP
if ! command -v php >/dev/null 2>&1; then
    echo "[+] Đang cài PHP..."
    pkg update -y && pkg install php -y
fi

URL="https://raw.githubusercontent.com/AnorinTool/tools_php/refs/heads/main/code.php"
DEST="$PREFIX/bin/tools_php"

echo "[+] Đang tải tool..."

curl -L "$URL" -o "$DEST"

# FIX CRLF
sed -i 's/\r$//' "$DEST"

chmod +x "$DEST"

echo ""
echo "✅ Cài đặt hoàn tất!"
echo "👉 Gõ: tools_php"