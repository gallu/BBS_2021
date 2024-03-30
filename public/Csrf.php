<?php   // Csrf.php
/**
// 設定する時
$token = Csrf::set();

// チェックする時
$r = Csrf::check();
if (false === $r) {
    だめぽ
}
 */
//
class Csrf {
    //
    const SESSION_KEY = 'csrf_token';
    
    // 設定する時
    public static function set() {
        // tokenを作る
        $csrf_token = bin2hex( random_bytes(24) );

        // CSRFトークンは5個まで(で後で追加するので、ここでは4個以下に)
        while (5 <= count($_SESSION[self::SESSION_KEY] ?? [])) {
            array_shift($_SESSION[self::SESSION_KEY]);
        }

        // tokenをセッションに格納する
        $_SESSION[self::SESSION_KEY][$csrf_token] = time();

        // tokenをreturnする
        return $csrf_token;
    }
    
    // チェックする時
    public static function check() {
        // POSTからtoken取得
        $post_token = strval($_POST['csrf_token'] ?? '');

        // sessionからtokenのTTL取得
        $session_datetime = intval($_SESSION[self::SESSION_KEY][$post_token] ?? 0);
        // sessionから取得したtoken削除
        unset($_SESSION[self::SESSION_KEY][$post_token]);

        // 寿命確認
        if (time() >= $session_datetime + 60) {
            return false;
        }

        //
        return true;
    }
}