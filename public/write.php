<?php   // write.php
//
require_once( __DIR__ . '/init.php' );

// formからの情報を取得
$params = [
    'handle' => '',
    'title' => 'must',
    'del_code' => '',
    'body' => 'must',
];
//
$datum = [];
$error = [];
//
foreach($params as $k => $v) {
    // データの取得
    $datum[$k] = strval($_POST[$k] ?? '');

    // validate
    if ('must' === $v) {
        if ('' === $datum[$k]) {
            $error[$k] = true;
        }
    }
}
// CSRFチェック
if (false === Csrf::check()) {
        $error['csrf'] = true;
}

//
var_dump($datum, $error);

// エラーなら突き返す
if ([] !== $error) {
    //
    $_SESSION['flash']['error'] = $error;
    $_SESSION['flash']['datum'] = $datum;
    
    //
    header('Location: ./index.php');
    exit;
}

// 接続ユーザの情報を把握
$datum = $datum + Util::getConnectionUserInfo();
//var_dump($datum); exit;

/* 「書き込まれた内容」をDBに書き込む */
// プリペアドステートメントを用意
$sql = 'INSERT INTO bbses(handle, title, del_code, body, user_agent, from_ip, created_at)
            VALUES(:handle, :title, :del_code, :body, :user_agent, :from_ip, :created_at);';
$pre = $dbh->prepare($sql);
var_dump($pre);

// プレースホルダに値をバインド
$datum['created_at'] = date('Y-m-d H:i:s');
foreach($datum as $k => $v) {
    $pre->bindValue(":{$k}", $v);
}

// SQLを実行
$r = $pre->execute();
var_dump($r);

// 「書いたよ」的な出力
$_SESSION['flash']['success'] = $r;
// SQLでエラーだったら書いた内容も戻してあげる
if (false === $r) {
    $_SESSION['flash']['datum'] = $datum;
}
header('Location: ./index.php');
