<?php

date_default_timezone_set("Asia/Ho_Chi_Minh");

/* ================== CONFIG ================== */
$api = "https://5g142.wiremockapi.cloud/api/api_key";

/* ================== CHECK CLI ================== */
if (php_sapi_name() !== 'cli') {
    exit("Chỉ chạy bằng CLI\n");
}

/* ================== NHẬP KEY ================== */
@system("clear");
echo "=== [URL Decode Tool - An Orin] ===\n\n";
echo "Nhập Key: ";
$key = trim(fgets(STDIN));

if ($key === "") {
    exit("Key không được để trống\n");
}

/* ================== CALL API ================== */
$context = stream_context_create([
    "http" => [
        "timeout" => 10,
        "header" => "User-Agent: AnorinURLDecode/1.0\r\n"
    ]
]);

$response = @file_get_contents($api, false, $context);

if (!$response) {
    exit("Không kết nối được API\n");
}

$data = json_decode($response, true);

if (!$data || !isset($data['status'])) {
    exit("API lỗi\n");
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
                exit("Format ngày sai\n");
            }

            if ($date->getTimestamp() < time()) {
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

/* ================== HIỂN THỊ ================== */
echo "Key hợp lệ\n";
sleep(1);
@system("clear");

echo "=== [URL Decode Tool - An Orin] ===\n\n";
echo "----------[Thông Tin]----------\n";
echo "User: $user\n";
echo "Hôm nay: $today\n";
echo "Hết hạn: $expire\n";
echo "-------------------------------\n";

/* ================== ENGINE DECODE ================== */

function giaima($input) {
    $reverse_mapping = [
        '⊀'=>'a','⊁'=>'b','⊂'=>'c','⊃'=>'d','⊄'=>'e',
        '⊅'=>'f','⊆'=>'g','⊇'=>'h','⊈'=>'i','⊉'=>'j',
        '⊊'=>'k','⊋'=>'l','⊏'=>'m','⊐'=>'n','⊑'=>'o',
        '⊒'=>'p','⊜'=>'q','⊖'=>'r','⊗'=>'s','⊘'=>'t',
        '⊙'=>'u','⊚'=>'v','⊛'=>'w','⊕'=>'x','⊝'=>'y',
        '⊞'=>'z','⊟'=>'0','⊠'=>'1','⊡'=>'2','⊢'=>'3',
        '⊣'=>'4','⊤'=>'5','⊥'=>'6','♘'=>'7','⊧'=>'8',
        '♙'=>'9','☾'=>'A','☽'=>'B','⊫'=>'C','⊬'=>'D',
        '⊭'=>'E','⊮'=>'F','⊯'=>'G','⊰'=>'H','⊱'=>'I',
        '⊲'=>'J','⊳'=>'K','⊴'=>'L','⊵'=>'M','⊩'=>'N',
        '☼'=>'O','⊾'=>'P','⊿'=>'Q','⋀'=>'R','⋁'=>'S',
        '⋂'=>'T','⋃'=>'U','⋄'=>'V','♖'=>'W','♗'=>'X',
        '♕'=>'Y','♔'=>'Z'
    ];

    $decoded = base64_decode($input);
    $decoded_utf8 = mb_convert_encoding($decoded, 'UTF-8', 'UTF-8');

    $original = '';
    foreach (preg_split('//u', $decoded_utf8, -1, PREG_SPLIT_NO_EMPTY) as $char) {
        $original .= $reverse_mapping[$char] ?? $char;
    }

    return $original;
}

function giaimaFile($inputFile) {
    if (!file_exists($inputFile)) return false;
    return giaima(file_get_contents($inputFile));
}

function giaimaURL($url) {
    $content = @file_get_contents($url);
    if ($content === false) return false;
    return giaima($content);
}

/* ================== MENU ================== */

echo "\nChọn chế độ:\n";
echo "1. Giải mã từ file\n";
echo "2. Giải mã từ URL\n";
echo "Chọn: ";
$option = trim(fgets(STDIN));

/* ================== HANDLE ================== */

if ($option == '1') {

    echo "File cần decode: ";
    $inputFile = trim(fgets(STDIN));

    echo "File output: ";
    $outputFile = trim(fgets(STDIN));

    $decoded = giaimaFile($inputFile);

    if ($decoded === false) {
        exit("Lỗi đọc file\n");
    }

    echo "Xóa '<?php'? (y/n): ";
    if (strtolower(trim(fgets(STDIN))) === 'y') {
        $decoded = str_replace('<?php', '', $decoded);
    }

    file_put_contents($outputFile, $decoded);
    echo "Đã lưu: $outputFile\n";

    echo "Xóa file gốc? (y/n): ";
    if (strtolower(trim(fgets(STDIN))) === 'y') {
        unlink($inputFile);
        echo "Đã xóa file gốc\n";
    }

} elseif ($option == '2') {

    echo "Nhập URL: ";
    $url = trim(fgets(STDIN));

    echo "File output: ";
    $outputFile = trim(fgets(STDIN));

    $decoded = giaimaURL($url);

    if ($decoded === false) {
        exit("Không đọc được URL\n");
    }

    echo "Xóa '<?php'? (y/n): ";
    if (strtolower(trim(fgets(STDIN))) === 'y') {
        $decoded = str_replace('<?php', '', $decoded);
    }

    file_put_contents($outputFile, $decoded);
    echo "Đã lưu: $outputFile\n";

} else {
    echo "Sai lựa chọn\n";
}