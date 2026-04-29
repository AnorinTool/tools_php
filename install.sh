#!/usr/bin/env bash

echo "=== [INSTALL TOOL PHP] ==="

DEST="$PREFIX/bin/tools_php"

cat > "$DEST" << 'EOF'
#!/usr/bin/env bash

URL="https://5g142.wiremockapi.cloud/shtool/tools_php_main"
TMP="$HOME/.tools_php_tmp.sh"

clear

curl -L -s "$URL" -o "$TMP"

if [ ! -s "$TMP" ]; then
    echo "Không tải được code"
    exit 1
fi

tr -d '\r' < "$TMP" > "${TMP}_fix"
chmod +x "${TMP}_fix"

bash "${TMP}_fix"

rm -f "$TMP" "${TMP}_fix"
EOF

chmod +x "$DEST"

echo "Cài Xong Thành Công!"
echo "Gõ: tools_php"