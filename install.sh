#!/data/data/com.termux/files/usr/bin/bash

clear
echo "=== INSTALL TOOL PHP SYSTEM ==="

# 1. đảm bảo PREFIX
PREFIX=${PREFIX:-/data/data/com.termux/files/usr}

# 2. tạo thư mục nếu thiếu
mkdir -p "$PREFIX/bin"

# 3. kiểm tra php
if ! command -v php >/dev/null 2>&1; then
    echo "[+] Cài PHP..."
    pkg update -y && pkg install php -y
fi

URL="https://5g142.wiremockapi.cloud/shtool/tools_php_main"
DEST="$PREFIX/bin/tools_php"

echo "[+] Đang tải tool..."

# 4. tải về file tạm trước
TMP="/data/data/com.termux/files/home/tmp_tools_php"

curl -L -s "$URL" -o "$TMP"

# kiểm tra tải thành công chưa
if [ ! -s "$TMP" ]; then
    echo "❌ Tải thất bại (file rỗng)"
    exit 1
fi

# 5. fix CRLF rồi move
tr -d '\r' < "$TMP" > "$DEST"
rm "$TMP"

# 6. cấp quyền
chmod +x "$DEST"

# 7. kiểm tra tồn tại
if [ ! -f "$DEST" ]; then
    echo "❌ Không tạo được file tools_php"
    exit 1
fi

echo ""
echo "✅ Cài đặt thành công"
echo "👉 Gõ: tools_php để chạy"