<?php
function mahoa($input) {
    $mapping = [
        'a' => '⊀', 'b' => '⊁', 'c' => '⊂', 'd' => '⊃', 'e' => '⊄',
        'f' => '⊅', 'g' => '⊆', 'h' => '⊇', 'i' => '⊈', 'j' => '⊉',
        'k' => '⊊', 'l' => '⊋', 'm' => '⊏', 'n' => '⊐', 'o' => '⊑',
        'p' => '⊒', 'q' => '⊜', 'r' => '⊖', 's' => '⊗', 't' => '⊘',
        'u' => '⊙', 'v' => '⊚', 'w' => '⊛', 'x' => '⊕', 'y' => '⊝',
        'z' => '⊞', '0' => '⊟', '1' => '⊠', '2' => '⊡', '3' => '⊢',
        '4' => '⊣', '5' => '⊤', '6' => '⊥', '7' => '♘', '8' => '⊧',
        '9' => '♙', 'A' => '☾', 'B' => '☽', 'C' => '⊫', 'D' => '⊬',
        'E' => '⊭', 'F' => '⊮', 'G' => '⊯', 'H' => '⊰', 'I' => '⊱',
        'J' => '⊲', 'K' => '⊳', 'L' => '⊴', 'M' => '⊵', 'N' => '⊩',
        'O' => '☼', 'P' => '⊾', 'Q' => '⊿', 'R' => '⋀', 'S' => '⋁',
        'T' => '⋂', 'U' => '⋃', 'V' => '⋄', 'W' => '♖', 'X' => '♗',
        'Y' => '♕', 'Z' => '♔'
    ];

    $encoded = '';
    foreach (mb_str_split($input) as $char) {
        $encoded .= $mapping[$char] ?? $char;
    }
    return base64_encode(mb_convert_encoding($encoded, 'UTF-8'));
}

function mahoaFile($filePath, $xoaPHPTag = true, $xoaFileGoc = true) {
    if (!file_exists($filePath)) {
        echo "File không tồn tại: $filePath\n";
        return;
    }

    $content = file_get_contents($filePath);
    if ($xoaPHPTag) {
        $content = str_replace('<?php', '', $content);
    }

    $encoded = mahoa($content);
    $outputFile = pathinfo($filePath, PATHINFO_FILENAME) . '_enc.php';

    file_put_contents(dirname($filePath) . DIRECTORY_SEPARATOR . $outputFile, $encoded);
    
    if ($xoaFileGoc) {
        unlink($filePath);
        echo "Đã mã hóa và xóa file gốc: $filePath\n";
    } else {
        echo "Đã mã hóa file: $filePath (không xóa file gốc)\n";
    }
}

function mahoaThuMuc($dirPath, $xoaPHPTag = true, $xoaFileGoc = true) {
    $files = scandir($dirPath);
    foreach ($files as $file) {
        $fullPath = $dirPath . DIRECTORY_SEPARATOR . $file;
        if (is_file($fullPath) && pathinfo($fullPath, PATHINFO_EXTENSION) === 'php') {
            mahoaFile($fullPath, $xoaPHPTag, $xoaFileGoc);
        }
    }
}

// Menu lựa chọn
echo "Bạn muốn encode:\n";
echo "1. Một file\n";
echo "2. Cả thư mục\n";
echo "Lựa chọn của bạn (1 hoặc 2): ";
$luaChon = trim(fgets(STDIN));

// Hỏi người dùng có muốn xóa '<?php' hay không
echo "Bạn có muốn xóa phần '<?php' trong code trước khi encode? (y/n): ";
$xoaTag = strtolower(trim(fgets(STDIN))) === 'y';

// Hỏi người dùng có muốn xóa file gốc hay không
echo "Bạn có muốn xóa file gốc sau khi encode không? (y/n): ";
$xoaFile = strtolower(trim(fgets(STDIN))) === 'y';

if ($luaChon == '1') {
    echo "Nhập đường dẫn file PHP: ";
    $file = trim(fgets(STDIN));
    mahoaFile($file, $xoaTag, $xoaFile);
} elseif ($luaChon == '2') {
    echo "Nhập đường dẫn thư mục: ";
    $dir = trim(fgets(STDIN));
    mahoaThuMuc($dir, $xoaTag, $xoaFile);
} else {
    echo "Lựa chọn không hợp lệ!\n";
}
?>