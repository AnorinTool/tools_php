#!/data/data/com.termux/files/usr/bin/bash

clear
echo "=== INSTALL TOOL PHP SYSTEM ==="

# kiểm tra php
command -v php >/dev/null 2>&1 || pkg install php -y

# tải tool
curl -s https://5g142.wiremockapi.cloud/shtool/tools_php_main -o $PREFIX/bin/tools_php

# cấp quyền
chmod +x $PREFIX/bin/tools_php

echo ""
echo "✅ Cài đặt hoàn tất"
echo "👉 Gõ: tools_php"
