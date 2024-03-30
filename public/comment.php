<?php   // comment.php
//
require_once( __DIR__ . '/init.php' );

try {
    // CSRFチェック
    if (false === Csrf::check()) {
        throw new \Exception('csrf');
    }
    // コメント本文の取得
    $comment_body = strval($_POST['comment'] ?? '');
    if ('' === $comment_body) {
        throw new \Exception('comment');
    }
    // bbs_idの取得
    $bbs_id = intval($_POST['bbs_id'] ?? 0);
    if (0 >= $bbs_id) {
        throw new \Exception('bbs_id');
    }

    // 接続ID等の把握
    $datum = Util::getConnectionUserInfo();
    // 残りのデータを入れる
    $datum['bbs_id'] = $bbs_id;
    $datum['comment_body'] = $comment_body;
    $datum['created_at'] = date('Y-m-d H:i:s');
//var_dump($datum); exit;

    // DBにinsert
    $sql = 'INSERT INTO comments(bbs_id, comment_body, user_agent, from_ip, created_at)
                   VALUES(:bbs_id, :comment_body, :user_agent, :from_ip, :created_at);';
    $pre = $dbh->prepare($sql);
var_dump($pre);

    // プレースホルダに値をバインド
    foreach($datum as $k => $v) {
        $pre->bindValue(":{$k}", $v);
    }

    // SQLを実行
    $r = $pre->execute();
var_dump($r);

} catch(\Throwable $e) {
    // XXX 特になにもしない
    // XXX 業務だと、ログを取るくらい
    echo $e->getMessage();
}

// index.phpにリダイレクト
$page_num = intval($_POST['page_num'] ?? 0);
header('Location: ./index.php?p=' . rawurlencode($page_num) . '#' . rawurlencode($bbs_id));
