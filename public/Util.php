<?php   // Util.php

class Util {
    // 接続ユーザの「user-agent」と「接続元IP」を取得する
    public static function getConnectionUserInfo()
    {
        $datum = [];
        $datum['user_agent'] = $_SERVER['HTTP_USER_AGENT'] ?? '';
        $datum['from_ip'] = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? '';
        //
        return $datum;
    }

}