<?php

date_default_timezone_set("Asia/Ho_Chi_Minh");

/* ===== RANDOM KEY ===== */
function randomKey($length = 12) {
    $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $key = '';
    for ($i = 0; $i < $length; $i++) {
        $key .= $chars[rand(0, strlen($chars) - 1)];
    }
    return $key;
}

/* ===== CONFIRM STOP ===== */
function confirmStop() {
    echo "⚠️ Bạn có chắc muốn dừng? (y/n): ";
    $c = strtolower(trim(fgets(STDIN)));
    return $c === 'y';
}

/* ===== TÍNH EXPIRE ===== */
function calcExpire($index = 0) {

    static $type = null;
    static $value = null;

    if ($type === null) {
        echo "Chọn kiểu thời hạn:\n";
        echo "[1] Theo ngày (tăng dần)\n";
        echo "[2] Theo tuần (tăng dần)\n";
        echo "[3] Theo tháng (tăng dần)\n";
        echo "[4] Nhập tay\n";
        echo "Chọn: ";
        $type = trim(fgets(STDIN));

        if ($type != "4") {
            echo "Nhập giá trị cơ bản: ";
            $value = (int)trim(fgets(STDIN));
            if ($value <= 0) $value = 1;
        }
    }

    switch ($type) {
        case "1":
            return date("d-m-Y", strtotime("+".($value + $index)." days"));

        case "2":
            return date("d-m-Y", strtotime("+".(($value * 7) + ($index * 7))." days"));

        case "3":
            return date("d-m-Y", strtotime("+".($value + $index)." months"));

        case "4":
            echo "Nhập ngày hết hạn (dd-mm-yyyy): ";
            $manual = trim(fgets(STDIN));

            if ($manual === "") {
                if (confirmStop()) exit("Đã dừng tạo key\n");
                return calcExpire($index);
            }

            $date = DateTime::createFromFormat('d-m-Y', $manual);
            if (!$date) {
                echo "Sai format → +1 ngày\n";
                return date("d-m-Y", strtotime("+1 day"));
            }
            return $manual;

        default:
            return date("d-m-Y", strtotime("+1 day"));
    }
}

/* ================= START ================= */

echo "===== TOOL TẠO KEY PRO MAX =====\n";
echo "[1] Random key\n";
echo "[2] Nhập tay\n";
echo "Chọn: ";
$mode = trim(fgets(STDIN));

echo "Số lượng key: ";
$count = (int)trim(fgets(STDIN));

if ($count <= 0) {
    exit("Số lượng không hợp lệ\n");
}

/* ===== GLOBAL NOTE ===== */
echo "Nhập tên người dùng chung (Enter để bỏ qua): ";
$globalNote = trim(fgets(STDIN));

$useGlobal = false;
if ($globalNote !== "") {
    echo "Áp dụng tên này cho tất cả key? (y/n): ";
    $useGlobal = strtolower(trim(fgets(STDIN))) === 'y';
}

$data = [];

for ($i = 1; $i <= $count; $i++) {

    echo "\n===== KEY $i =====\n";

    /* ===== KEY ===== */
    if ($mode == "1") {
        $key = randomKey(12);
        echo "Key auto: $key\n";
    } elseif ($mode == "2") {
        echo "Nhập key (Enter để dừng): ";
        $key = trim(fgets(STDIN));

        if ($key === "") {
            if (confirmStop()) break;
            $i--; // retry lại key này
            continue;
        }
    } else {
        exit("Sai chế độ\n");
    }

    /* ===== NOTE ===== */
    if ($useGlobal) {
        $note = $globalNote;
    } else {
        echo "Tên người dùng (Enter để dừng): ";
        $note = trim(fgets(STDIN));

        if ($note === "") {
            if (confirmStop()) break;
            $i--;
            continue;
        }
    }

    /* ===== EXPIRE ===== */
    $expire = calcExpire($i - 1);

    /* ===== HASH ===== */
    $hash = md5($key);

    echo "MD5: $hash\n";
    echo "Expire: $expire\n";

    $data[] = [
        "key" => $hash,
        "expire" => $expire,
        "note" => $note
    ];
}

/* ===== SLOT RỖNG ===== */
echo "\nThêm slot trống? (Enter = 5): ";
$empty = trim(fgets(STDIN));
if ($empty == "") $empty = 5;

for ($i = 0; $i < $empty; $i++) {
    $data[] = [
        "key" => "",
        "expire" => "",
        "note" => ""
    ];
}

/* ===== EXPORT JSON ===== */
$json = [
    "status" => "success",
    "data" => $data
];

file_put_contents("key_api.json", json_encode($json, JSON_PRETTY_PRINT));

echo "\n✅ Đã tạo file key_api.json\n";
echo "👉 Upload lên Wiremock là dùng được\n";