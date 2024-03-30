<?php   // delete.php
//
require_once( __DIR__ . '/init.php' );
//
try {
    // データを取得
    $bbs_id = strval($_POST['bbs_id'] ?? '');
    $del_code = strval($_POST['del_code'] ?? '');
    
    // validate
    if ( ('' === $bbs_id)||('' === $del_code) ) {
        throw new \Exception('validate');
    }

    // CSRFチェック
    if (false === Csrf::check()) {
        throw new \Exception('csrf');
    }
    
    // bbs_idでレコードを取得
    // プリペアドステートメントを作成
    $sql = 'SELECT * FROM bbses WHERE bbs_id = :bbs_id;';
    $pre = $dbh->prepare($sql);
    var_dump($pre);
    // プレースホルダに値をバインド
    $pre->bindValue(":bbs_id", $bbs_id);
    // SQLを実行
    $r = $pre->execute();
    var_dump($r);
    if (false === $r) {
        throw new \Exception('DB error');
    }
    // データを読み込み
    $bbs = $pre->fetch(\PDO::FETCH_ASSOC);
    if (false === $bbs) {
        throw new \Exception('empty');
    }
var_dump($bbs);

    // del_codeが一致していること
    if ($del_code !== $bbs['del_code']) {
        throw new \Exception('del_code');
    }

    // トランザクション
    $dbh->beginTransaction();

    // 対象のレコードを削除
    $sql = 'DELETE FROM comments WHERE bbs_id = :bbs_id;';
    $pre = $dbh->prepare($sql);
    // プレースホルダに値をバインド
    $pre->bindValue(":bbs_id", $bbs_id);
    // SQLを実行
    $r = $pre->execute();
    if (false === $r) {
        throw new \Exception('DB error(delete comments)');
    }
    //
    $sql = 'DELETE FROM bbses WHERE bbs_id = :bbs_id;';
    $pre = $dbh->prepare($sql);
var_dump($pre);
    // プレースホルダに値をバインド
    $pre->bindValue(":bbs_id", $bbs_id);
    // SQLを実行
    $r = $pre->execute();
var_dump($r);
    if (false === $r) {
        throw new \Exception('DB error(delete bbses)');
    }
    
    // トランザクション終了
    $dbh->commit();
    // 削除OKのメッセージ
    $_SESSION['flash']['delete'] = true;
} catch(\Throwable $e) {
    // トランザクション内なら、rollback
    if (true === $dbh->inTransaction()) {
        $dbh->rollback();
    }
    
    // XXX 特になにもしない
    echo $e->getMessage();
}

// index.phpに遷移
header('Location: ./index.php');
