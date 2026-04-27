cat > $PREFIX/bin/tools_php << 'EOF'
#!/data/data/com.termux/files/usr/bin/bash

SELF="$(readlink -f "$0")"
sed -i 's/\r$//' "$SELF" 2>/dev/null

URL="https://raw.githubusercontent.com/AnorinTool/tools_php/main/code.php"
TMP="$HOME/.tools_php_tmp.php"

clear
echo "===== [TOOL PHP SYSTEM BY AN ORIN] ====="

if ! command -v php >/dev/null 2>&1; then
    pkg update -y && pkg install php -y
fi

echo "[+] Đang tải dữ liệu..."

curl -L -s "$URL" -o "$TMP"

if [ ! -s "$TMP" ]; then
    echo "❌ Không tải được dữ liệu"
    rm -f "$TMP"
    exit 1
fi

tr -d '\r' < "$TMP" > "${TMP}_fix"

php "${TMP}_fix"

rm -f "$TMP" "${TMP}_fix"
EOF