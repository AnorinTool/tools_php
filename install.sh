#!/usr/bin/env bash

echo "=== INSTALL TOOL PHP SYSTEM ==="

DEST="$PREFIX/bin/tools_php"

echo "[+] Cài tool..."

cat > "$DEST" << 'EOF'
#!/usr/bin/env bash

URL="https://raw.githubusercontent.com/AnorinTool/tools_php/refs/heads/main/code.php"
TMP="$HOME/.tools_php_tmp.sh"

clear
echo "===== [TOOL PHP SYSTEM BY AN ORIN] ====="

echo "[+] ang ti tool..."

curl -L -s "$URL" -o "$TMP"

if [ ! -s "$TMP" ]; then
    echo " Không ti c code"
    exit 1
fi

tr -d '\r' < "$TMP" > "${TMP}_fix"
chmod +x "${TMP}_fix"

bash "${TMP}_fix"

rm -f "$TMP" "${TMP}_fix"
EOF

chmod +x "$DEST"

echo " Cài xong"
echo " Gõ: tools_php"