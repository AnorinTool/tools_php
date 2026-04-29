<?php
@system("clear");
echo"=== [Tool Encode By An Orin] ===\n\n";
echo "Nhập File Cần Encode: ";
$input = trim(fgets(STDIN));

if (!file_exists($input)) {
    exit("Không Tìm Thấy File\n");
}

echo "Nhập File Encode Xong Tên Gì Hoặc (" . pathinfo($input, PATHINFO_FILENAME) . "_enc.php): ";
$output = trim(fgets(STDIN));

if ($output == "") {
    $name = pathinfo($input, PATHINFO_FILENAME);
    $output = $name . "_enc.php";
}

echo "Ghi dấu bản quyền: ";
$mark = trim(fgets(STDIN));

/* ===== READ ===== */
$code = file_get_contents($input);

/* ===== OBFUSCATE NHẸ ===== */
$code = strrev($code);

/* ===== MULTI ENCODE ===== */
$round = 5;
$payload = $code;

for ($i = 0; $i < $round; $i++) {
    $payload = base64_encode(gzdeflate($payload));
}

/* ===== SPLIT NHƯNG KHÔNG PHÁ THỨ TỰ ===== */
$chunks = str_split($payload, rand(20, 50));

/* ===== RANDOM BIẾN ===== */
function r($l=5){
    $c='abcdefghijklmnopqrstuvwxyz';
    $s='';
    for($i=0;$i<$l;$i++){
        $s.=$c[rand(0,25)];
    }
    return $s;
}

$v1=r(); $v2=r(); $v3=r(); $v4=r(); $v5=r();

/* ===== EXPORT ===== */
$c = var_export($chunks, true);

/* ===== LOADER ===== */
$loader = <<<PHP
<?php
/* {$mark} */

\${$v1} = $c;

/* ghép lại */
\${$v2} = '';
foreach(\${$v1} as \${$v3}){
    \${$v2} .= \${$v3};
}

/* hidden func */
\${$v4} = 'ba'.'se'.'64'.'_de'.'co'.'de';
\${$v5} = 'gz'.'in'.'fl'.'ate';

/* decode */
\$_x = \${$v2};
for(\$_i=0;\$_i<$round;\$_i++){
    \$_x = \${$v5}(\${$v4}(\$_x));
}

/* restore */
\$_x = strrev(\$_x);

/* run */
\$_f = sys_get_temp_dir().'/'.md5(\$_x).'.php';
file_put_contents(\$_f, \$_x);
include \$_f;
unlink(\$_f);
PHP;

/* ===== SAVE ===== */
file_put_contents($output, $loader);

echo "Encode Thành Công!: $output\n";