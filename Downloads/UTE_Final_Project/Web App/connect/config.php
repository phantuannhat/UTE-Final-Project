<?php
define('HACKER', 'Lỗi không thể truy cập đường dẫn này !');
define('SERVERNAME', 'localhost');
define('USERNAME', 'root');
define('PASSWORD', '');
define('DATABASE', 'du_an');
$connect = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE);
if ($connect->connect_error) {
    die("Không thể kết nối");
    exit();
}
mysqli_set_charset($connect, "utf8");
date_default_timezone_set('Asia/Ho_Chi_Minh');
$datetime = date('Y-m-d H:i:s');

$config = array(
    'KEYMAPAPI' => 'AIzaSyBH6z9pLP8iIZWzfXFBV_XUjrAY27Vo2XM',
    'TOADOTRUNGTAM' => '16.0765639, 108.2143147',
    'ZOOM' => '16'
);