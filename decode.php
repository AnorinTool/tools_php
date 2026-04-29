<?php

date_default_timezone_set("Asia/Ho_Chi_Minh");

/* ================== CONFIG ================== */
$api = "https://5g142.wiremockapi.cloud/api/api_key";
$key_file = "key_decode.txt";
$limit_file = "limit_decode.json";
$limit_max = 100; // số lần decode tối đa

/* ================== CHECK CLI ================== */
if (php_sapi_name() !== 'cli') {
    exit("Chỉ chạy bằng CLI\n");
}

/* ================== DEVICE ID ================== */
function getDeviceID() {
    return md5(
        php_uname() .
        get_current_user() .
        __FILE__
    );
}

/* ================== CLEAR ================== */
@system("clear");
echo "=== [Tool Decode By An Orin] ===\n\n";

/* ================== LOAD KEY LOCAL ================== */
$key = "";

if (file_exists($key_file)) {
    $saved = json_decode(file_get_contents($key_file), true);

    if (!empty($saved['key']) && !empty($saved['device'])) {

        if ($saved['device'] !== getDeviceID()) {
            unlink($key_file);
            exit("Key không hợp lệ trên thiết bị này\n");
        }

        echo "Phát hiện key đã lưu → Enter để dùng / nhập key mới để thay: ";
        $input = trim(fgets(STDIN));

        if ($input === "") {
            $key = $saved['key'];
        } else {
            $key = $input;
        }
    }
}

if ($key === "") {
    echo "Nhập Key: ";
    $key = trim(fgets(STDIN));
}

if ($key === "") {
    exit("Key không được để trống\n");
}

/* ================== CALL API ================== */
$context = stream_context_create([
    "http" => [
        "timeout" => 10,
        "header" => "User-Agent: AnorinDecode/5.0\r\n"
    ]
]);

$response = @file_get_contents($api, false, $context);

if (!$response) {
    exit("Không kết nối được API\n");
}

$data = json_decode($response, true);

if (!$data || !isset($data['status'])) {
    exit("API lỗi hoặc bị chỉnh sửa\n");
}

/* ================== CHECK KEY ================== */
$valid = false;
$user = "Unknown";
$expire = "";
$today = date("d-m-Y");

if ($data['status'] === 'success' && isset($data['data'])) {

    foreach ($data['data'] as $item) {

        if (empty($item['key']) || empty($item['expire'])) {
            continue;
        }

        if (md5($key) === $item['key']) {

            $date = DateTime::createFromFormat('d-m-Y', $item['expire']);

            if (!$date) {
                exit("Format ngày không hợp lệ\n");
            }

            if ($date->getTimestamp() < time()) {
                if (file_exists($key_file)) {
                    unlink($key_file);
                }
                exit("Key đã hết hạn\n");
            }

            $valid = true;
            $user = $item['note'] ?? "Unknown";
            $expire = $item['expire'];
            break;
        }
    }
}

if (!$valid) {
    exit("Key không hợp lệ\n");
}

/* ================== LƯU KEY ================== */
file_put_contents($key_file, json_encode([
    "key" => $key,
    "device" => getDeviceID()
]));

/* ================== LIMIT USAGE ================== */
if (!file_exists($limit_file)) {
    file_put_contents($limit_file, json_encode([
        "used" => 0
    ]));
}

$limit_data = json_decode(file_get_contents($limit_file), true);

if ($limit_data['used'] >= $limit_max) {
    exit("Đã đạt giới hạn sử dụng ($limit_max lần)\n");
}

$limit_data['used']++;
file_put_contents($limit_file, json_encode($limit_data));

/* ================== HIỂN THỊ ================== */
sleep(1);
@system("clear");

echo "=== [Tool Decode By An Orin] ===\n\n";
echo "-----------[Thông Tin]------------------\n";
echo "Người dùng: $user\n";
echo "Ngày kích hoạt: $today\n";
echo "Hết hạn: $expire\n";
echo "Số lần đã dùng: ".$limit_data['used']." / $limit_max\n";
echo "----------------------------------------\n";

/* ================== DECODE ================== */

echo "Nhập File Decode: ";
$input = trim(fgets(STDIN));

if (!file_exists($input)) {
    exit("Không tìm thấy file\n");
}

echo "Nhập File Xuất Decode (Enter = decoded.php): ";
$output = trim(fgets(STDIN));

if ($output === "") {
    $output = "decoded.php";
}

/* ===== READ ===== */
$code = file_get_contents($input);

/* ===== LẤY PAYLOAD ===== */
preg_match('/=\s*(array\s*\(.*?\));/s', $code, $match);

if (!isset($match[1])) {
    exit("Không tìm thấy dữ liệu encode\n");
}

/* ===== STRING -> ARRAY ===== */
$chunks = @eval("return " . $match[1] . ";");

if (!is_array($chunks)) {
    exit("Lỗi parse dữ liệu\n");
}

/* ===== GHÉP ===== */
$payload = implode('', $chunks);

/* ===== DECODE ===== */
$round = 5;

for ($i = 0; $i < $round; $i++) {
    $payload = @gzinflate(base64_decode($payload));
    if ($payload === false) {
        exit("Decode thất bại\n");
    }
}

/* ===== RESTORE ===== */
$payload = strrev($payload);

/* ===== SAVE ===== */
file_put_contents($output, $payload);

echo "Decode Thành Công: $output\n";