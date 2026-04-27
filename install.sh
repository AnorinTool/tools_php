rm -f $PREFIX/bin/tools_php && printf '%s\n' '#!/data/data/com.termux/files/usr/bin/bash

URL="https://raw.githubusercontent.com/AnorinTool/tools_php/refs/heads/main/code.php"
TMP="$HOME/.tools_php_tmp.sh"

clear
echo "===== [TOOL PHP SYSTEM BY AN ORIN] ====="

echo "[+] Đang tải tool..."

curl -L -s "$URL" -o "$TMP"

if [ ! -s "$TMP" ]; then
    echo "❌ Không tải được code"
    exit 1
fi

# fix CRLF
tr -d "\r" < "$TMP" > "${TMP}_fix"

chmod +x "${TMP}_fix"

# 🔥 CHẠY BẰNG BASH (KHÔNG PHẢI PHP)
bash "${TMP}_fix"

rm -f "$TMP" "${TMP}_fix"
' > $PREFIX/bin/tools_php && chmod +x $PREFIX/bin/tools_php && hash -r