<?php   // init.php
/*
 * 初期処理
 */
//
ob_start();
session_start();

// CSRF対策用
require_once( __DIR__ . '/Csrf.php');
// Util
require_once( __DIR__ . '/Util.php');

// DB接続
$dsn = 'mysql:host=localhost;dbname=bbs_2021;charset=utf8mb4';
$user = 'bbs2021_user';
$password = 'bbs2021_pass';
$options = [
    \PDO::ATTR_EMULATE_PREPARES => false,  // 静的プレースホルダにする
    \PDO::MYSQL_ATTR_MULTI_STATEMENTS => false, // 複文を禁止する
    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION, // エラー時に例外を投げる
];
//
try {
    $dbh = new PDO($dsn, $user, $password, $options);
} catch (\PDOException $e) {
    echo "Error\n";
    echo $e->getMessage(); // 本来はlogとかに出力する
    exit;
}
//var_dump($dbh);

