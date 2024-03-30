<?php  // index.php
// https://dev2.m-fr.net/アカウント名/BBS_2021/
//
require_once( __DIR__ . '/init.php' );
require_once( __DIR__ . '/../vendor/autoload.php');
//
use Twig\Loader\FilesystemLoader;
use Twig\Environment;
//
$twig = new Environment(new FilesystemLoader(__DIR__ . '/../templates'));

// 1ページあたりの表示数
$limit_num = 10;
// ページ数の取得(1 start)
$p = intval($_GET['p'] ?? 1);
if (1 > $p) {
    $p = 1;
}

// プリペアドステートメントを作成
$sql = 'SELECT * FROM bbses ORDER BY created_at DESC LIMIT :limit_num OFFSET :offset_num ;';
$pre = $dbh->prepare($sql);
//var_dump($pre);

// 値をバインド
$pre->bindValue(':limit_num', $limit_num + 1); // 「次があるか」を把握したいので +1
$pre->bindValue(':offset_num', $limit_num * ($p - 1));

// SQLを実行
$r = $pre->execute();
//var_dump($r);

// データを読み込み
$list = $pre->fetchAll(\PDO::FETCH_ASSOC);
//var_dump($list);

// 前後のページ数の把握
if (count($list) === $limit_num + 1) {
    //echo "次のページ、あるよ<br>\n";
    $next_flg = true;
    // 末尾のデータを１つ除去
    array_pop($list);
} else {
    //echo "次のページ、ないよ...\n";
    $next_flg = false;
}

// コメントを付与
// プリペアドステートメントは使いまわす
$sql = 'SELECT * FROM comments WHERE bbs_id = :bbs_id ORDER BY created_at DESC;';
$pre = $dbh->prepare($sql);
//var_dump($pre);
foreach($list as $k => $v) {
    // コメントを取得
    // 値をバインド
    $pre->bindValue(':bbs_id', $v['bbs_id']);
    // SQLを実行
    $r = $pre->execute();
    //
    $list[$k]['comment'] = $pre->fetchAll(\PDO::FETCH_ASSOC);
}
//var_dump($list); exit;

//
$flash_session = $_SESSION['flash'] ?? [];
unset($_SESSION['flash']);
//var_dump($flash_session);

// CSRFトークンの取得
$csrf_token = Csrf::set();

//
$context = [
    'flash' => $flash_session,
    'list' => $list,
    // ページング要素
    'page_num' => $p,
    'before_page_num' => $p - 1,
    'next_page_num' => $p + 1,
    'next_flg' => $next_flg,
    //
    'csrf_token' => $csrf_token,
];

//
echo $twig->render('index.twig', $context);
